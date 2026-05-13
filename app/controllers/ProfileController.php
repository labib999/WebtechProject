<?php
class ProfileController extends Controller {

    public function show() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

        $userRow = $db->query("SELECT * FROM users WHERE id=?", "i", [$orgId]);
        $profRow = $db->query(
            "SELECT * FROM organiser_profiles WHERE user_id=?", "i", [$orgId]);

        $user    = $userRow[0] ?? [];
        $profile = $profRow[0] ?? [];
        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');
        $this->view('organiser/profile', compact('user','profile','success','error'));
    }

    public function update() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

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
                $name2 = uniqid('logo_') . '.' . $ext;
                $dir  = __DIR__ . '/../../public/assets/uploads/logos/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                move_uploaded_file($_FILES['logo']['tmp_name'], $dir . $name2);
                $logoPath = 'assets/uploads/logos/' . $name2;
            }
        }

        // Update users table
        $db->execute(
            "UPDATE users SET name=?, phone=? WHERE id=?",
            "ssi", [$name, $phone, $orgId]
        );

        // Update organiser_profiles
        if ($logoPath) {
            $db->execute(
                "UPDATE organiser_profiles SET org_name=?,org_description=?,website=?,org_logo_path=? WHERE user_id=?",
                "ssssi", [$orgName, $orgDesc, $website, $logoPath, $orgId]
            );
        } else {
            $db->execute(
                "UPDATE organiser_profiles SET org_name=?,org_description=?,website=? WHERE user_id=?",
                "sssi", [$orgName, $orgDesc, $website, $orgId]
            );
        }

        // Optional password change
        $newPass = trim($_POST['new_password']    ?? '');
        $confirm = trim($_POST['confirm_password']?? '');
        if (!empty($newPass)) {
            if (strlen($newPass) < 8) {
                Session::setFlash('error', 'New password must be at least 8 characters.');
                $this->redirect('organiser/profile'); return;
            }
            if ($newPass !== $confirm) {
                Session::setFlash('error', 'Passwords do not match.');
                $this->redirect('organiser/profile'); return;
            }
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $db->execute("UPDATE users SET password_hash=? WHERE id=?", "si", [$hash, $orgId]);
        }

        Session::setFlash('success', 'Profile updated successfully.');
        $this->redirect('organiser/profile');
    }
}