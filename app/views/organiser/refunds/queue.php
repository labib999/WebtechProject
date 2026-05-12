<?php
$pageTitle  = 'Refund Requests';
$activePage = 'refunds';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .filter-tab { padding:.4rem 1.1rem; border-radius:20px; font-size:.82rem; font-weight:600;
    text-decoration:none; border:1.5px solid #e5e7eb; color:#6b7280; transition:all .15s; }
  .filter-tab:hover { border-color:#0F6E56; color:#0F6E56; }
  .filter-tab.active { background:#0F6E56; color:#fff; border-color:#0F6E56; }
  .filter-badge { background:rgba(255,255,255,.25); border-radius:10px;
    font-size:.68rem; padding:1px 6px; margin-left:3px; }
  .filter-tab:not(.active) .filter-badge { background:#f3f4f6; color:#6b7280; }

  .req-card { background:#fff; border-radius:14px; padding:1.25rem;
    box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 14px rgba(0,0,0,.04);
    border:1px solid rgba(0,0,0,.05); margin-bottom:.75rem; }
  .req-attendee { font-size:.9rem; font-weight:700; color:#111; }
  .req-meta     { font-size:.76rem; color:#9ca3af; margin-top:.15rem; }
  .req-reason   { font-size:.85rem; color:#374151; margin-top:.6rem; padding:.65rem .85rem;
    background:#f8f9fa; border-radius:8px; border-left:3px solid #e5e7eb; }
  .req-amount   { font-size:1.1rem; font-weight:800; color:#0F6E56; }
  .btn-approve  { padding:.38rem .9rem; border-radius:8px; font-size:.8rem; font-weight:600;
    border:1.5px solid #6ee7b7; color:#065f46; background:none; cursor:pointer; transition:all .15s; }
  .btn-approve:hover { background:#ECFDF5; }
  .btn-reject   { padding:.38rem .9rem; border-radius:8px; font-size:.8rem; font-weight:600;
    border:1.5px solid #fecaca; color:#991b1b; background:none; cursor:pointer; transition:all .15s; }
  .btn-reject:hover { background:#FEF2F2; }
  .reject-form  { display:none; margin-top:.75rem; padding:.85rem;
    background:#FEF2F2; border-radius:10px; }
  .reject-form.open { display:block; }
  [data-bs-theme="dark"] .req-card    { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .req-attendee{ color:#f3f4f6; }
  [data-bs-theme="dark"] .req-reason  { background:#253245; border-color:#374151; color:#d1d5db; }
  [data-bs-theme="dark"] .reject-form { background:rgba(248,113,113,0.1); }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1.05rem;">Refund Requests</h4>
    <p class="text-muted mb-0" style="font-size:.8rem;">Review and action attendee refund requests</p>
  </div>
</div>

<?php if (!empty($success)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#ECFDF5;border:1px solid #6ee7b7;color:#065f46;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-check-circle-fill"></i><span><?= htmlspecialchars($success) ?></span>
  </div>
<?php endif; ?>

<!-- Filter tabs -->
<div class="d-flex flex-wrap gap-2 mb-4">
  <?php foreach (['pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $key=>$lbl): ?>
    <a href="/WebtechProject/public/organiser/refunds?status=<?= $key ?>"
       class="filter-tab <?= $filter===$key?'active':'' ?>">
      <?= $lbl ?><span class="filter-badge"><?= $statusCounts[$key] ?></span>
    </a>
  <?php endforeach; ?>
</div>

<!-- Requests -->
<?php if (empty($requests)): ?>
  <div style="text-align:center;padding:3rem;color:#9ca3af;">
    <i class="bi bi-receipt-cutoff" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db;"></i>
    <p style="font-size:.88rem;">No <?= $filter ?> refund requests.</p>
  </div>
<?php else: foreach ($requests as $r): ?>
  <div class="req-card">
    <div class="row align-items-start">
      <div class="col-md-7">
        <div class="req-attendee"><?= htmlspecialchars($r['attendee_name']) ?></div>
        <div class="req-meta">
          <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($r['attendee_email']) ?> &middot;
          <code style="font-size:.78rem;"><?= htmlspecialchars($r['ticket_code']) ?></code> &middot;
          <?= htmlspecialchars($r['tier_name']) ?>
        </div>
        <div class="req-meta" style="margin-top:.2rem;">
          <i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($r['event_title']) ?> &middot;
          <?= date('d M Y', strtotime($r['created_at'])) ?>
        </div>
        <div class="req-reason">
          <i class="bi bi-chat-left-text me-1" style="color:#9ca3af;"></i>
          "<?= htmlspecialchars($r['reason']) ?>"
        </div>
        <?php if (!empty($r['organiser_note'])): ?>
          <div style="font-size:.78rem;color:#9ca3af;margin-top:.4rem;">
            <i class="bi bi-reply me-1"></i>Your note: <?= htmlspecialchars($r['organiser_note']) ?>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <div class="req-amount">$<?= number_format($r['total_price'],2) ?></div>
        <div style="font-size:.75rem;color:#9ca3af;margin-bottom:.75rem;">refund amount</div>

        <?php if ($r['status'] === 'pending'): ?>
          <!-- Approve -->
          <form method="POST" action="/WebtechProject/public/organiser/refunds/approve"
                style="display:inline;" onsubmit="return confirm('Approve this refund? The booking will be marked as refunded.')">
            <input type="hidden" name="request_id" value="<?= $r['id'] ?>"/>
            <button type="submit" class="btn-approve me-1">
              <i class="bi bi-check-lg me-1"></i>Approve
            </button>
          </form>
          <!-- Reject toggle -->
          <button type="button" class="btn-reject"
                  onclick="toggleReject(<?= $r['id'] ?>)">
            <i class="bi bi-x-lg me-1"></i>Reject
          </button>

          <!-- Reject form -->
          <div class="reject-form" id="rejectForm<?= $r['id'] ?>">
            <form method="POST" action="/WebtechProject/public/organiser/refunds/reject">
              <input type="hidden" name="request_id" value="<?= $r['id'] ?>"/>
              <label style="font-size:.78rem;font-weight:600;color:#991b1b;display:block;margin-bottom:.35rem;">
                Reason for rejection (shown to attendee)
              </label>
              <input type="text" name="organiser_note" class="form-control form-control-sm mb-2"
                     placeholder="e.g. Past the refund window" required/>
              <button type="submit" class="btn-reject" style="width:100%;">
                Confirm Rejection
              </button>
            </form>
          </div>
        <?php else: ?>
          <span class="pill <?= $r['status']==='approved'?'pill-active':'pill-ref' ?>"
                style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:.78rem;font-weight:700;">
            <?= ucfirst($r['status']) ?>
          </span>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endforeach; endif; ?>

<script>
function toggleReject(id) {
  const f = document.getElementById('rejectForm' + id);
  f.classList.toggle('open');
}
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>