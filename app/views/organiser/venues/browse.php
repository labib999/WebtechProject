<?php
$pageTitle  = 'Browse Venues';
$activePage = 'venues';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .venue-card { background:#fff; border-radius:14px; padding:1.25rem;
    box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 14px rgba(0,0,0,.04);
    border:1px solid rgba(0,0,0,.05); height:100%; transition:box-shadow .15s; }
  .venue-card:hover { box-shadow:0 6px 24px rgba(0,0,0,.1); }
  .venue-name { font-size:.95rem; font-weight:700; color:#111; margin-bottom:.25rem; }
  .venue-meta { font-size:.78rem; color:#9ca3af; }
  .venue-cap  { font-size:1.1rem; font-weight:800; color:#0F6E56; }
  .facility-tag { display:inline-block; background:#f3f4f6; color:#374151;
    padding:2px 8px; border-radius:8px; font-size:.72rem; margin:2px; }
  .filter-bar { background:#fff; border-radius:13px; padding:1rem 1.25rem;
    margin-bottom:1.25rem; box-shadow:0 1px 3px rgba(0,0,0,.05);
    border:1px solid rgba(0,0,0,.05); }
  .btn-filter { padding:.5rem 1.1rem; border-radius:9px; border:none;
    background:#0F6E56; color:#fff; font-size:.85rem; font-weight:600; cursor:pointer; }
  .req-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5);
    z-index:2000; align-items:center; justify-content:center; padding:1rem; }
  .req-modal.open { display:flex; }
  .req-box { background:#fff; border-radius:16px; padding:1.75rem; width:100%;
    max-width:480px; box-shadow:0 20px 60px rgba(0,0,0,.2); }
  .form-label { font-size:.8rem; font-weight:700; color:#374151; margin-bottom:.35rem; }
  .form-control,.form-select { border-color:#e5e7eb; font-size:.9rem; }
  .form-control:focus { border-color:#0F6E56; box-shadow:0 0 0 3px rgba(15,110,86,.12); }
  .btn-req { padding:.6rem 1.25rem; border-radius:9px; border:none; background:#0F6E56;
    color:#fff; font-size:.85rem; font-weight:600; cursor:pointer; transition:all .15s; }
  .btn-req:hover { background:#063D30; }

  [data-bs-theme="dark"] .venue-card  { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .filter-bar  { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .venue-name  { color:#f3f4f6; }
  [data-bs-theme="dark"] .facility-tag{ background:#374151; color:#d1d5db; }
  [data-bs-theme="dark"] .req-box     { background:#1f2937; }
  [data-bs-theme="dark"] .form-control,
  [data-bs-theme="dark"] .form-select { background:#253245; border-color:#374151; color:#f3f4f6; }
  [data-bs-theme="dark"] .form-label  { color:#d1d5db; }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1.05rem;">Browse Venues</h4>
    <p class="text-muted mb-0" style="font-size:.8rem;"><?= count($venues) ?> venue<?= count($venues)!=1?'s':'' ?> available</p>
  </div>
  <a href="/WebtechProject/public/organiser/venue-requests"
     style="font-size:.82rem;color:#0F6E56;text-decoration:none;font-weight:600;">
    <i class="bi bi-list-check me-1"></i>My Requests
  </a>
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

<!-- Filter -->
<div class="filter-bar">
  <form method="GET" action="/WebtechProject/public/organiser/venues" class="row g-2 align-items-end">
    <div class="col-md-4">
      <label style="font-size:.75rem;font-weight:600;color:#6b7280;display:block;margin-bottom:.25rem;">City</label>
      <select name="city" class="form-select form-select-sm">
        <option value="">All cities</option>
        <?php foreach ($cities as $c): ?>
          <option value="<?= htmlspecialchars($c['city']) ?>"
            <?= $city===$c['city']?'selected':'' ?>><?= htmlspecialchars($c['city']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label style="font-size:.75rem;font-weight:600;color:#6b7280;display:block;margin-bottom:.25rem;">Min Capacity</label>
      <input type="number" name="min_cap" class="form-control form-control-sm"
             placeholder="e.g. 100" value="<?= $minCap ?: '' ?>"/>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn-filter w-100">
        <i class="bi bi-search me-1"></i>Search
      </button>
    </div>
  </form>
</div>

<!-- Venues grid -->
<?php if (empty($venues)): ?>
  <div style="text-align:center;padding:3rem;color:#9ca3af;">
    <i class="bi bi-building" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db;"></i>
    <p>No venues found matching your search.</p>
  </div>
<?php else: ?>
  <div class="row g-3">
    <?php foreach ($venues as $v):
      $facilities = json_decode($v['facilities'] ?? '[]', true) ?? [];
    ?>
    <div class="col-md-6 col-xl-4">
      <div class="venue-card">
        <div class="d-flex justify-content-between align-items-start mb-1">
          <div style="flex:1;min-width:0;">
            <div class="venue-name"><?= htmlspecialchars($v['name']) ?></div>
            <div class="venue-meta">
              <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($v['city']) ?> &middot;
              <?= htmlspecialchars($v['address']) ?>
            </div>
          </div>
        </div>

        <div class="venue-cap mt-2"><?= number_format($v['capacity']) ?>
          <span style="font-size:.78rem;font-weight:400;color:#9ca3af;">max capacity</span>
        </div>

        <?php if (!empty($v['description'])): ?>
          <p style="font-size:.8rem;color:#6b7280;margin:.5rem 0;">
            <?= htmlspecialchars(mb_strimwidth($v['description'],0,90,'…')) ?>
          </p>
        <?php endif; ?>

        <?php if (!empty($facilities)): ?>
          <div style="margin:.5rem 0;">
            <?php foreach (array_slice($facilities,0,5) as $f): ?>
              <span class="facility-tag"><?= htmlspecialchars($f) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div style="font-size:.74rem;color:#9ca3af;margin:.5rem 0 .85rem;">
          <i class="bi bi-person me-1"></i>Managed by <?= htmlspecialchars($v['manager_name']) ?>
        </div>

        <button class="btn-req w-100"
                onclick="openRequestModal(<?= $v['id'] ?>, '<?= addslashes(htmlspecialchars($v['name'])) ?>')">
          <i class="bi bi-send me-1"></i>Request Booking
        </button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<!-- Request Modal -->
<div class="req-modal" id="reqModal">
  <div class="req-box">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold mb-0" id="modalVenueName" style="font-size:.95rem;"></h6>
      <button onclick="closeModal()"
              style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:#9ca3af;line-height:1;">×</button>
    </div>
    <form method="POST" action="/WebtechProject/public/organiser/venues/request">
      <input type="hidden" name="venue_id" id="modalVenueId"/>
      <div class="mb-3">
        <label class="form-label">Event Name
          <span style="font-weight:400;color:#9ca3af;">(optional)</span>
        </label>
        <input type="text" name="event_title_preview" class="form-control"
               placeholder="e.g. Tech Summit 2027"/>
      </div>
      <div class="mb-3">
        <label class="form-label">Requested Dates <span style="color:#ef4444;">*</span></label>
        <input type="text" name="requested_dates" class="form-control"
               placeholder="e.g. 2027-01-15, 2027-01-16" required/>
        <div style="font-size:.73rem;color:#9ca3af;margin-top:.25rem;">
          Separate multiple dates with commas
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label">Message to Venue Manager
          <span style="font-weight:400;color:#9ca3af;">(optional)</span>
        </label>
        <textarea name="message" class="form-control" rows="2"
                  placeholder="Any special requirements..."></textarea>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn-req" style="flex:1;">
          <i class="bi bi-send-fill me-1"></i>Submit Request
        </button>
        <button type="button" onclick="closeModal()"
                style="padding:.6rem 1rem;border-radius:9px;border:1.5px solid #e5e7eb;
                       background:none;font-size:.85rem;cursor:pointer;font-weight:600;">
          Cancel
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function openRequestModal(id, name) {
  document.getElementById('modalVenueId').value = id;
  document.getElementById('modalVenueName').textContent = 'Request: ' + name;
  document.getElementById('reqModal').classList.add('open');
}
function closeModal() {
  document.getElementById('reqModal').classList.remove('open');
}
document.getElementById('reqModal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>