<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';

$managerId = $_SESSION['user_id'];
$error     = '';
$success   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $current = trim($_POST['current_password'] ?? '');
    $new     = trim($_POST['new_password']     ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if (empty($name)) {
        $error = 'Name cannot be empty.';
    } else {
        $conn = getDB();

        if (!empty($_FILES['profile_pic']['name'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/profiles/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $ext     = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg','jpeg','png','webp'];
            if (in_array(strtolower($ext), $allowed)) {
                $filename = 'profile_' . $managerId . '.' . $ext;
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadDir . $filename)) {
                    $s = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
                    $s->bind_param('si', $filename, $managerId);
                    $s->execute();
                    $s->close();
                }
            }
        }

        if (!empty($new)) {
            if (strlen($new) < 6) {
                $error = 'New password must be at least 6 characters.';
            } elseif ($new !== $confirm) {
                $error = 'New passwords do not match.';
            } else {
                $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
                $stmt->bind_param('i', $managerId);
                $stmt->execute();
                $stmt->bind_result($hash);
                $stmt->fetch();
                $stmt->close();

                if (!password_verify($current, $hash)) {
                    $error = 'Current password is incorrect.';
                } else {
                    $newHash = password_hash($new, PASSWORD_BCRYPT);
                    $stmt    = $conn->prepare("UPDATE users SET name=?, phone=?, password_hash=? WHERE id=?");
                    $stmt->bind_param('sssi', $name, $phone, $newHash, $managerId);
                    $stmt->execute();
                    $stmt->close();
                    $_SESSION['user_name'] = $name;
                    $success = 'Profile and password updated successfully!';
                }
            }
        } else {
            $stmt = $conn->prepare("UPDATE users SET name=?, phone=? WHERE id=?");
            $stmt->bind_param('ssi', $name, $phone, $managerId);
            $stmt->execute();
            $stmt->close();
            $_SESSION['user_name'] = $name;
            $success = 'Profile updated successfully!';
        }

        $conn->close();
    }
}

$conn = getDB();
$stmt = $conn->prepare("SELECT name, email, phone, created_at, profile_pic FROM users WHERE id = ?");
$stmt->bind_param('i', $managerId);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();
$conn->close();

$activePage = 'profile';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">My Profile</h4>
    <p class="text-muted mb-0" style="font-size:14px;">Manage your account information</p>
  </div>
</div>

<?php if ($error): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="row g-4">

  <div class="col-md-4">
    <div class="card text-center p-4">

      <?php
      $picPath = !empty($user['profile_pic'])
          ? '/webtechproject/WebtechProject/public/uploads/profiles/' . $user['profile_pic']
          : null;
      ?>

      <?php if ($picPath): ?>
        <img src="<?= htmlspecialchars($picPath) ?>"
             id="profilePreview"
             class="rounded-circle mx-auto mb-3"
             style="width:90px; height:90px; object-fit:cover; border:3px solid #eff6ff;">
      <?php else: ?>
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
             id="profileIcon"
             style="width:90px; height:90px; background:#eff6ff; font-size:36px; color:#3b82f6;">
          <i class="bi bi-person"></i>
        </div>
      <?php endif; ?>

      <h5 class="fw-semibold mb-1"><?= htmlspecialchars($user['name']) ?></h5>
      <p class="text-muted mb-1" style="font-size:13px;"><?= htmlspecialchars($user['email']) ?></p>
      <span class="badge bg-primary">Venue Manager</span>
      <hr>
      <p class="text-muted mb-2" style="font-size:12px;">
        Member since <?= date('M Y', strtotime($user['created_at'])) ?>
      </p>

    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <i class="bi bi-pencil me-2"></i> Edit Profile
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label">Profile Photo</label>
            <div class="d-flex align-items-center gap-3">
              <label class="btn btn-outline-secondary btn-sm" style="cursor:pointer; margin:0;">
                <i class="bi bi-camera me-1"></i> Upload Photo
                <input type="file" name="profile_pic" accept="image/*"
                       style="display:none;" onchange="previewPic(this)">
              </label>
              <small class="text-muted">JPG, PNG, WEBP — Max 2MB</small>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($user['name']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control"
                   value="<?= htmlspecialchars($user['email']) ?>" disabled>
            <small class="text-muted">Email cannot be changed</small>
          </div>

          <div class="mb-4">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control"
                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                   placeholder="e.g. 01711000000">
          </div>

          <hr>
          <p class="fw-semibold mb-3">Change Password
            <small class="text-muted fw-normal">(leave blank to keep current)</small>
          </p>

          <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control"
                   placeholder="Enter current password">
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">New Password</label>
              <input type="password" name="new_password" class="form-control"
                     placeholder="Minimum 6 characters">
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirm New Password</label>
              <input type="password" name="confirm_password" class="form-control"
                     placeholder="Re-enter new password">
            </div>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="bi bi-floppy me-2"></i> Save Changes
          </button>

        </form>
      </div>
    </div>
  </div>

</div>

<script>
function previewPic(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById('profilePreview');
            const icon  = document.getElementById('profileIcon');
            if (!preview) {
                if (icon) icon.outerHTML = '<img id="profilePreview" class="rounded-circle mx-auto mb-3" style="width:90px; height:90px; object-fit:cover; border:3px solid #eff6ff;">';
                preview = document.getElementById('profilePreview');
            }
            if (preview) preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../shared/footer.php'; ?>