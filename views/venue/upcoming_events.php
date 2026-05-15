<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model     = new VenueModel();
$managerId = $_SESSION['user_id'];
$events    = $model->getUpcomingEvents($managerId);

$activePage = 'upcoming_events';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Upcoming Events</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      All approved events scheduled at your venues
    </p>
  </div>
</div>

<?php if (empty($events)): ?>
  <div class="card p-5 text-center">
    <i class="bi bi-calendar-x" style="font-size:48px; color:#e2e8f0;"></i>
    <h5 class="mt-3 text-muted">No upcoming events</h5>
    <p class="text-muted" style="font-size:14px;">
      Approved events will appear here
    </p>
  </div>

<?php else: ?>
  <div class="card">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Event</th>
            <th>Organiser</th>
            <th>Venue</th>
            <th>Date & Time</th>
            <th>Days Left</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($events as $i => $event):
            $daysLeft = (int)ceil((strtotime($event['event_datetime']) - time()) / 86400);
          ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><strong><?= htmlspecialchars($event['title']) ?></strong></td>
            <td><?= htmlspecialchars($event['organiser_name']) ?></td>
            <td><?= htmlspecialchars($event['venue_name']) ?></td>
            <td><?= date('M d, Y — g:i A', strtotime($event['event_datetime'])) ?></td>
            <td>
              <?php if ($daysLeft <= 3): ?>
                <span class="badge bg-danger"><?= $daysLeft ?> days</span>
              <?php elseif ($daysLeft <= 7): ?>
                <span class="badge bg-warning text-dark"><?= $daysLeft ?> days</span>
              <?php else: ?>
                <span class="badge bg-success"><?= $daysLeft ?> days</span>
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