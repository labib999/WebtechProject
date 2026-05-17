<?php
class ProfileController extends Controller {

    private $profileModel;

    public function __construct() {
        $this->profileModel = new ProfileModel();
    }

    public function show() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $user    = $this->profileModel->getUser($orgId);
        $profile = $this->profileModel->getProfile($orgId);
        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');

        $this->view('organiser/profile',
            compact('user','profile','success','error'));
    }

    public function update() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $name    = trim($_POST['name']            ?? '');
        $phone   = trim($_POST['phone']           ?? '');
        $orgName = trim($_POST['org_name']        ?? '');
        $orgDesc = trim($_POST['org_description'] ?? '');
        $website = trim($_POST['website']         ?? '');

        if (empty($name) || empty($orgName)) {
            Session::setFlash('error', 'Name and organisation name are required.');
            $this->redirect('organiser/profile'); return;
        }

        // Handle logo upload
        $logoPath = null;
        if (!empty($_FILES['logo']['name'])) {
            $allowed  = ['image/jpeg','image/png','image/webp'];
            $mimeType = mime_content_type($_FILES['logo']['tmp_name']);
            if (in_array($mimeType, $allowed) && $_FILES['logo']['size'] <= 2097152) {
                $ext  = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                $filename = uniqid('logo_') . '.' . $ext;
                $dir  = __DIR__ . '/../public/assets/uploads/logos/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                move_uploaded_file($_FILES['logo']['tmp_name'], $dir . $filename);
                $logoPath = 'assets/uploads/logos/' . $filename;
            }
        }

        // Update user and profile
        $this->profileModel->updateUser($orgId, $name, $phone);

        if ($logoPath) {
            $this->profileModel->updateProfileWithLogo($orgId, $orgName, $orgDesc, $website, $logoPath);
        } else {
            $this->profileModel->updateProfile($orgId, $orgName, $orgDesc, $website);
        }

        // Optional password change
        $newPass = trim($_POST['new_password']     ?? '');
        $confirm = trim($_POST['confirm_password'] ?? '');
        if (!empty($newPass)) {
            if (strlen($newPass) < 8) {
                Session::setFlash('error', 'New password must be at least 8 characters.');
                $this->redirect('organiser/profile'); return;
            }
            if ($newPass !== $confirm) {
                Session::setFlash('error', 'Passwords do not match.');
                $this->redirect('organiser/profile'); return;
            }
            $this->profileModel->updatePassword($orgId, password_hash($newPass, PASSWORD_DEFAULT));
        }

        Session::setFlash('success', 'Profile updated successfully.');
        $this->redirect('organiser/profile');
    }
}