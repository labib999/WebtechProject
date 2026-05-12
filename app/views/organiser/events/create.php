<?php
$pageTitle  = 'Create New Event';
$activePage = 'events';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .form-section {
    background:#fff; border-radius:14px; padding:1.5rem;
    box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);
    border:1px solid rgba(0,0,0,0.05); margin-bottom:1.25rem;
  }
  .form-section-title {
    font-size:.82rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.6px; color:#9ca3af; margin-bottom:1.1rem;
    display:flex; align-items:center; gap:.5rem;
  }
  .form-label { font-size:.8rem; font-weight:700; color:#374151; letter-spacing:.3px; margin-bottom:.4rem; }
  .form-control, .form-select {
    border-color:#e5e7eb; font-size:.9rem;
    transition:border-color .2s, box-shadow .2s;
  }
  .form-control:focus, .form-select:focus {
    border-color:#0F6E56; box-shadow:0 0 0 3px rgba(15,110,86,.12);
  }
  textarea.form-control { resize:vertical; min-height:100px; }

  .venue-toggle { display:flex; gap:.5rem; margin-bottom:1rem; }
  .vtog-btn {
    flex:1; padding:.55rem; border:1.5px solid #e5e7eb; border-radius:9px;
    background:none; font-size:.82rem; font-weight:600; cursor:pointer;
    transition:all .15s; color:#6b7280;
  }
  .vtog-btn.active { border-color:#0F6E56; background:#E1F5EE; color:#0F6E56; }

  .banner-drop {
    border:2px dashed #e5e7eb; border-radius:12px;
    padding:2rem; text-align:center; cursor:pointer;
    transition:all .2s; position:relative;
  }
  .banner-drop:hover { border-color:#0F6E56; background:#f8fffd; }
  .banner-drop.has-image { border-style:solid; border-color:#0F6E56; padding:0; overflow:hidden; }
  .banner-drop input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; }
  .banner-preview { width:100%; height:180px; object-fit:cover; border-radius:10px; display:none; }

  .action-card { background:#fff; border-radius:14px; padding:1.25rem;
    box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 16px rgba(0,0,0,0.04);
    border:1px solid rgba(0,0,0,0.05); position:sticky; top:75px; }
  .action-card h6 { font-size:.82rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.5px; margin-bottom:1rem; }
  .btn-draft   { width:100%; padding:.65rem; border-radius:10px; border:1.5px solid #e5e7eb;
    background:#fff; font-size:.88rem; font-weight:600; color:#374151; cursor:pointer; margin-bottom:.5rem; transition:all .15s; }
  .btn-draft:hover { background:#f3f4f6; }
  .btn-publish { width:100%; padding:.65rem; border-radius:10px; border:none;
    background:#0F6E56; color:#fff; font-size:.88rem; font-weight:600;
    cursor:pointer; box-shadow:0 4px 14px rgba(15,110,86,.3); transition:all .15s; }
  .btn-publish:hover { background:#063D30; }

  [data-bs-theme="dark"] .form-section,
  [data-bs-theme="dark"] .action-card { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .form-control,
  [data-bs-theme="dark"] .form-select  { background:#253245; border-color:#374151; color:#f3f4f6; }
  [data-bs-theme="dark"] .form-label   { color:#d1d5db; }
  [data-bs-theme="dark"] .form-section-title { color:#6b7280; }
  [data-bs-theme="dark"] .btn-draft    { background:#253245; border-color:#374151; color:#d1d5db; }
  [data-bs-theme="dark"] .vtog-btn     { border-color:#374151; color:#9ca3af; background:#253245; }
  [data-bs-theme="dark"] .vtog-btn.active { border-color:#0F6E56; background:#1a3a2e; color:#5DCAA5; }
  [data-bs-theme="dark"] .banner-drop  { border-color:#374151; background:#253245; }
</style>

<!-- Breadcrumb -->
<nav style="font-size:.8rem; color:#9ca3af; margin-bottom:1.25rem;">
  <a href="/WebtechProject/public/organiser/events" style="color:#0F6E56; text-decoration:none;">My Events</a>
  <i class="bi bi-chevron-right mx-1" style="font-size:.7rem;"></i>
  <span>Create New Event</span>
</nav>

<?php if (!empty($error)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#FEF2F2;border:1px solid #fecaca;color:#991b1b;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-exclamation-circle-fill"></i><span><?= htmlspecialchars($error) ?></span>
  </div>
<?php endif; ?>

<form method="POST" action="/WebtechProject/public/organiser/events/store"
      enctype="multipart/form-data" id="eventForm">

  <div class="row g-3">

    <!-- Left: main form -->
    <div class="col-lg-8">

      <!-- Basic info -->
      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-info-circle"></i> Basic Information</div>

        <div class="mb-3">
          <label class="form-label">Event Title <span style="color:#ef4444;">*</span></label>
          <input type="text" name="title" class="form-control"
                 placeholder="e.g. Tech Innovators Summit 2026" required
                 value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"/>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control"
                    placeholder="Tell attendees what your event is about..."
                    ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div>
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select">
            <option value="">— Select category —</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>"
                <?= (($_POST['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Date & time -->
      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-calendar-event"></i> Date &amp; Time</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Start Date &amp; Time <span style="color:#ef4444;">*</span></label>
            <input type="datetime-local" name="event_datetime" class="form-control" required
                   value="<?= htmlspecialchars($_POST['event_datetime'] ?? '') ?>"/>
          </div>
          <div class="col-md-6">
            <label class="form-label">End Date &amp; Time <span style="color:#ef4444;">*</span></label>
            <input type="datetime-local" name="end_datetime" class="form-control" required
                   value="<?= htmlspecialchars($_POST['end_datetime'] ?? '') ?>"/>
          </div>
        </div>
      </div>

      <!-- Venue -->
      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-geo-alt"></i> Venue</div>
        <input type="hidden" name="venue_option" id="venueOption" value="custom"/>

        <div class="venue-toggle">
          <button type="button" class="vtog-btn active" id="btnCustom"
                  onclick="setVenue('custom')">
            <i class="bi bi-pencil-square me-1"></i> Custom Address
          </button>
          <button type="button" class="vtog-btn" id="btnBooked"
                  onclick="setVenue('booked')"
                  <?= empty($venueBookings) ? 'disabled title="No approved venue bookings"' : '' ?>>
            <i class="bi bi-building me-1"></i>
            Booked Venue <?= !empty($venueBookings) ? '('.count($venueBookings).')' : '(none)' ?>
          </button>
        </div>

        <div id="customVenueDiv">
          <label class="form-label">Venue Name / Address</label>
          <input type="text" name="venue_name_override" class="form-control"
                 placeholder="e.g. Chittagong Convention Center, Agrabad"
                 value="<?= htmlspecialchars($_POST['venue_name_override'] ?? '') ?>"/>
        </div>

        <div id="bookedVenueDiv" style="display:none;">
          <label class="form-label">Select Approved Venue</label>
          <select name="venue_id" class="form-select">
            <option value="">— Select venue —</option>
            <?php foreach ($venueBookings as $vb): ?>
              <option value="<?= $vb['venue_id'] ?>">
                <?= htmlspecialchars($vb['venue_name']) ?> — <?= htmlspecialchars($vb['city']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Banner -->
      <div class="form-section">
        <div class="form-section-title"><i class="bi bi-image"></i> Banner Image</div>
        <p style="font-size:.78rem; color:#9ca3af; margin-bottom:.75rem;">
          JPG, PNG or WebP — max 2 MB. Recommended: 1200 × 400 px.
        </p>
        <div class="banner-drop" id="bannerDrop">
          <input type="file" name="banner" accept="image/*" id="bannerInput"
                 onchange="previewBanner(this)"/>
          <img id="bannerPreview" class="banner-preview" alt="Banner preview"/>
          <div id="bannerPlaceholder">
            <i class="bi bi-cloud-arrow-up" style="font-size:2rem; color:#d1d5db; display:block; margin-bottom:.5rem;"></i>
            <p style="font-size:.85rem; color:#9ca3af; margin:0;">Click to upload banner image</p>
          </div>
        </div>
      </div>

    </div><!-- /col-lg-8 -->

    <!-- Right: actions -->
    <div class="col-lg-4">
      <div class="action-card">
        <h6><i class="bi bi-send me-1"></i> Publish Options</h6>

        <div style="background:#f8f9fa; border-radius:9px; padding:.85rem; margin-bottom:1rem; font-size:.82rem; color:#6b7280;">
          <i class="bi bi-info-circle me-1"></i>
          You can save as draft first, add ticket tiers, then publish when ready.
        </div>

        <button type="submit" name="action" value="draft" class="btn-draft">
          <i class="bi bi-floppy me-1"></i> Save as Draft
        </button>
        <button type="submit" name="action" value="publish" class="btn-publish"
                onclick="return confirm('Publish this event? It will be visible to attendees.')">
          <i class="bi bi-send-fill me-1"></i> Publish Now
        </button>

        <hr style="border-color:#f3f4f6; margin:1rem 0;"/>

        <a href="/WebtechProject/public/organiser/events"
           style="display:flex; align-items:center; justify-content:center; gap:.4rem;
                  font-size:.82rem; color:#9ca3af; text-decoration:none; padding:.4rem;">
          <i class="bi bi-arrow-left"></i> Back to My Events
        </a>
      </div>
    </div>

  </div>
</form>

<script>
  function setVenue(type) {
    document.getElementById('venueOption').value = type;
    document.getElementById('customVenueDiv').style.display = type === 'custom' ? '' : 'none';
    document.getElementById('bookedVenueDiv').style.display = type === 'booked' ? '' : 'none';
    document.getElementById('btnCustom').className = 'vtog-btn' + (type==='custom'?' active':'');
    document.getElementById('btnBooked').className = 'vtog-btn' + (type==='booked'?' active':'');
  }

  function previewBanner(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
      const img  = document.getElementById('bannerPreview');
      const ph   = document.getElementById('bannerPlaceholder');
      const drop = document.getElementById('bannerDrop');
      img.src = e.target.result;
      img.style.display = 'block';
      ph.style.display  = 'none';
      drop.classList.add('has-image');
    };
    reader.readAsDataURL(file);
  }
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>