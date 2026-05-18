<?php
$pageTitle  = 'My Profile';
$activePage = 'profile';
include __DIR__ . '/../layouts/organiser-header.php';
?>
<style>
/* ── Page header ── */
.pr-title { font-size:1.3rem; font-weight:900; color:#0f172a; letter-spacing:-.3px; }
.pr-sub   { font-size:.82rem; color:#94a3b8; margin-top:.15rem; }
[data-bs-theme="dark"] .pr-title { color:#f1f5f9; }

/* ── Form sections ── */
.form-section {
  background:#fff; border-radius:18px;
  box-shadow:0 4px 20px rgba(0,0,0,.07),0 1px 3px rgba(0,0,0,.04);
  border:1px solid rgba(0,0,0,.05);
  margin-bottom:1.1rem; overflow:hidden;
}
.fs-header {
  display:flex; align-items:center; gap:.6rem;
  padding:.9rem 1.25rem; border-bottom:1px solid #f1f5f9;
  background:linear-gradient(180deg,#fafcff,#fff);
}
.fs-icon {
  width:32px; height:32px; border-radius:9px;
  display:flex; align-items:center; justify-content:center;
  font-size:.9rem; flex-shrink:0;
}
.fs-title { font-size:.8rem; font-weight:700; text-transform:uppercase;
  letter-spacing:.6px; color:#64748b; }
.fs-body { padding:1.25rem; }

/* ── Avatar ── */
.avatar-wrap {
  position:relative; width:82px; height:82px; flex-shrink:0;
}
.avatar-img {
  width:82px; height:82px; border-radius:50%; object-fit:cover;
  border:3px solid #fff;
  box-shadow:0 0 0 3px var(--g300), 0 4px 16px rgba(15,110,86,.2);
}
.avatar-initials {
  width:82px; height:82px; border-radius:50%;
  background:linear-gradient(135deg,var(--g700),var(--g300));
  display:flex; align-items:center; justify-content:center;
  font-size:2rem; font-weight:900; color:#fff;
  box-shadow:0 0 0 3px #fff, 0 4px 16px rgba(15,110,86,.25);
}
.avatar-edit-btn {
  position:absolute; bottom:0; right:0;
  width:26px; height:26px; border-radius:50%;
  background:#0F6E56; border:2px solid #fff;
  display:flex; align-items:center; justify-content:center;
  font-size:.65rem; color:#fff; cursor:pointer;
}
.user-badge {
  display:inline-flex; align-items:center; gap:.3rem;
  padding:3px 10px; border-radius:99px; font-size:.73rem;
  font-weight:700; margin-top:.35rem;
}
.badge-approved { background:#ECFDF5; color:#065f46; }
.badge-pending  { background:#FFFBEB; color:#92400e; }

/* ── Form controls ── */
.form-label {
  font-size:.79rem; font-weight:700; color:#374151;
  margin-bottom:.35rem; display:block;
}
.form-control, .form-select {
  border:1.5px solid #e2e8f0 !important;
  border-radius:10px !important;
  font-size:.9rem !important;
  padding:.65rem .9rem !important;
  transition:all .2s !important;
  background:#fff !important;
  color:#374151 !important;
}
.form-control:focus, .form-select:focus {
  border-color:#0F6E56 !important;
  box-shadow:0 0 0 3px rgba(15,110,86,.1) !important;
  background:#fff !important;
}
textarea.form-control { resize:vertical; min-height:90px; }

/* ── Logo preview ── */
.logo-preview-wrap {
  display:flex; align-items:center; gap:.75rem;
  padding:.75rem; background:#f8fffe;
  border:1.5px solid #E1F5EE; border-radius:12px;
  margin-bottom:.75rem;
}
.logo-preview-img {
  width:48px; height:48px; border-radius:10px;
  object-fit:cover; border:1px solid #e2e8f0;
}
.logo-preview-info { font-size:.78rem; color:#64748b; }

/* ── Password strength ── */
.strength-track {
  height:5px; background:#f1f5f9; border-radius:99px;
  overflow:hidden; margin-top:.5rem;
}
.strength-fill {
  height:100%; border-radius:99px; transition:all .3s; width:0%;
}
.strength-label { font-size:.72rem; margin-top:.3rem; color:#94a3b8; }

/* ── Save card ── */
.save-card {
  background:#fff; border-radius:18px;
  box-shadow:0 4px 20px rgba(0,0,0,.07);
  border:1px solid rgba(0,0,0,.05);
  overflow:hidden; position:sticky; top:75px;
}
.save-card-hdr {
  padding:.9rem 1.25rem; border-bottom:1px solid #f1f5f9;
  background:linear-gradient(135deg,#042C20,#0F6E56);
}
.save-card-title { font-size:.85rem; font-weight:700; color:#fff; }
.save-card-body  { padding:1.25rem; }
.btn-save {
  width:100%; padding:.8rem; border:none; border-radius:12px;
  background:linear-gradient(135deg,#0F6E56,#1a8a6e);
  color:#fff; font-size:.92rem; font-weight:700;
  cursor:pointer; font-family:'Inter',sans-serif;
  box-shadow:0 4px 16px rgba(15,110,86,.3);
  transition:all .2s; display:flex; align-items:center;
  justify-content:center; gap:.5rem;
}
.btn-save:hover {
  transform:translateY(-2px);
  box-shadow:0 8px 24px rgba(15,110,86,.4);
}
.info-box {
  background:#f0fdf4; border:1px solid #bbf7d0;
  border-radius:10px; padding:.8rem .9rem;
  font-size:.8rem; color:#166534; margin-bottom:1rem;
  display:flex; align-items:flex-start; gap:.5rem;
}

/* ── Dark mode ── */
[data-bs-theme="dark"] .form-section { background:#1a2030; border-color:rgba(255,255,255,.06); }
[data-bs-theme="dark"] .fs-header { background:#1a2030; border-color:#1e2a3a; }
[data-bs-theme="dark"] .fs-title { color:#94a3b8; }
[data-bs-theme="dark"] .form-label { color:#d1d5db; }
[data-bs-theme="dark"] .form-control { background:#1e2a3a !important; border-color:#2d3748 !important; color:#e2e8f0 !important; }
[data-bs-theme="dark"] .save-card { background:#1a2030; border-color:rgba(255,255,255,.06); }
[data-bs-theme="dark"] .save-card-hdr { border-color:#1e2a3a; }
[data-bs-theme="dark"] .info-box { background:#0a2a1e; border-color:#166534; color:#86efac; }
[data-bs-theme="dark"] .logo-preview-wrap { background:#1e2a3a; border-color:#2d3748; }
[data-bs-theme="dark"] .strength-track { background:#1e2a3a; }
</style>

<!-- Page Header -->
<div style="margin-bottom:1.5rem;">
  <div class="pr-title">My Profile</div>
  <div class="pr-sub">Manage your personal and organisation information</div>
</div>

<!-- Flash messages -->
<?php if (!empty($success)): ?>
  <div style="display:flex;align-items:center;gap:.6rem;padding:.8rem 1rem;
              background:#ECFDF5;border:1px solid #86efac;color:#065f46;
              border-radius:12px;font-size:.87rem;font-weight:500;margin-bottom:1rem;">
    <i class="bi bi-check-circle-fill"></i><?= htmlspecialchars($success) ?>
  </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <div style="display:flex;align-items:center;gap:.6rem;padding:.8rem 1rem;
              background:#FEF2F2;border:1px solid #fecaca;color:#991b1b;
              border-radius:12px;font-size:.87rem;font-weight:500;margin-bottom:1rem;">
    <i class="bi bi-exclamation-circle-fill"></i><?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<form method="POST" action="/WebtechProject/public/organiser/profile/update"
      enctype="multipart/form-data">
<div class="row g-3">
  <div class="col-lg-8">

    <!-- ── Personal Information ─────────────────────── -->
    <div class="form-section">
      <div class="fs-header">
        <div class="fs-icon" style="background:#E1F5EE;color:#0F6E56;">
          <i class="bi bi-person-circle"></i>
        </div>
        <span class="fs-title">Personal Information</span>
      </div>
      <div class="fs-body">
        <!-- Avatar + Name -->
        <div style="display:flex;align-items:center;gap:1.1rem;margin-bottom:1.25rem;
                    padding-bottom:1.25rem;border-bottom:1px solid #f1f5f9;">
          <div class="avatar-wrap">
            <?php if (!empty($profile['org_logo_path'])): ?>
              <img src="/WebtechProject/public/<?= htmlspecialchars($profile['org_logo_path']) ?>"
                   class="avatar-img" alt="Logo"/>
            <?php else: ?>
              <div class="avatar-initials">
                <?= strtoupper(substr($user['name'] ?? 'O', 0, 1)) ?>
              </div>
            <?php endif; ?>
            <div class="avatar-edit-btn" title="Change logo below">
              <i class="bi bi-camera-fill"></i>
            </div>
          </div>
          <div>
            <div style="font-size:1.05rem;font-weight:800;color:#0f172a;letter-spacing:-.2px;">
              <?= htmlspecialchars($user['name'] ?? '') ?>
            </div>
            <div style="font-size:.8rem;color:#94a3b8;margin-top:.15rem;">
              <?= htmlspecialchars($user['email'] ?? '') ?>
            </div>
            <?php
              $st = $profile['status'] ?? 'pending';
              $bc = $st === 'approved' ? 'badge-approved' : 'badge-pending';
            ?>
            <span class="user-badge <?= $bc ?>">
              <i class="bi bi-<?= $st==='approved'?'patch-check-fill':'clock' ?>"></i>
              <?= ucfirst($st) ?>
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
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone" class="form-control"
                   placeholder="01700000000"
                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>"/>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Organisation ────────────────────────────── -->
    <div class="form-section">
      <div class="fs-header">
        <div class="fs-icon" style="background:#EFF6FF;color:#2563eb;">
          <i class="bi bi-building-fill"></i>
        </div>
        <span class="fs-title">Organisation</span>
      </div>
      <div class="fs-body">
        <div class="mb-3">
          <label class="form-label">Organisation Name <span style="color:#ef4444;">*</span></label>
          <input type="text" name="org_name" class="form-control" required
                 value="<?= htmlspecialchars($profile['org_name'] ?? '') ?>"/>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="org_description" class="form-control"
                    placeholder="Describe your organisation..."
                    ><?= htmlspecialchars($profile['org_description'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Website</label>
          <div style="position:relative;">
            <input type="url" name="website" class="form-control"
                   placeholder="https://yourwebsite.com"
                   style="padding-left:2.5rem;"
                   value="<?= htmlspecialchars($profile['website'] ?? '') ?>"/>
            <i class="bi bi-globe" style="position:absolute;left:.85rem;top:50%;
               transform:translateY(-50%);color:#94a3b8;font-size:.9rem;"></i>
          </div>
        </div>
        <div>
          <label class="form-label">Organisation Logo</label>
          <?php if (!empty($profile['org_logo_path'])): ?>
            <div class="logo-preview-wrap">
              <img src="/WebtechProject/public/<?= htmlspecialchars($profile['org_logo_path']) ?>"
                   class="logo-preview-img" alt="Current logo"/>
              <div>
                <div class="logo-preview-info" style="font-weight:600;color:#374151;">
                  Current logo
                </div>
                <div class="logo-preview-info">Upload new file to replace</div>
              </div>
            </div>
          <?php endif; ?>
          <input type="file" name="logo" class="form-control" accept="image/*"/>
          <div style="font-size:.73rem;color:#94a3b8;margin-top:.4rem;">
            <i class="bi bi-info-circle me-1"></i>JPG, PNG or WebP — max 2MB
          </div>
        </div>
      </div>
    </div>

    <!-- ── Change Password ─────────────────────────── -->
    <div class="form-section">
      <div class="fs-header">
        <div class="fs-icon" style="background:#FFF7ED;color:#d97706;">
          <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div>
          <span class="fs-title">Change Password</span>
          <div style="font-size:.72rem;color:#94a3b8;font-weight:400;
                      text-transform:none;letter-spacing:0;margin-top:.1rem;">
            Leave blank to keep your current password
          </div>
        </div>
      </div>
      <div class="fs-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" id="newPassword"
                   class="form-control" placeholder="Min 8 characters"
                   oninput="checkStrength(this.value)"/>
            <div class="strength-track">
              <div class="strength-fill" id="strengthBar"></div>
            </div>
            <div class="strength-label" id="strengthText"></div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control"
                   placeholder="Repeat new password"/>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- ── Right: Save Card ──────────────────────────── -->
  <div class="col-lg-4">
    <div class="save-card">
      <div class="save-card-hdr">
        <div class="save-card-title">
          <i class="bi bi-floppy me-2"></i>Save Changes
        </div>
      </div>
      <div class="save-card-body">
        <div class="info-box">
          <i class="bi bi-info-circle-fill" style="flex-shrink:0;margin-top:.05rem;"></i>
          <span>Your profile is visible to attendees who book your events.</span>
        </div>
        <button type="submit" class="btn-save">
          <i class="bi bi-floppy-fill"></i>Save Changes
        </button>

        <!-- Profile completeness -->
        <?php
          $fields = [
            'Name'       => !empty($user['name']),
            'Phone'      => !empty($user['phone']),
            'Org name'   => !empty($profile['org_name']),
            'Description'=> !empty($profile['org_description']),
            'Website'    => !empty($profile['website']),
            'Logo'       => !empty($profile['org_logo_path']),
          ];
          $done  = count(array_filter($fields));
          $total = count($fields);
          $pct   = round($done/$total*100);
        ?>
        <div style="margin-top:1.25rem;padding-top:1.1rem;border-top:1px solid #f1f5f9;">
          <div style="display:flex;justify-content:space-between;align-items:center;
                      margin-bottom:.5rem;">
            <span style="font-size:.78rem;font-weight:700;color:#374151;">
              Profile Completeness
            </span>
            <span style="font-size:.78rem;font-weight:800;color:<?= $pct>=80?'#0F6E56':'#d97706' ?>;">
              <?= $pct ?>%
            </span>
          </div>
          <div style="height:6px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
            <div style="height:100%;width:<?= $pct ?>%;border-radius:99px;
                        background:linear-gradient(90deg,<?= $pct>=80?'#0F6E56,#5DCAA5':'#d97706,#f59e0b' ?>);
                        transition:width .6s ease;"></div>
          </div>
          <div style="margin-top:.75rem;">
            <?php foreach($fields as $label => $complete): ?>
              <div style="display:flex;align-items:center;gap:.5rem;
                          margin-bottom:.3rem;font-size:.77rem;">
                <i class="bi bi-<?= $complete?'check-circle-fill':'circle' ?>"
                   style="color:<?= $complete?'#0F6E56':'#d1d5db' ?>;font-size:.8rem;"></i>
                <span style="color:<?= $complete?'#374151':'#94a3b8' ?>;">
                  <?= $label ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>
</form>

<script>
function checkStrength(val) {
  const bar  = document.getElementById('strengthBar');
  const text = document.getElementById('strengthText');
  if (!val) { bar.style.width='0%'; text.textContent=''; return; }
  let score = 0;
  if (val.length >= 8)          score++;
  if (/[A-Z]/.test(val))        score++;
  if (/[0-9]/.test(val))        score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;
  const levels = [
    { w:'25%', c:'#ef4444', t:'Weak — add uppercase and numbers' },
    { w:'50%', c:'#d97706', t:'Fair — add special characters' },
    { w:'75%', c:'#3b82f6', t:'Good — almost there!' },
    { w:'100%', c:'#0F6E56', t:'Strong password' }
  ];
  const l = levels[Math.max(score-1, 0)];
  bar.style.width      = l.w;
  bar.style.background = l.c;
  text.style.color     = l.c;
  text.textContent     = l.t;
}
</script>

<?php include __DIR__ . '/../layouts/organiser-footer.php'; ?>