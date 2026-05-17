<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model     = new VenueModel();
$managerId = $_SESSION['user_id'];
$history   = $model->getBookingHistory($managerId);

$activePage = 'booking_history';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Venue Booking History</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      All past events held at your venues
    </p>
  </div>
  <span class="badge bg-secondary" style="font-size:13px; padding:8px 14px;">
    <?= count($history) ?> Past Events
  </span>
</div>

<?php if (empty($history)): ?>
  <div class="card p-5 text-center">
    <i class="bi bi-clock-history" style="font-size:48px; color:#e2e8f0;"></i>
    <h5 class="mt-3 text-muted">No past events yet</h5>
    <p class="text-muted" style="font-size:14px;">Completed events will appear here</p>
  </div>

<?php else: ?>
  <div class="card">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>Event</th>
            <th>Organiser</th>
            <th>Venue</th>
            <th>Date</th>
            <th>Tickets Sold</th>
            <th>Check-ins</th>
            <th>Revenue (৳)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($history as $row): ?>
          <tr>
            <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
            <td><?= htmlspecialchars($row['organiser_name']) ?></td>
            <td style="font-size:13px;"><?= htmlspecialchars($row['venue_name']) ?></td>
            <td><?= date('M d, Y', strtotime($row['event_datetime'])) ?></td>
            <td>
              <?php if ($row['tickets_sold']): ?>
                <span class="badge bg-primary"><?= number_format($row['tickets_sold']) ?></span>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($row['checked_in']): ?>
                <span class="badge bg-success">✓ Checked In</span>
              <?php else: ?>
                <span class="badge bg-secondary">Not checked</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($row['total_price']): ?>
                <strong style="color:#3b82f6;">৳<?= number_format($row['total_price']) ?></strong>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php include '../shared/footer.php'; ?>