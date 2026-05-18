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

$picPath = !empty($user['profile_pic'])
    ? '/webtechproject/WebtechProject/public/uploads/profiles/' . $user['profile_pic']
    : null;
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">My Profile</h4>
    <p class="text-muted mb-0" style="font-size:14px;">Manage your account and security settings</p>
  </div>
</div>

<!-- Alerts -->
<?php if ($error): ?>
  <div class="alert alert-danger d-flex align-items-center gap-2 mb-4"
       style="border-radius:10px; font-size:14px;" id="alertMsg">
    <i class="bi bi-exclamation-circle-fill"></i>
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>
<?php if ($success): ?>
  <div class="alert alert-success d-flex align-items-center gap-2 mb-4"
       style="border-radius:10px; font-size:14px;" id="alertMsg">
    <i class="bi bi-check-circle-fill"></i>
    <?= htmlspecialchars($success) ?>
  </div>
<?php endif; ?>

<div class="row g-4">

  <!-- Left Card -->
  <div class="col-md-4">
    <div class="rounded-3 overflow-hidden" style="background:#fff; border:1px solid #e2e8f0;">

      <!-- Banner -->
      <div style="height:80px; background:linear-gradient(135deg, #0f172a, #1e3a5f, #1e40af);"></div>

      <!-- Avatar -->
      <div class="text-center" style="margin-top:-45px; padding:0 20px 24px;">
        <?php if ($picPath): ?>
          <img src="<?= htmlspecialchars($picPath) ?>"
               id="profilePreview"
               style="width:90px; height:90px; object-fit:cover; border-radius:50%;
                      border:4px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <?php else: ?>
          <div id="profileIcon"
               style="width:90px; height:90px; background:linear-gradient(135deg, #3b82f6, #8b5cf6);
                      border-radius:50%; border:4px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,0.1);
                      display:flex; align-items:center; justify-content:center;
                      font-size:36px; color:#fff; margin:0 auto;">
            <i class="bi bi-person"></i>
          </div>
        <?php endif; ?>

        <h5 class="fw-bold mt-3 mb-1"><?= htmlspecialchars($user['name']) ?></h5>
        <p style="font-size:13px; color:#64748b; margin-bottom:8px;">
          <?= htmlspecialchars($user['email']) ?>
        </p>
        <span style="background:linear-gradient(135deg, #3b82f6, #8b5cf6); color:#fff;
                     font-size:12px; padding:4px 14px; border-radius:20px; font-weight:600;">
          Venue Manager
        </span>
      </div>

      <hr style="margin:0; border-color:#f1f5f9;">

      <!-- Info -->
      <div style="padding:16px 20px;">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="background:#eff6ff; width:36px; height:36px; border-radius:8px;
                      display:flex; align-items:center; justify-content:center; color:#3b82f6; font-size:15px;">
            <i class="bi bi-telephone"></i>
          </div>
          <div>
            <p style="font-size:11px; color:#94a3b8; margin:0;">Phone</p>
            <p style="font-size:13px; font-weight:600; color:#1e293b; margin:0;">
              <?= htmlspecialchars($user['phone'] ?? 'Not set') ?>
            </p>
          </div>
        </div>
        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="background:#ecfdf5; width:36px; height:36px; border-radius:8px;
                      display:flex; align-items:center; justify-content:center; color:#10b981; font-size:15px;">
            <i class="bi bi-calendar3"></i>
          </div>
          <div>
            <p style="font-size:11px; color:#94a3b8; margin:0;">Member Since</p>
            <p style="font-size:13px; font-weight:600; color:#1e293b; margin:0;">
              <?= date('M Y', strtotime($user['created_at'])) ?>
            </p>
          </div>
        </div>
        <div class="d-flex align-items-center gap-3">
          <div style="background:#f5f3ff; width:36px; height:36px; border-radius:8px;
                      display:flex; align-items:center; justify-content:center; color:#8b5cf6; font-size:15px;">
            <i class="bi bi-shield-check"></i>
          </div>
          <div>
            <p style="font-size:11px; color:#94a3b8; margin:0;">Account Status</p>
            <p style="font-size:13px; font-weight:600; color:#10b981; margin:0;">
              ● Active
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Right Form -->
  <div class="col-md-8">
    <form method="POST" enctype="multipart/form-data">

      <!-- Personal Info -->
      <div class="rounded-3 overflow-hidden mb-4" style="background:#fff; border:1px solid #e2e8f0;">
        <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9; background:#fafafa;">
          <h6 class="fw-semibold mb-0" style="font-size:14px;">
            <i class="bi bi-person me-2" style="color:#3b82f6;"></i>Personal Information
          </h6>
        </div>
        <div class="p-4">

          <!-- Photo Upload -->
          <div class="d-flex align-items-center gap-4 mb-4 p-3 rounded-2"
               style="background:#f8fafc; border:1px dashed #e2e8f0;">
            <?php if ($picPath): ?>
              <img src="<?= htmlspecialchars($picPath) ?>" id="previewThumb"
                   style="width:56px; height:56px; border-radius:50%; object-fit:cover; border:2px solid #e2e8f0;">
            <?php else: ?>
              <div id="previewThumb"
                   style="width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg,#3b82f6,#8b5cf6);
                          display:flex; align-items:center; justify-content:center; color:#fff; font-size:22px;">
                <i class="bi bi-person"></i>
              </div>
            <?php endif; ?>
            <div>
              <label class="btn btn-sm" style="background:#eff6ff; color:#3b82f6; border:1px solid #bfdbfe; cursor:pointer; margin:0;">
                <i class="bi bi-camera me-1"></i> Change Photo
                <input type="file" name="profile_pic" accept="image/*"
                       style="display:none;" onchange="previewPic(this)">
              </label>
              <p class="mb-0 mt-1" style="font-size:11px; color:#94a3b8;">JPG, PNG, WEBP — Max 2MB</p>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" style="font-size:13px; font-weight:600;">
                Full Name <span class="text-danger">*</span>
              </label>
              <input type="text" name="name" class="form-control"
                     value="<?= htmlspecialchars($user['name']) ?>"
                     style="font-size:13px;" required>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:13px; font-weight:600;">Phone Number</label>
              <input type="text" name="phone" class="form-control"
                     value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                     placeholder="e.g. 01711000000" style="font-size:13px;">
            </div>
            <div class="col-12">
              <label class="form-label" style="font-size:13px; font-weight:600;">Email Address</label>
              <input type="email" class="form-control"
                     value="<?= htmlspecialchars($user['email']) ?>"
                     style="font-size:13px; background:#f8fafc;" disabled>
              <small style="font-size:11px; color:#94a3b8;">
                <i class="bi bi-lock me-1"></i>Email cannot be changed
              </small>
            </div>
          </div>
        </div>
      </div>

      <!-- Password -->
      <div class="rounded-3 overflow-hidden mb-4" style="background:#fff; border:1px solid #e2e8f0;">
        <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9; background:#fafafa;">
          <h6 class="fw-semibold mb-0" style="font-size:14px;">
            <i class="bi bi-shield-lock me-2" style="color:#8b5cf6;"></i>Change Password
            <small class="text-muted fw-normal ms-1">(leave blank to keep current)</small>
          </h6>
        </div>
        <div class="p-4">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" style="font-size:13px; font-weight:600;">Current Password</label>
              <input type="password" name="current_password" class="form-control"
                     placeholder="Enter your current password"
                     autocomplete="current-password" style="font-size:13px;">
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:13px; font-weight:600;">New Password</label>
              <input type="password" name="new_password" class="form-control"
                     placeholder="Minimum 6 characters"
                     autocomplete="new-password" style="font-size:13px;">
            </div>
            <div class="col-md-6">
              <label class="form-label" style="font-size:13px; font-weight:600;">Confirm New Password</label>
              <input type="password" name="confirm_password" class="form-control"
                     placeholder="Re-enter new password"
                     autocomplete="new-password" style="font-size:13px;">
            </div>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="d-flex justify-content-end gap-2">
        <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bi bi-floppy me-2"></i> Save Changes
        </button>
      </div>

    </form>
  </div>

</div>

<script>
// Auto hide alerts
setTimeout(function() {
    const alert = document.getElementById('alertMsg');
    if (alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }
}, 4000);

function previewPic(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Update left card preview
            let preview = document.getElementById('profilePreview');
            const icon  = document.getElementById('profileIcon');
            if (!preview && icon) {
                icon.outerHTML = '<img id="profilePreview" style="width:90px; height:90px; object-fit:cover; border-radius:50%; border:4px solid #fff; box-shadow:0 4px 12px rgba(0,0,0,0.1);">';
                preview = document.getElementById('profilePreview');
            }
            if (preview) preview.src = e.target.result;

            // Update thumb
            const thumb = document.getElementById('previewThumb');
            if (thumb && thumb.tagName === 'IMG') {
                thumb.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../shared/footer.php'; ?>