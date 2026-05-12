<?php
$pageTitle  = 'Announcements';
$activePage = 'announcements';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .form-section { background:#fff; border-radius:14px; padding:1.5rem;
    box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 14px rgba(0,0,0,.04);
    border:1px solid rgba(0,0,0,.05); }
  .form-section-title { font-size:.82rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.6px; color:#9ca3af; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
  .form-label { font-size:.8rem; font-weight:700; color:#374151; margin-bottom:.35rem; }
  .form-control,.form-select { border-color:#e5e7eb; font-size:.9rem; }
  .form-control:focus,.form-select:focus { border-color:#0F6E56; box-shadow:0 0 0 3px rgba(15,110,86,.12); }
  textarea.form-control { resize:vertical; min-height:110px; }
  .btn-send { padding:.65rem 1.5rem; border-radius:10px; border:none; background:#0F6E56;
    color:#fff; font-size:.9rem; font-weight:600; cursor:pointer;
    box-shadow:0 4px 14px rgba(15,110,86,.25); transition:all .15s; }
  .btn-send:hover { background:#063D30; }

  .ann-item { display:flex; gap:1rem; padding:1rem 0; border-bottom:1px solid #f3f4f6; }
  .ann-item:last-child { border-bottom:none; }
  .ann-icon { width:40px; height:40px; background:#E1F5EE; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    color:#0F6E56; font-size:1.1rem; flex-shrink:0; }
  .ann-title { font-size:.9rem; font-weight:700; color:#111; margin-bottom:.15rem; }
  .ann-body  { font-size:.82rem; color:#6b7280; line-height:1.5; margin-bottom:.3rem; }
  .ann-meta  { font-size:.73rem; color:#9ca3af; }

  [data-bs-theme="dark"] .form-section { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .form-control,
  [data-bs-theme="dark"] .form-select { background:#253245; border-color:#374151; color:#f3f4f6; }
  [data-bs-theme="dark"] .form-label  { color:#d1d5db; }
  [data-bs-theme="dark"] .ann-title   { color:#f3f4f6; }
  [data-bs-theme="dark"] .ann-item    { border-color:#374151; }
  [data-bs-theme="dark"] .ann-icon    { background:#1a3a2e; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1.05rem;">Announcements</h4>
    <p class="text-muted mb-0" style="font-size:.8rem;">Send messages to all ticket holders of an event</p>
  </div>
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

<div class="row g-3">
  <!-- Compose form -->
  <div class="col-lg-6">
    <div class="form-section">
      <div class="form-section-title"><i class="bi bi-megaphone-fill"></i> Compose Announcement</div>

      <?php if (empty($myEvents)): ?>
        <div style="text-align:center;padding:1.5rem;color:#9ca3af;font-size:.88rem;">
          <i class="bi bi-calendar-x" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#d1d5db;"></i>
          No published events. Publish an event first.
        </div>
      <?php else: ?>
        <form method="POST" action="/WebtechProject/public/organiser/announcements/send">
          <div class="mb-3">
            <label class="form-label">Send to attendees of <span style="color:#ef4444;">*</span></label>
            <select name="event_id" class="form-select" required>
              <option value="">— Select event —</option>
              <?php foreach ($myEvents as $ev): ?>
                <option value="<?= $ev['id'] ?>"><?= htmlspecialchars($ev['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Subject / Title <span style="color:#ef4444;">*</span></label>
            <input type="text" name="title" class="form-control"
                   placeholder="e.g. Important: Venue Parking Update" required/>
          </div>
          <div class="mb-4">
            <label class="form-label">Message <span style="color:#ef4444;">*</span></label>
            <textarea name="body" class="form-control"
                      placeholder="Write your message to all ticket holders..." required></textarea>
          </div>
          <button type="submit" class="btn-send"
                  onclick="return confirm('Send this announcement to all ticket holders?')">
            <i class="bi bi-send-fill me-2"></i>Send Announcement
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>

  <!-- History -->
  <div class="col-lg-6">
    <div class="form-section" style="height:100%;">
      <div class="form-section-title"><i class="bi bi-clock-history"></i> Sent Announcements</div>

      <?php if (empty($history)): ?>
        <div style="text-align:center;padding:2rem;color:#9ca3af;">
          <i class="bi bi-megaphone" style="font-size:2rem;display:block;margin-bottom:.75rem;color:#d1d5db;"></i>
          <p style="font-size:.85rem;">No announcements sent yet.</p>
        </div>
      <?php else: ?>
        <div style="max-height:420px;overflow-y:auto;">
          <?php foreach ($history as $a): ?>
            <div class="ann-item">
              <div class="ann-icon"><i class="bi bi-megaphone-fill"></i></div>
              <div style="flex:1;min-width:0;">
                <div class="ann-title"><?= htmlspecialchars($a['title']) ?></div>
                <div class="ann-body text-truncate" style="-webkit-line-clamp:2;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;">
                  <?= htmlspecialchars($a['body']) ?>
                </div>
                <div class="ann-meta">
                  <i class="bi bi-calendar3 me-1"></i><?= date('d M Y H:i', strtotime($a['sent_at'])) ?>
                  &middot; <i class="bi bi-people me-1"></i><?= $a['recipient_count'] ?> recipient<?= $a['recipient_count']!=1?'s':'' ?>
                  &middot; <?= htmlspecialchars(mb_strimwidth($a['event_title'],0,28,'…')) ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>