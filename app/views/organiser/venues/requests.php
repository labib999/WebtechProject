<?php
$pageTitle  = 'My Venue Requests';
$activePage = 'venue-requests';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .req-card { background:#fff; border-radius:14px; padding:1.25rem;
    box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 14px rgba(0,0,0,.04);
    border:1px solid rgba(0,0,0,.05); margin-bottom:.75rem; }
  .req-venue { font-size:.95rem; font-weight:700; color:#111; }
  .req-meta  { font-size:.76rem; color:#9ca3af; margin-top:.2rem; }
  .st-pending  { background:#FFFBEB; color:#92400e; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
  .st-approved { background:#ECFDF5; color:#065f46; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
  .st-rejected { background:#FEF2F2; color:#991b1b; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
  [data-bs-theme="dark"] .req-card  { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .req-venue { color:#f3f4f6; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1.05rem;">My Venue Requests</h4>
    <p class="text-muted mb-0" style="font-size:.8rem;"><?= count($requests) ?> request<?= count($requests)!=1?'s':'' ?></p>
  </div>
  <a href="/WebtechProject/public/organiser/venues"
     style="font-size:.82rem;color:#0F6E56;text-decoration:none;font-weight:600;">
    <i class="bi bi-building me-1"></i>Browse Venues
  </a>
</div>

<?php if (!empty($success)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#ECFDF5;border:1px solid #6ee7b7;color:#065f46;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-check-circle-fill"></i><span><?= htmlspecialchars($success) ?></span>
  </div>
<?php endif; ?>

<?php if (empty($requests)): ?>
  <div style="text-align:center;padding:3rem;color:#9ca3af;">
    <i class="bi bi-send" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db;"></i>
    <p style="font-size:.88rem;">No venue requests yet. <a href="/WebtechProject/public/organiser/venues" style="color:#0F6E56;">Browse venues</a> to submit one.</p>
  </div>
<?php else: foreach ($requests as $r):
  $dates = json_decode($r['requested_dates'] ?? '[]', true) ?? [];
?>
  <div class="req-card">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
      <div>
        <div class="req-venue"><?= htmlspecialchars($r['venue_name']) ?></div>
        <div class="req-meta">
          <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($r['city']) ?> &middot;
          Capacity <?= number_format($r['capacity']) ?>
        </div>
        <?php if (!empty($r['event_title_preview'])): ?>
          <div class="req-meta" style="margin-top:.2rem;">
            <i class="bi bi-calendar-event me-1"></i><?= htmlspecialchars($r['event_title_preview']) ?>
          </div>
        <?php endif; ?>
        <div class="req-meta" style="margin-top:.3rem;">
          <i class="bi bi-calendar3 me-1"></i>
          Requested: <?= implode(', ', array_map(fn($d)=>date('d M Y',strtotime($d)), $dates)) ?>
        </div>
        <div class="req-meta" style="margin-top:.1rem;">
          <i class="bi bi-clock me-1"></i>Submitted <?= date('d M Y', strtotime($r['submitted_at'])) ?>
        </div>
      </div>
      <span class="st-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span>
    </div>
    <?php if (!empty($r['manager_note'])): ?>
      <div style="margin-top:.75rem;padding:.65rem .85rem;background:#f8f9fa;border-radius:8px;
                  border-left:3px solid <?= $r['status']==='approved'?'#0F6E56':'#ef4444' ?>;font-size:.83rem;color:#374151;">
        <strong>Venue Manager:</strong> <?= htmlspecialchars($r['manager_note']) ?>
      </div>
    <?php endif; ?>
  </div>
<?php endforeach; endif; ?>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>