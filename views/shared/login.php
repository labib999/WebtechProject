<?php
session_start();
require_once '../../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $conn = getDB();
        $stmt = $conn->prepare("SELECT id, name, email, password_hash, role, is_active FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        if (!$user) {
            $error = 'No account found with that email.';
        } elseif ($user['role'] !== 'venue_manager') {
            $error = 'This portal is for Venue Managers only.';
        } elseif ($user['is_active'] == 0) {
            $error = 'Your account is pending admin approval.';
        } elseif (!password_verify($password, $user['password_hash'])) {
            $error = 'Incorrect password. Please try again.';
        } else {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role']       = $user['role'];
            header('Location: http://localhost/webtechproject/WebtechProject/views/venue/dashboard.php');
            exit;
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="min-vh-100 d-flex" style="background:#f8fafc;">

  <div class="d-none d-md-flex flex-column align-items-center justify-content-center p-5"
       style="width:50%; background:#1e293b;">
    <h2 class="text-white fw-bold mb-2">EM<span style="color:#3b82f6">TS</span></h2>
    <p class="text-center mb-5" style="color:#94a3b8; font-size:15px;">
      Event Management & Ticketing System
    </p>
    <div class="w-100" style="max-width:320px;">
      <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:rgba(255,255,255,0.05);">
        <i class="bi bi-building fs-4" style="color:#3b82f6;"></i>
        <span style="color:#cbd5e1; font-size:14px;">Manage multiple venue properties</span>
      </div>
      <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:rgba(255,255,255,0.05);">
        <i class="bi bi-calendar3 fs-4" style="color:#3b82f6;"></i>
        <span style="color:#cbd5e1; font-size:14px;">Visual availability calendar</span>
      </div>
      <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3" style="background:rgba(255,255,255,0.05);">
        <i class="bi bi-inbox fs-4" style="color:#3b82f6;"></i>
        <span style="color:#cbd5e1; font-size:14px;">Approve organiser booking requests</span>
      </div>
      <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:rgba(255,255,255,0.05);">
        <i class="bi bi-bar-chart fs-4" style="color:#3b82f6;"></i>
        <span style="color:#cbd5e1; font-size:14px;">Revenue and occupancy reports</span>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center justify-content-center p-4" style="width:50%;">
    <div class="w-100" style="max-width:400px;">

      <h4 class="fw-bold mb-1">Welcome back 👋</h4>
      <p class="text-muted mb-4" style="font-size:14px;">Sign in to your Venue Manager account</p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="card p-4">
        <form method="POST" autocomplete="off">

          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control"
                     placeholder="rohit@emts.com"
                     autocomplete="off"
                     value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" name="password" id="passwordInput" class="form-control"
                     placeholder="Enter your password"
                     autocomplete="new-password">
              <button type="button" class="btn btn-outline-secondary"
                      onclick="togglePassword()">
                <i class="bi bi-eye" id="eyeIcon"></i>
              </button>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
          </button>

        </form>
      </div>

      <p class="text-center text-muted mt-3" style="font-size:14px;">
        Don't have an account?
        <a href="register.php" style="color:#3b82f6;">Register here</a>
      </p>

    </div>
  </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type     = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type     = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>

<?php
$skipNavbar = true;
include 'footer.php';
?>