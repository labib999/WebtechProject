<?php
$pageTitle  = 'Create New Event';
$activePage = 'events';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
.breadcrumb-ep { font-size:.8rem; color:#94a3b8; margin-bottom:1.25rem;
  display:flex; align-items:center; gap:.4rem; }
.breadcrumb-ep a { color:var(--g700); text-decoration:none; font-weight:600; }
.breadcrumb-ep a:hover { text-decoration:underline; }

.ce-title { font-size:1.3rem; font-weight:900; color:#0f172a; letter-spacing:-.3px; }
.ce-sub   { font-size:.82rem; color:#94a3b8; margin-top:.15rem; }
[data-bs-theme="dark"] .ce-title { color:#f1f5f9; }

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
}
textarea.form-control { resize:vertical; min-height:100px; }

.venue-toggle { display:flex; gap:.5rem; margin-bottom:1rem; }
.vtog-btn {
  flex:1; padding:.6rem .75rem; border:1.5px solid #e2e8f0;
  border-radius:10px; background:#fff; font-size:.82rem;
  font-weight:600; cursor:pointer; transition:all .18s;
  color:#64748b; display:flex; align-items:center;
  justify-content:center; gap:.4rem; font-family:'Inter',sans-serif;
}
.vtog-btn:hover:not(:disabled) { border-color:var(--g700); color:var(--g700); }
.vtog-btn.active {
  border-color:var(--g700); background:var(--g50); color:var(--g700);
  box-shadow:0 0 0 3px rgba(15,110,86,.08);
}
.vtog-btn:disabled { opacity:.45; cursor:not-allowed; }

.banner-drop {
  border:2px dashed #e2e8f0; border-radius:14px;
  padding:2.5rem 2rem; text-align:center; cursor:pointer;
  transition:all .2s; position:relative; background:#fafcff;
}
.banner-drop:hover { border-color:var(--g700); background:#f0fdf4; }
.banner-drop.has-image { border:2px solid var(--g300); padding:0; overflow:hidden; }
.banner-drop input[type=file] {
  position:absolute; inset:0; opacity:0; cursor:pointer; z-index:2;
}
.banner-preview { width:100%; height:200px; object-fit:cover; display:none; border-radius:12px; }
.banner-placeholder i { font-size:2.5rem; color:#d1d5db; display:block; margin-bottom:.75rem; }
.banner-placeholder p { font-size:.87rem; color:#94a3b8; margin:0; }
.banner-placeholder small { font-size:.75rem; color:#d1d5db; }

.action-card {
  background:#fff; border-radius:18px;
  box-shadow:0 4px 20px rgba(0,0,0,.07);
  border:1px solid rgba(0,0,0,.05);
  overflow:hidden; position:sticky; top:75px;
}
.action-card-hdr {
  padding:.9rem 1.25rem;
  background:linear-gradient(135deg,#042C20,#0F6E56);
}
.action-card-title { font-size:.85rem; font-weight:700; color:#fff; }
.action-card-body  { padding:1.25rem; }

.btn-draft {
  width:100%; padding:.72rem; border-radius:12px;
  border:1.5px solid #e2e8f0; background:#fff;
  font-size:.88rem; font-weight:700; color:#374151;
  cursor:pointer; transition:all .18s; margin-bottom:.6rem;
  font-family:'Inter',sans-serif;
  display:flex; align-items:center; justify-content:center; gap:.4rem;
}
.btn-draft:hover { border-color:var(--g700); color:var(--g700); background:var(--g50); }

.btn-create {
  width:100%; padding:.8rem; border:none; border-radius:12px;
  background:linear-gradient(135deg,#0F6E56,#1a8a6e);
  color:#fff; font-size:.92rem; font-weight:700;
  cursor:pointer; transition:all .2s; font-family:'Inter',sans-serif;
  box-shadow:0 4px 16px rgba(15,110,86,.3);
  display:flex; align-items:center; justify-content:center; gap:.5rem;
}
.btn-create:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(15,110,86,.4); }

.info-box {
  background:#f0fdf4; border:1px solid #bbf7d0;
  border-radius:10px; padding:.8rem .9rem;
  font-size:.79rem; color:#166534; margin-bottom:1rem;
  display:flex; align-items:flex-start; gap:.5rem; line-height:1.5;
}
.tip-item {
  display:flex; align-items:flex-start; gap:.5rem;
  font-size:.78rem; color:#64748b; margin-bottom:.5rem; line-height:1.5;
}
.tip-item i { color:var(--g700); flex-shrink:0; margin-top:.1rem; }

[data-bs-theme="dark"] .form-section,
[data-bs-theme="dark"] .action-card { background:#1a2030; border-color:rgba(255,255,255,.06); }
[data-bs-theme="dark"] .fs-header { background:#1a2030; border-color:#1e2a3a; }
[data-bs-theme="dark"] .fs-title  { color:#64748b; }
[data-bs-theme="dark"] .form-label { color:#d1d5db; }
[data-bs-theme="dark"] .form-control,
[data-bs-theme="dark"] .form-select { background:#1e2a3a !important; border-color:#2d3748 !important; color:#e2e8f0 !important; }
[data-bs-theme="dark"] .vtog-btn:not(.active) { background:#1e2a3a; border-color:#2d3748; color:#94a3b8; }
[data-bs-theme="dark"] .vtog-btn.active { background:#0a2a1e; border-color:var(--g700); }
[data-bs-theme="dark"] .banner-drop { border-color:#2d3748; background:#1e2a3a; }
[data-bs-theme="dark"] .btn-draft { background:#1e2a3a; border-color:#2d3748; color:#d1d5db; }
[data-bs-theme="dark"] .info-box { background:#0a2a1e; border-color:#166534; color:#86efac; }
[data-bs-theme="dark"] .tip-item { color:#94a3b8; }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb-ep">
  <a href="/WebtechProject/public/organiser/events">
    <i class="bi bi-calendar-event-fill me-1"></i>My Events
  </a>
  <i class="bi bi-chevron-right" style="font-size:.7rem;"></i>
  <span>Create New Event</span>
</div>

<!-- Page title -->
<div style="margin-bottom:1.5rem;">
  <div class="ce-title">Create New Event</div>
  <div class="ce-sub">Fill in the details below — you can save as draft and publish later</div>
</div>

<!-- Error -->
<?php if (!empty($error)): ?>
  <div style="display:flex;align-items:center;gap:.6rem;padding:.8rem 1rem;
              background:#FEF2F2;border:1px solid #fecaca;color:#991b1b;
              border-radius:12px;font-size:.87rem;font-weight:500;margin-bottom:1rem;">
    <i class="bi bi-exclamation-circle-fill"></i><?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<form method="POST" action="/WebtechProject/public/organiser/events/store"
      enctype="multipart/form-data" id="eventForm">

  <!-- Hidden fields required by EventModel -->
  <input type="hidden" name="venue_address" value="<?= htmlspecialchars($_POST['venue_address'] ?? '') ?>"/>
  <input type="hidden" name="venue_city"    value="<?= htmlspecialchars($_POST['venue_city']    ?? '') ?>"/>

  <div class="row g-3">

    <!-- ── Left: main form ─────────────────────────── -->
    <div class="col-lg-8">

      <!-- Basic Info -->
      <div class="form-section">
        <div class="fs-header">
          <div class="fs-icon" style="background:#E1F5EE;color:#0F6E56;">
            <i class="bi bi-info-circle-fill"></i>
          </div>
          <span class="fs-title">Basic Information</span>
        </div>
        <div class="fs-body">
          <div class="mb-3">
            <label class="form-label">
              Event Title <span style="color:#ef4444;">*</span>
            </label>
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
          <div class="row g-3">
            <div class="col-md-6">
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
            <div class="col-md-6">
              <label class="form-label">Total Capacity</label>
              <input type="number" name="max_capacity" class="form-control"
                     placeholder="e.g. 200" min="1"
                     value="<?= htmlspecialchars($_POST['max_capacity'] ?? '') ?>"/>
            </div>
          </div>
        </div>
      </div>

      <!-- Date & Time -->
      <div class="form-section">
        <div class="fs-header">
          <div class="fs-icon" style="background:#EFF6FF;color:#2563eb;">
            <i class="bi bi-calendar-event-fill"></i>
          </div>
          <span class="fs-title">Date &amp; Time</span>
        </div>
        <div class="fs-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">
                Start Date &amp; Time <span style="color:#ef4444;">*</span>
              </label>
              <input type="datetime-local" name="event_datetime" class="form-control"
                     required value="<?= htmlspecialchars($_POST['event_datetime'] ?? '') ?>"/>
            </div>
            <div class="col-md-6">
              <label class="form-label">
                End Date &amp; Time <span style="color:#ef4444;">*</span>
              </label>
              <input type="datetime-local" name="end_datetime" class="form-control"
                     required value="<?= htmlspecialchars($_POST['end_datetime'] ?? '') ?>"/>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:.5rem;margin-top:.75rem;
                      padding:.65rem .9rem;background:#f8f9fa;border-radius:10px;
                      font-size:.78rem;color:#64748b;">
            <i class="bi bi-clock" style="color:var(--g700);"></i>
            Timezone: Asia/Dhaka (BDT)
          </div>
        </div>
      </div>

      <!-- Venue -->
      <div class="form-section">
        <div class="fs-header">
          <div class="fs-icon" style="background:#FFF7ED;color:#d97706;">
            <i class="bi bi-geo-alt-fill"></i>
          </div>
          <span class="fs-title">Venue</span>
        </div>
        <div class="fs-body">
          <input type="hidden" name="venue_option" id="venueOption" value="custom"/>
          <div class="venue-toggle">
            <button type="button" class="vtog-btn active" id="btnCustom"
                    onclick="setVenue('custom')">
              <i class="bi bi-pencil-square"></i> Custom Address
            </button>
            <button type="button" class="vtog-btn" id="btnBooked"
                    onclick="setVenue('booked')"
                    <?= empty($venueBookings) ? 'disabled title="No approved venue bookings"' : '' ?>>
              <i class="bi bi-building"></i>
              Booked Venue
              <?= !empty($venueBookings) ? '('.count($venueBookings).')' : '(none)' ?>
            </button>
          </div>

          <div id="customVenueDiv">
            <label class="form-label">Venue Name / Address</label>
            <div style="position:relative;">
              <input type="text" name="venue_name_override" class="form-control"
                     placeholder="e.g. Chittagong Convention Center, Agrabad"
                     style="padding-left:2.5rem;"
                     value="<?= htmlspecialchars($_POST['venue_name_override'] ?? '') ?>"/>
              <i class="bi bi-pin-map-fill" style="position:absolute;left:.85rem;
                 top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.9rem;"></i>
            </div>
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
      </div>

      <!-- Banner -->
      <div class="form-section">
        <div class="fs-header">
          <div class="fs-icon" style="background:#F5F3FF;color:#7c3aed;">
            <i class="bi bi-image-fill"></i>
          </div>
          <span class="fs-title">Banner Image</span>
        </div>
        <div class="fs-body">
          <div class="banner-drop" id="bannerDrop">
            <input type="file" name="banner" accept="image/*" id="bannerInput"
                   onchange="previewBanner(this)"/>
            <img id="bannerPreview" class="banner-preview" alt="Banner preview"/>
            <div class="banner-placeholder" id="bannerPlaceholder">
              <i class="bi bi-cloud-arrow-up"></i>
              <p>Click to upload banner image</p>
              <small>JPG, PNG or WebP — max 2MB · Recommended 1200×400px</small>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- ── Right: action card ─────────────────────── -->
    <div class="col-lg-4">
      <div class="action-card">
        <div class="action-card-hdr">
          <div class="action-card-title">
            <i class="bi bi-send me-2"></i>Publish Options
          </div>
        </div>
        <div class="action-card-body">
          <div class="info-box">
            <i class="bi bi-info-circle-fill" style="flex-shrink:0;margin-top:.05rem;"></i>
            <span>Save as draft first, add ticket tiers, then publish when ready.</span>
          </div>

          <button type="submit" name="action" value="draft" class="btn-draft">
            <i class="bi bi-floppy"></i> Save as Draft
          </button>
          <button type="submit" name="action" value="publish" class="btn-create"
                  onclick="return confirm('Publish this event? It will be visible to attendees.')">
            <i class="bi bi-send-fill"></i> Publish Now
          </button>

          <!-- Tips -->
          <div style="margin-top:1.25rem;padding-top:1.1rem;border-top:1px solid #f1f5f9;">
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;
                        letter-spacing:.5px;color:#94a3b8;margin-bottom:.75rem;">
              Tips
            </div>
            <div class="tip-item">
              <i class="bi bi-check-circle-fill"></i>
              Save as Draft → Add Ticket Tiers → Then Publish
            </div>
            <div class="tip-item">
              <i class="bi bi-check-circle-fill"></i>
              A banner image makes your event look more professional
            </div>
            <div class="tip-item">
              <i class="bi bi-check-circle-fill"></i>
              Add multiple tiers — VIP, General, Early Bird
            </div>
            <div class="tip-item">
              <i class="bi bi-check-circle-fill"></i>
              Use Discount Codes to promote your event
            </div>
          </div>

          <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f1f5f9;">
            <a href="/WebtechProject/public/organiser/events"
               style="display:flex;align-items:center;justify-content:center;gap:.4rem;
                      font-size:.82rem;color:#94a3b8;text-decoration:none;padding:.4rem;
                      border-radius:8px;transition:all .15s;">
              <i class="bi bi-arrow-left"></i> Back to My Events
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>
</form>

<script>
function setVenue(type) {
  document.getElementById('venueOption').value = type;
  document.getElementById('customVenueDiv').style.display = type==='custom' ? '' : 'none';
  document.getElementById('bookedVenueDiv').style.display = type==='booked' ? '' : 'none';
  document.getElementById('btnCustom').className = 'vtog-btn'+(type==='custom'?' active':'');
  document.getElementById('btnBooked').className = 'vtog-btn'+(type==='booked'?' active':'');
}

function previewBanner(input) {
  const file = input.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const img  = document.getElementById('bannerPreview');
    const ph   = document.getElementById('bannerPlaceholder');
    const drop = document.getElementById('bannerDrop');
    img.src           = e.target.result;
    img.style.display = 'block';
    ph.style.display  = 'none';
    drop.classList.add('has-image');
  };
  reader.readAsDataURL(file);
}
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>