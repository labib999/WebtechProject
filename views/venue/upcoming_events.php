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

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Upcoming Events</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      All approved events scheduled at your venues
    </p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <span style="background:linear-gradient(135deg,#3b82f6,#8b5cf6); color:#fff;
                 font-size:13px; padding:6px 16px; border-radius:20px; font-weight:600;">
      <?= count($events) ?> Upcoming
    </span>
  </div>
</div>

<?php if (empty($events)): ?>
  <div class="text-center py-5 rounded-3" style="background:#fff; border:2px dashed #e2e8f0;">
    <i class="bi bi-calendar-x" style="font-size:52px; color:#e2e8f0;"></i>
    <h5 class="mt-3 fw-semibold">No upcoming events</h5>
    <p class="text-muted" style="font-size:14px;">Approved events will appear here</p>
  </div>

<?php else: ?>

  <!-- Timeline style list -->
  <div class="row g-3">
    <?php
    $gradients = [
      'linear-gradient(135deg,#3b82f6,#1d4ed8)',
      'linear-gradient(135deg,#10b981,#059669)',
      'linear-gradient(135deg,#f59e0b,#d97706)',
      'linear-gradient(135deg,#8b5cf6,#6d28d9)',
      'linear-gradient(135deg,#ef4444,#dc2626)',
      'linear-gradient(135deg,#06b6d4,#0891b2)',
    ];
    $colors = ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#ef4444','#06b6d4'];

    foreach ($events as $i => $event):
      $daysLeft = (int)ceil((strtotime($event['event_datetime']) - time()) / 86400);
      $gradient = $gradients[$i % count($gradients)];
      $color    = $colors[$i % count($colors)];
    ?>
    <div class="col-md-6">
      <div class="rounded-3 overflow-hidden h-100"
           style="background:#fff; border:1px solid #e2e8f0;
                  transition:box-shadow 0.2s, transform 0.2s;"
           onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.08)'; this.style.transform='translateY(-2px)'"
           onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">

        <!-- Color top bar -->
        <div style="height:5px; background:<?= $gradient ?>;"></div>

        <div class="p-4">
          <!-- Top row -->
          <div class="d-flex justify-content-between align-items-start mb-3">

            <!-- Date badge -->
            <div style="background:<?= $gradient ?>; width:52px; height:52px; border-radius:12px;
                        display:flex; flex-direction:column; align-items:center;
                        justify-content:center; color:#fff; flex-shrink:0;">
              <span style="font-size:18px; font-weight:800; line-height:1;">
                <?= date('d', strtotime($event['event_datetime'])) ?>
              </span>
              <span style="font-size:10px; opacity:0.9; letter-spacing:0.5px;">
                <?= strtoupper(date('M', strtotime($event['event_datetime']))) ?>
              </span>
            </div>

            <!-- Days left badge -->
            <?php if ($daysLeft <= 3): ?>
              <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626;
                          font-size:12px; font-weight:600; padding:4px 12px;
                          border-radius:20px; display:flex; align-items:center; gap:4px;">
                <span style="width:8px; height:8px; background:#ef4444; border-radius:50%; display:inline-block;"></span>
                <?= $daysLeft ?> days left
              </div>
            <?php elseif ($daysLeft <= 14): ?>
              <div style="background:#fffbeb; border:1px solid #fcd34d; color:#d97706;
                          font-size:12px; font-weight:600; padding:4px 12px;
                          border-radius:20px; display:flex; align-items:center; gap:4px;">
                <span style="width:8px; height:8px; background:#f59e0b; border-radius:50%; display:inline-block;"></span>
                <?= $daysLeft ?> days left
              </div>
            <?php else: ?>
              <div style="background:#ecfdf5; border:1px solid #6ee7b7; color:#059669;
                          font-size:12px; font-weight:600; padding:4px 12px;
                          border-radius:20px; display:flex; align-items:center; gap:4px;">
                <span style="width:8px; height:8px; background:#10b981; border-radius:50%; display:inline-block;"></span>
                <?= $daysLeft ?> days left
              </div>
            <?php endif; ?>
          </div>

          <!-- Event title -->
          <h6 class="fw-bold mb-1" style="font-size:15px; color:#1e293b;">
            <?= htmlspecialchars($event['title']) ?>
          </h6>

          <!-- Meta info -->
          <div class="d-flex flex-wrap gap-3 mb-3" style="font-size:12px; color:#64748b;">
            <span>
              <i class="bi bi-person me-1" style="color:<?= $color ?>;"></i>
              <?= htmlspecialchars($event['organiser_name']) ?>
            </span>
            <span>
              <i class="bi bi-building me-1" style="color:<?= $color ?>;"></i>
              <?= htmlspecialchars($event['venue_name']) ?>
            </span>
          </div>

          <!-- Divider -->
          <div style="height:1px; background:#f1f5f9; margin-bottom:12px;"></div>

          <!-- Date & Time -->
          <div class="d-flex justify-content-between align-items-center">
            <div style="font-size:13px; color:#475569;">
              <i class="bi bi-calendar3 me-1" style="color:<?= $color ?>;"></i>
              <strong><?= date('D, M d Y', strtotime($event['event_datetime'])) ?></strong>
            </div>
            <div style="background:#f8fafc; border:1px solid #e2e8f0;
                        padding:4px 12px; border-radius:20px; font-size:12px;
                        color:#475569; font-weight:600;">
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