<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model     = new VenueModel();
$managerId = $_SESSION['user_id'];

$filter   = $_GET['status'] ?? 'all';
$requests = $filter === 'all'
    ? $model->getBookingRequests($managerId)
    : $model->getBookingRequests($managerId, $filter);

$pendingCount  = count($model->getBookingRequests($managerId, 'pending'));
$approvedCount = count($model->getBookingRequests($managerId, 'approved'));
$rejectedCount = count($model->getBookingRequests($managerId, 'rejected'));

$activePage = 'booking_requests';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Booking Requests</h4>
    <p class="text-muted mb-0" style="font-size:14px;">Review and respond to organiser venue requests</p>
  </div>
</div>

<!-- Flash Message -->
<?php if (!empty($_SESSION['flash'])): ?>
  <div class="alert alert-<?= $_SESSION['flash']['type'] ?> d-flex align-items-center gap-2 mb-4"
       style="border-radius:10px; font-size:14px;">
    <i class="bi bi-<?= $_SESSION['flash']['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
    <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Stats Row -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <a href="?status=pending" class="text-decoration-none">
      <div class="rounded-3 p-3 d-flex align-items-center gap-3"
           style="background:<?= $filter === 'pending' ? '#fffbeb' : '#fff' ?>; border:1px solid <?= $filter === 'pending' ? '#fcd34d' : '#e2e8f0' ?>; transition:all 0.2s;">
        <div style="background:#fffbeb; width:44px; height:44px; border-radius:10px;
                    display:flex; align-items:center; justify-content:center; color:#f59e0b; font-size:20px;">
          <i class="bi bi-hourglass-split"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0" style="color:#1e293b;"><?= $pendingCount ?></h4>
          <p class="mb-0" style="font-size:12px; color:#64748b;">Pending</p>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-4">
    <a href="?status=approved" class="text-decoration-none">
      <div class="rounded-3 p-3 d-flex align-items-center gap-3"
           style="background:<?= $filter === 'approved' ? '#ecfdf5' : '#fff' ?>; border:1px solid <?= $filter === 'approved' ? '#6ee7b7' : '#e2e8f0' ?>; transition:all 0.2s;">
        <div style="background:#ecfdf5; width:44px; height:44px; border-radius:10px;
                    display:flex; align-items:center; justify-content:center; color:#10b981; font-size:20px;">
          <i class="bi bi-check-circle"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0" style="color:#1e293b;"><?= $approvedCount ?></h4>
          <p class="mb-0" style="font-size:12px; color:#64748b;">Approved</p>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-4">
    <a href="?status=rejected" class="text-decoration-none">
      <div class="rounded-3 p-3 d-flex align-items-center gap-3"
           style="background:<?= $filter === 'rejected' ? '#fef2f2' : '#fff' ?>; border:1px solid <?= $filter === 'rejected' ? '#fca5a5' : '#e2e8f0' ?>; transition:all 0.2s;">
        <div style="background:#fef2f2; width:44px; height:44px; border-radius:10px;
                    display:flex; align-items:center; justify-content:center; color:#ef4444; font-size:20px;">
          <i class="bi bi-x-circle"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0" style="color:#1e293b;"><?= $rejectedCount ?></h4>
          <p class="mb-0" style="font-size:12px; color:#64748b;">Rejected</p>
        </div>
      </div>
    </a>
  </div>
</div>

<!-- Filter Tabs -->
<div class="d-flex gap-2 mb-4">
  <a href="?status=all"
     class="btn btn-sm <?= $filter === 'all' ? 'btn-primary' : 'btn-outline-secondary' ?>">
    All (<?= $pendingCount + $approvedCount + $rejectedCount ?>)
  </a>
  <a href="?status=pending"
     class="btn btn-sm <?= $filter === 'pending' ? 'btn-warning' : 'btn-outline-secondary' ?>">
    Pending (<?= $pendingCount ?>)
  </a>
  <a href="?status=approved"
     class="btn btn-sm <?= $filter === 'approved' ? 'btn-success' : 'btn-outline-secondary' ?>">
    Approved (<?= $approvedCount ?>)
  </a>
  <a href="?status=rejected"
     class="btn btn-sm <?= $filter === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' ?>">
    Rejected (<?= $rejectedCount ?>)
  </a>
</div>

<?php if (empty($requests)): ?>
  <div class="text-center py-5 rounded-3" style="background:#fff; border:2px dashed #e2e8f0;">
    <i class="bi bi-inbox" style="font-size:48px; color:#e2e8f0;"></i>
    <h5 class="mt-3 fw-semibold">No requests found</h5>
    <p class="text-muted" style="font-size:14px;">No booking requests match this filter</p>
  </div>

<?php else: ?>
  <?php foreach ($requests as $req):
    $dates = json_decode($req['requested_dates'] ?? '[]', true);
    $borderColor = $req['status'] === 'pending' ? '#f59e0b' : ($req['status'] === 'approved' ? '#10b981' : '#ef4444');
    $bgColor     = $req['status'] === 'pending' ? '#fffbeb' : ($req['status'] === 'approved' ? '#f0fdf4' : '#fef2f2');
  ?>
  <div class="rounded-3 mb-3 overflow-hidden"
       style="background:#fff; border:1px solid #e2e8f0; border-left:4px solid <?= $borderColor ?>;">
    <div class="p-4">
      <div class="d-flex justify-content-between align-items-start gap-3">

        <div style="flex:1;">
          <!-- Title + Badge -->
          <div class="d-flex align-items-center gap-2 mb-3">
            <div style="background:<?= $bgColor ?>; width:36px; height:36px; border-radius:8px;
                        display:flex; align-items:center; justify-content:center; font-size:16px; color:<?= $borderColor ?>;">
              <i class="bi bi-calendar-event"></i>
            </div>
            <div>
              <h6 class="fw-bold mb-0" style="font-size:15px;">
                <?= htmlspecialchars($req['event_title_preview']) ?>
              </h6>
              <div class="d-flex align-items-center gap-1 mt-1">
                <?php if ($req['status'] === 'pending'): ?>
                  <span style="background:#fffbeb; color:#d97706; font-size:11px; padding:2px 8px; border-radius:20px; font-weight:600;">
                    ⏳ Pending Review
                  </span>
                <?php elseif ($req['status'] === 'approved'): ?>
                  <span style="background:#ecfdf5; color:#059669; font-size:11px; padding:2px 8px; border-radius:20px; font-weight:600;">
                    ✅ Approved
                  </span>
                <?php else: ?>
                  <span style="background:#fef2f2; color:#dc2626; font-size:11px; padding:2px 8px; border-radius:20px; font-weight:600;">
                    ❌ Rejected
                  </span>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Info -->
          <div class="d-flex gap-4 mb-3" style="font-size:13px; color:#64748b;">
            <span><i class="bi bi-person me-1" style="color:#3b82f6;"></i><?= htmlspecialchars($req['organiser_name']) ?></span>
            <span><i class="bi bi-building me-1" style="color:#10b981;"></i><?= htmlspecialchars($req['venue_name']) ?></span>
            <span><i class="bi bi-clock me-1" style="color:#f59e0b;"></i><?= date('M d, Y', strtotime($req['submitted_at'])) ?></span>
          </div>

          <!-- Message -->
          <?php if ($req['message']): ?>
          <div class="rounded-2 p-2 mb-3"
               style="background:#f8fafc; border:1px solid #f1f5f9; font-size:13px; color:#475569;">
            <i class="bi bi-chat-left-text me-1"></i>
            <?= htmlspecialchars($req['message']) ?>
          </div>
          <?php endif; ?>

          <!-- Dates -->
          <div class="mb-2">
            <?php foreach ($dates as $date): ?>
              <span style="display:inline-block; background:#eff6ff; color:#3b82f6;
                           padding:3px 10px; border-radius:20px; font-size:12px;
                           font-weight:500; margin:2px; border:1px solid #bfdbfe;">
                <i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($date) ?>
              </span>
            <?php endforeach; ?>
          </div>

          <!-- Rejection Note -->
          <?php if ($req['status'] === 'rejected' && $req['manager_note']): ?>
          <div class="rounded-2 p-2 mt-2"
               style="background:#fef2f2; border:1px solid #fecaca; font-size:12px; color:#dc2626;">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <strong>Rejection Note:</strong> <?= htmlspecialchars($req['manager_note']) ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <?php if ($req['status'] === 'pending'): ?>
        <div class="d-flex flex-column gap-2" style="min-width:130px;">
          <form method="POST" action="/webtechproject/WebtechProject/controllers/VenueController.php">
            <input type="hidden" name="action" value="approve_request">
            <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
            <button type="submit" class="btn btn-sm w-100"
                    style="background:#ecfdf5; color:#059669; border:1px solid #6ee7b7; font-weight:600;"
                    onclick="return confirm('Approve this booking request?')">
              <i class="bi bi-check-lg me-1"></i> Approve
            </button>
          </form>
          <button class="btn btn-sm w-100"
                  style="background:#fef2f2; color:#dc2626; border:1px solid #fca5a5; font-weight:600;"
                  onclick="showRejectModal(<?= $req['id'] ?>)">
            <i class="bi bi-x-lg me-1"></i> Reject
          </button>
        </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:12px; overflow:hidden;">
      <div class="modal-header" style="background:linear-gradient(135deg, #991b1b, #ef4444); color:#fff; border:none;">
        <h6 class="modal-title fw-semibold">
          <i class="bi bi-x-circle me-2"></i>Reject Booking Request
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="/webtechproject/WebtechProject/controllers/VenueController.php">
        <input type="hidden" name="action" value="reject_request">
        <input type="hidden" name="request_id" id="rejectRequestId">
        <div class="modal-body" style="padding:20px;">
          <p style="font-size:13px; color:#64748b; margin-bottom:12px;">
            Please provide a reason for rejecting this request. The organiser will be notified.
          </p>
          <label class="form-label fw-semibold" style="font-size:13px;">
            Reason for Rejection <span class="text-danger">*</span>
          </label>
          <textarea name="manager_note" class="form-control" rows="3"
                    placeholder="e.g. Venue already booked for those dates..."
                    required style="font-size:13px;"></textarea>
        </div>
        <div class="modal-footer" style="border:none; background:#fafafa;">
          <button type="button" class="btn btn-outline-secondary btn-sm"
                  data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-sm">
            <i class="bi bi-x-lg me-1"></i> Confirm Reject
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function showRejectModal(requestId) {
    document.getElementById('rejectRequestId').value = requestId;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>

<?php include '../shared/footer.php'; ?>