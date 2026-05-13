<?php
$pageTitle  = 'My Profile';
$activePage = 'profile';
include __DIR__ . '/../layouts/organiser-header.php';
?>
<style>
  .form-section { background:#fff; border-radius:14px; padding:1.5rem;
    box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 14px rgba(0,0,0,.04);
    border:1px solid rgba(0,0,0,.05); margin-bottom:1.25rem; }
  .form-section-title { font-size:.82rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.6px; color:#9ca3af; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
  .form-label { font-size:.8rem; font-weight:700; color:#374151; margin-bottom:.35rem; }
  .form-control { border-color:#e5e7eb; font-size:.9rem; }
  .form-control:focus { border-color:#0F6E56; box-shadow:0 0 0 3px rgba(15,110,86,.12); }
  textarea.form-control { resize:vertical; min-height:90px; }
  .btn-save { padding:.65rem 1.5rem; border-radius:10px; border:none; background:#0F6E56;
    color:#fff; font-size:.9rem; font-weight:600; cursor:pointer;
    box-shadow:0 4px 14px rgba(15,110,86,.25); transition:all .15s; }
  .btn-save:hover { background:#063D30; }
  .avatar-big { width:80px; height:80px; border-radius:50%; background:#0F6E56;
    display:flex; align-items:center; justify-content:center;
    font-size:2rem; font-weight:800; color:#fff; flex-shrink:0; }
  .status-badge { display:inline-block; padding:4px 12px; border-radius:20px;
    font-size:.78rem; font-weight:700; }
  [data-bs-theme="dark"] .form-section { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .form-control { background:#253245; border-color:#374151; color:#f3f4f6; }
  [data-bs-theme="dark"] .form-label   { color:#d1d5db; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0" style="font-size:1.05rem;">My Profile</h4>
</div>

<?php if (!empty($success)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#ECFDF5;border:1px solid #6ee7b7;color:#065f46;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-check-circle-fill"></i><span><?= htmlspecialchars($success) ?></span>
  </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#FEF2F2;border:1px solid #fecaca;color:#991b1b;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-exclamation-circle-fill"></i><span><?= htmlspecialchars($error) ?></span>
  </div>
<?php endif; ?>

<form method="POST" action="/WebtechProject/public/organiser/profile/update"
      enctype="multipart/form-data">
  <div class="row g-3">
    <div class="col-lg-8">

      <!-- Personal info -->
      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-person-circle"></i> Personal Information</div>
        <div class="d-flex align-items-center gap-3 mb-3">
          <?php if (!empty($profile['org_logo_path'])): ?>
            <img src="/WebtechProject/public/<?= htmlspecialchars($profile['org_logo_path']) ?>"
                 style="width:80px;height:80px;border-radius:50%;object-fit:cover;" alt="Logo"/>
          <?php else: ?>
            <div class="avatar-big"><?= strtoupper(substr($user['name'] ?? 'O', 0, 1)) ?></div>
          <?php endif; ?>
          <div>
            <div style="font-size:1rem;font-weight:700;"><?= htmlspecialchars($user['name'] ?? '') ?></div>
            <div style="font-size:.8rem;color:#9ca3af;"><?= htmlspecialchars($user['email'] ?? '') ?></div>
            <span class="status-badge mt-1"
                  style="<?= ($profile['status']??'')=='approved'?'background:#ECFDF5;color:#065f46;':'background:#FFFBEB;color:#92400e;' ?>">
              <?= ucfirst($profile['status'] ?? 'pending') ?>
            </span>
          </div>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" class="form-control" required
                   value="<?= htmlspecialchars($user['name'] ?? '') ?>"/>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="tel" name="phone" class="form-control"
                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>"/>
          </div>
        </div>
      </div>

      <!-- Organisation info -->
      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-building"></i> Organisation</div>
        <div class="mb-3">
          <label class="form-label">Organisation Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="org_name" class="form-control" required
                 value="<?= htmlspecialchars($profile['org_name'] ?? '') ?>"/>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="org_description" class="form-control"
                    ><?= htmlspecialchars($profile['org_description'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Website</label>
          <input type="url" name="website" class="form-control"
                 placeholder="https://yourwebsite.com"
                 value="<?= htmlspecialchars($profile['website'] ?? '') ?>"/>
        </div>
        <div>
          <label class="form-label">Organisation Logo</label>
          <?php if (!empty($profile['org_logo_path'])): ?>
            <div style="margin-bottom:.5rem;">
              <img src="/WebtechProject/public/<?= htmlspecialchars($profile['org_logo_path']) ?>"
                   style="height:50px;border-radius:8px;border:1px solid #e5e7eb;" alt="Logo"/>
            </div>
          <?php endif; ?>
          <input type="file" name="logo" class="form-control" accept="image/*"/>
          <div style="font-size:.75rem;color:#9ca3af;margin-top:.3rem;">JPG/PNG/WebP, max 2MB</div>
        </div>
      </div>

      <!-- Change password -->
      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-shield-lock"></i> Change Password
          <span style="font-weight:400;font-size:.75rem;text-transform:none;letter-spacing:0;">
            — leave blank to keep current password
          </span>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control"
                   placeholder="Min 8 characters"/>
          </div>
          <div class="col-md-6">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control"
                   placeholder="Repeat new password"/>
          </div>
        </div>
      </div>

    </div>

    <!-- Right: save card -->
    <div class="col-lg-4">
      <div class="form-section" style="position:sticky;top:75px;">
        <div class="form-section-title"><i class="bi bi-floppy"></i> Save Changes</div>
        <div style="background:#f8f9fa;border-radius:9px;padding:.85rem;margin-bottom:1rem;font-size:.82rem;color:#6b7280;">
          <i class="bi bi-info-circle me-1"></i>
          Your profile is visible to attendees who book your events.
        </div>
        <button type="submit" class="btn-save w-100">
          <i class="bi bi-floppy me-1"></i>Save Changes
        </button>
      </div>
    </div>
  </div>
</form>

<?php include __DIR__ . '/../layouts/organiser-footer.php'; ?>