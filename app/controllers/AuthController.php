<?php
class AuthController extends Controller {

    // Show login form
    public function showLogin() {
        if (Auth::isLoggedIn()) {
            $this->redirectByRole(Auth::userRole());
        }
        $error   = Session::getFlash('error');
        $success = Session::getFlash('success');
        $panel = 'login';
        $this->view('auth/auth', compact('panel', 'error', 'success'));
    }

    // Process login form submission
    public function processLogin() {
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            Session::setFlash('error', 'Email and password are required.');
            $this->redirect('login');
            return;
        }

        $db   = Database::getInstance();
        $rows = $db->query(
            "SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1",
            "s", [$email]
        );

        if (empty($rows) || !password_verify($password, $rows[0]['password_hash'])) {
            Session::setFlash('error', 'Invalid email or password.');
            $this->redirect('login');
            return;
        }

        $user = $rows[0];

        // Organisers must be approved before they can log in
        if ($user['role'] === 'organiser') {
            $profile = $db->query(
                "SELECT status FROM organiser_profiles WHERE user_id = ?",
                "i", [$user['id']]
            );
            if (!empty($profile) && $profile[0]['status'] !== 'approved') {
                Session::setFlash('error', 'Your account is pending admin approval.');
                $this->redirect('login');
                return;
            }
        }

        Auth::login($user);
        $this->redirectByRole($user['role']);
    }

    // Show registration form
    public function showRegister() {
        if (Auth::isLoggedIn()) {
            $this->redirectByRole(Auth::userRole());
        }
        $error = Session::getFlash('error');
        $panel = 'register';
        $this->view('auth/auth', compact('panel', 'error'));
    }

    // Process registration form submission
    public function processRegister() {
        $name    = trim($_POST['name']             ?? '');
        $email   = trim($_POST['email']            ?? '');
        $phone   = trim($_POST['phone']            ?? '');
        $pass    = trim($_POST['password']         ?? '');
        $confirm = trim($_POST['confirm_password'] ?? '');
        $orgName = trim($_POST['org_name']         ?? '');
        $orgDesc = trim($_POST['org_description']  ?? '');

        if (empty($name) || empty($email) || empty($pass) || empty($orgName)) {
            Session::setFlash('error', 'Please fill in all required fields.');
            $this->redirect('register');
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::setFlash('error', 'Please enter a valid email address.');
            $this->redirect('register');
            return;
        }
        if (strlen($pass) < 8) {
            Session::setFlash('error', 'Password must be at least 8 characters.');
            $this->redirect('register');
            return;
        }
        if ($pass !== $confirm) {
            Session::setFlash('error', 'Passwords do not match.');
            $this->redirect('register');
            return;
        }

        $db = Database::getInstance();

        $existing = $db->query("SELECT id FROM users WHERE email = ?", "s", [$email]);
        if (!empty($existing)) {
            Session::setFlash('error', 'An account with this email already exists.');
            $this->redirect('register');
            return;
        }

        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $db->execute(
            "INSERT INTO users (name, email, password_hash, phone, role) VALUES (?, ?, ?, ?, 'organiser')",
            "ssss", [$name, $email, $hash, $phone]
        );
        $userId = $db->lastInsertId();

        $db->execute(
            "INSERT INTO organiser_profiles (user_id, org_name, org_description, status) VALUES (?, ?, ?, 'pending')",
            "iss", [$userId, $orgName, $orgDesc]
        );

        Session::setFlash('success', 'Registration submitted. Awaiting admin approval.');
        $this->redirect('login');
    }

    // Logout
    public function logout() {
        Auth::logout();
    }

    // Send user to correct dashboard based on role
    private function redirectByRole($role) {
        $routes = [
            'organiser'     => 'organiser/dashboard',
            'attendee'      => 'attendee/dashboard',
            'venue_manager' => 'venue/dashboard',
            'admin'         => 'admin/dashboard',
        ];
        $this->redirect($routes[$role] ?? 'login');
    }
}