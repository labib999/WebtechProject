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

$activePage = 'booking_requests';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Booking Requests</h4>
    <p class="text-muted mb-0" style="font-size:14px;">Review and respond to organiser requests</p>
  </div>
</div>

<div class="d-flex gap-2 mb-4">
  <a href="?status=all"
     class="btn btn-sm <?= $filter === 'all'      ? 'btn-primary' : 'btn-outline-secondary' ?>">
    All
  </a>
  <a href="?status=pending"
     class="btn btn-sm <?= $filter === 'pending'  ? 'btn-warning'  : 'btn-outline-secondary' ?>">
    Pending
  </a>
  <a href="?status=approved"
     class="btn btn-sm <?= $filter === 'approved' ? 'btn-success'  : 'btn-outline-secondary' ?>">
    Approved
  </a>
  <a href="?status=rejected"
     class="btn btn-sm <?= $filter === 'rejected' ? 'btn-danger'   : 'btn-outline-secondary' ?>">
    Rejected
  </a>
</div>

<?php if (empty($requests)): ?>
  <div class="card p-5 text-center">
    <i class="bi bi-inbox" style="font-size:48px; color:#e2e8f0;"></i>
    <h5 class="mt-3 text-muted">No requests found</h5>
    <p class="text-muted" style="font-size:14px;">No booking requests match this filter</p>
  </div>

<?php else: ?>
  <?php foreach ($requests as $req):
    $dates = json_decode($req['requested_dates'] ?? '[]', true);
  ?>
  <div class="request-card">
    <div class="d-flex justify-content-between align-items-start gap-3">

      <div style="flex:1;">
        <div class="d-flex align-items-center gap-2 mb-2">
          <h6 class="fw-semibold mb-0">
            <?= htmlspecialchars($req['event_title_preview']) ?>
          </h6>
          <?php if ($req['status'] === 'pending'): ?>
            <span class="badge bg-warning text-dark">Pending</span>
          <?php elseif ($req['status'] === 'approved'): ?>
            <span class="badge bg-success">Approved</span>
          <?php else: ?>
            <span class="badge bg-danger">Rejected</span>
          <?php endif; ?>
        </div>

        <p class="text-muted mb-1" style="font-size:13px;">
          <i class="bi bi-person me-1"></i>
          <strong>Organiser:</strong> <?= htmlspecialchars($req['organiser_name']) ?>
          &nbsp;|&nbsp;
          <i class="bi bi-building me-1"></i>
          <strong>Venue:</strong> <?= htmlspecialchars($req['venue_name']) ?>
        </p>

        <?php if ($req['message']): ?>
          <p class="mb-2" style="font-size:13px;">
            <strong>Message:</strong> <?= htmlspecialchars($req['message']) ?>
          </p>
        <?php endif; ?>

        <div class="mb-2">
          <?php foreach ($dates as $date): ?>
            <span class="date-chip"><?= htmlspecialchars($date) ?></span>
          <?php endforeach; ?>
        </div>

        <?php if ($req['status'] === 'rejected' && $req['manager_note']): ?>
          <p class="mb-0" style="font-size:12px; color:#ef4444;">
            <strong>Rejection Note:</strong>
            <?= htmlspecialchars($req['manager_note']) ?>
          </p>
        <?php endif; ?>

        <p class="text-muted mb-0 mt-1" style="font-size:12px;">
          Submitted: <?= date('M d, Y', strtotime($req['submitted_at'])) ?>
        </p>
      </div>

      <?php if ($req['status'] === 'pending'): ?>
      <div class="d-flex flex-column gap-2" style="min-width:120px;">
        <form method="POST" action="/webtechproject/WebtechProject/controllers/VenueController.php">
          <input type="hidden" name="action" value="approve_request">
          <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
          <button type="submit" class="btn btn-success btn-sm w-100"
                  onclick="return confirm('Approve this booking request?')">
            <i class="bi bi-check-lg me-1"></i> Approve
          </button>
        </form>
        <button class="btn btn-danger btn-sm w-100"
                onclick="showRejectModal(<?= $req['id'] ?>)">
          <i class="bi bi-x-lg me-1"></i> Reject
        </button>
      </div>
      <?php endif; ?>

    </div>
  </div>
  <?php endforeach; ?>
<?php endif; ?>

<div class="modal fade" id="rejectModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-semibold">Reject Booking Request</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="/webtechproject/WebtechProject/controllers/VenueController.php">
        <input type="hidden" name="action" value="reject_request">
        <input type="hidden" name="request_id" id="rejectRequestId">
        <div class="modal-body">
          <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
          <textarea name="manager_note" class="form-control" rows="3"
                    placeholder="Explain why you are rejecting this request..."
                    required></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm"
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