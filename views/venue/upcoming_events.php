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
  <span class="badge bg-primary" style="font-size:13px; padding:8px 14px;">
    <?= count($events) ?> Events
  </span>
</div>

<?php if (empty($events)): ?>
  <div class="card p-5 text-center">
    <i class="bi bi-calendar-x" style="font-size:48px; color:#e2e8f0;"></i>
    <h5 class="mt-3 text-muted">No upcoming events</h5>
    <p class="text-muted" style="font-size:14px;">Approved events will appear here</p>
  </div>

<?php else: ?>
  <div class="row g-3">
    <?php foreach ($events as $i => $event):
      $daysLeft = (int)ceil((strtotime($event['event_datetime']) - time()) / 86400);
      $colors   = ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#ef4444','#06b6d4'];
      $color    = $colors[$i % count($colors)];
    ?>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
        <div style="height:6px; background:<?= $color ?>;"></div>
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h6 class="fw-bold mb-1" style="font-size:15px;">
                <?= htmlspecialchars($event['title']) ?>
              </h6>
              <p class="text-muted mb-0" style="font-size:12px;">
                <i class="bi bi-person me-1"></i>
                <?= htmlspecialchars($event['organiser_name']) ?>
              </p>
            </div>
            <?php if ($daysLeft <= 3): ?>
              <span class="badge" style="background:#fef2f2; color:#ef4444; font-size:12px; padding:6px 10px;">
                🔴 <?= $daysLeft ?> days left
              </span>
            <?php elseif ($daysLeft <= 7): ?>
              <span class="badge" style="background:#fffbeb; color:#f59e0b; font-size:12px; padding:6px 10px;">
                🟡 <?= $daysLeft ?> days left
              </span>
            <?php else: ?>
              <span class="badge" style="background:#ecfdf5; color:#10b981; font-size:12px; padding:6px 10px;">
                🟢 <?= $daysLeft ?> days left
              </span>
            <?php endif; ?>
          </div>

          <div class="d-flex gap-3" style="font-size:13px; color:#64748b;">
            <span>
              <i class="bi bi-building me-1" style="color:<?= $color ?>;"></i>
              <?= htmlspecialchars($event['venue_name']) ?>
            </span>
          </div>

          <hr style="margin:12px 0; border-color:#f1f5f9;">

          <div class="d-flex justify-content-between align-items-center">
            <div style="font-size:13px;">
              <i class="bi bi-calendar3 me-1" style="color:<?= $color ?>;"></i>
              <strong><?= date('M d, Y', strtotime($event['event_datetime'])) ?></strong>
            </div>
            <div style="font-size:13px;">
              <i class="bi bi-clock me-1" style="color:<?= $color ?>;"></i>
              <?= date('g:i A', strtotime($event['event_datetime'])) ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include '../shared/footer.php'; ?>