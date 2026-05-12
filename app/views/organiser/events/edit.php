<?php
$pageTitle  = 'Edit Event';
$activePage = 'events';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .form-section { background:#fff; border-radius:14px; padding:1.5rem;
    box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);
    border:1px solid rgba(0,0,0,0.05); margin-bottom:1.25rem; }
  .form-section-title { font-size:.82rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.6px; color:#9ca3af; margin-bottom:1.1rem;
    display:flex; align-items:center; gap:.5rem; }
  .form-label { font-size:.8rem; font-weight:700; color:#374151; letter-spacing:.3px; margin-bottom:.4rem; }
  .form-control, .form-select { border-color:#e5e7eb; font-size:.9rem; transition:border-color .2s, box-shadow .2s; }
  .form-control:focus, .form-select:focus { border-color:#0F6E56; box-shadow:0 0 0 3px rgba(15,110,86,.12); }
  textarea.form-control { resize:vertical; min-height:100px; }
  .banner-drop { border:2px dashed #e5e7eb; border-radius:12px; padding:2rem; text-align:center; cursor:pointer; transition:all .2s; position:relative; }
  .banner-drop:hover { border-color:#0F6E56; background:#f8fffd; }
  .banner-drop input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; }
  .action-card { background:#fff; border-radius:14px; padding:1.25rem;
    box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);
    border:1px solid rgba(0,0,0,0.05); position:sticky; top:75px; }
  .btn-save { width:100%; padding:.65rem; border-radius:10px; border:none;
    background:#0F6E56; color:#fff; font-size:.88rem; font-weight:600;
    cursor:pointer; box-shadow:0 4px 14px rgba(15,110,86,.3); transition:all .15s; }
  .btn-save:hover { background:#063D30; }
  [data-bs-theme="dark"] .form-section,
  [data-bs-theme="dark"] .action-card { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .form-control,
  [data-bs-theme="dark"] .form-select { background:#253245; border-color:#374151; color:#f3f4f6; }
  [data-bs-theme="dark"] .form-label { color:#d1d5db; }
  [data-bs-theme="dark"] .form-section-title { color:#6b7280; }
  [data-bs-theme="dark"] .banner-drop { border-color:#374151; background:#253245; }
</style>

<!-- Breadcrumb -->
<nav style="font-size:.8rem; color:#9ca3af; margin-bottom:1.25rem;">
  <a href="/WebtechProject/public/organiser/events" style="color:#0F6E56; text-decoration:none;">My Events</a>
  <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i>
  <span>Edit</span>
</nav>

<?php if (!empty($error)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#FEF2F2;border:1px solid #fecaca;color:#991b1b;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-exclamation-circle-fill"></i>
    <span><?= htmlspecialchars($error) ?></span>
  </div>
<?php endif; ?>

<form method="POST" action="/WebtechProject/public/organiser/events/update"
      enctype="multipart/form-data">
  <input type="hidden" name="event_id" value="<?= $event['id'] ?>"/>

  <div class="row g-3">

    <!-- Left: form fields -->
    <div class="col-lg-8">

      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-info-circle"></i> Basic Information</div>
        <div class="mb-3">
          <label class="form-label">Event Title <span style="color:#ef4444;">*</span></label>
          <input type="text" name="title" class="form-control" required
                 value="<?= htmlspecialchars($event['title']) ?>"/>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
        </div>
        <div>
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select">
            <option value="">— Select category —</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"
                <?= $event['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-calendar-event"></i> Date &amp; Time</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Start Date &amp; Time <span style="color:#ef4444;">*</span></label>
            <input type="datetime-local" name="event_datetime" class="form-control" required
                   value="<?= date('Y-m-d\TH:i', strtotime($event['event_datetime'])) ?>"/>
          </div>
          <div class="col-md-6">
            <label class="form-label">End Date &amp; Time <span style="color:#ef4444;">*</span></label>
            <input type="datetime-local" name="end_datetime" class="form-control" required
                   value="<?= date('Y-m-d\TH:i', strtotime($event['end_datetime'])) ?>"/>
          </div>
        </div>
      </div>

      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-geo-alt"></i> Venue</div>
        <label class="form-label">Venue Name / Address</label>
        <input type="text" name="venue_name_override" class="form-control"
               placeholder="Venue name or address"
               value="<?= htmlspecialchars($event['venue_name_override'] ?? '') ?>"/>
      </div>

      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-image"></i> Banner Image</div>
        <?php if (!empty($event['banner_image_path'])): ?>
          <div class="mb-2">
            <img src="/WebtechProject/public/<?= htmlspecialchars($event['banner_image_path']) ?>"
                 style="width:100%;max-height:180px;object-fit:cover;border-radius:10px;" alt="Current banner"/>
            <p style="font-size:.75rem;color:#9ca3af;margin-top:.4rem;">
              <i class="bi bi-info-circle me-1"></i>Upload a new image below to replace the current banner.
            </p>
          </div>
        <?php endif; ?>
        <div class="banner-drop">
          <input type="file" name="banner" accept="image/*" onchange="previewBanner(this)"/>
          <img id="bannerPreview" style="width:100%;max-height:160px;object-fit:cover;border-radius:10px;display:none;" alt=""/>
          <div id="bannerPlaceholder">
            <i class="bi bi-cloud-arrow-up" style="font-size:2rem;color:#d1d5db;display:block;margin-bottom:.5rem;"></i>
            <p style="font-size:.85rem;color:#9ca3af;margin:0;">
              <?= empty($event['banner_image_path']) ? 'Click to upload banner' : 'Click to replace banner' ?>
            </p>
          </div>
        </div>
      </div>

    </div>

    <!-- Right: action card -->
    <div class="col-lg-4">
      <div class="action-card">
        <h6 style="font-size:.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:1rem;">
          <i class="bi bi-pencil me-1"></i> Save Changes
        </h6>

        <div style="background:#f8f9fa;border-radius:9px;padding:.85rem;margin-bottom:1rem;font-size:.82rem;color:#6b7280;">
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span>Current status</span>
            <span class="st-badge st-<?= $event['status'] ?>" style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;">
              <?= ucfirst($event['status']) ?>
            </span>
          </div>
        </div>

        <button type="submit" class="btn-save">
          <i class="bi bi-floppy me-1"></i> Save Changes
        </button>

        <hr style="border-color:#f3f4f6;margin:1rem 0;"/>

        <a href="/WebtechProject/public/organiser/events"
           style="display:flex;align-items:center;justify-content:center;gap:.4rem;
                  font-size:.82rem;color:#9ca3af;text-decoration:none;padding:.4rem;">
          <i class="bi bi-arrow-left"></i> Back to My Events
        </a>
      </div>
    </div>

  </div>
</form>

<script>
  function previewBanner(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.getElementById('bannerPreview');
      img.src = e.target.result;
      img.style.display = 'block';
      document.getElementById('bannerPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
  }
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>