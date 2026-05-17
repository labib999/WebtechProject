<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';

$managerId = $_SESSION['user_id'];
$conn = getDB();

$stmt = $conn->prepare("SELECT COUNT(*) FROM venues WHERE manager_id = ? AND is_active = 1");
$stmt->bind_param('i', $managerId);
$stmt->execute();
$stmt->bind_result($totalVenues);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM venue_booking_requests vbr JOIN venues v ON vbr.venue_id = v.id WHERE v.manager_id = ? AND vbr.status = 'pending'");
$stmt->bind_param('i', $managerId);
$stmt->execute();
$stmt->bind_result($pendingRequests);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) FROM events e JOIN venues v ON e.venue_id = v.id WHERE v.manager_id = ? AND e.status = 'published' AND e.event_datetime >= NOW()");
$stmt->bind_param('i', $managerId);
$stmt->execute();
$stmt->bind_result($upcomingEvents);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT COALESCE(SUM(vp.price_per_day), 0) FROM venue_availability va JOIN venues v ON va.venue_id = v.id JOIN venue_pricing vp ON vp.venue_id = v.id WHERE v.manager_id = ? AND va.status = 'booked' AND MONTH(va.date) = MONTH(NOW()) AND YEAR(va.date) = YEAR(NOW()) AND vp.day_type = 'weekday'");
$stmt->bind_param('i', $managerId);
$stmt->execute();
$stmt->bind_result($monthlyRevenue);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT vbr.id, vbr.event_title_preview, vbr.requested_dates, vbr.status, vbr.submitted_at, u.name as organiser_name, v.name as venue_name FROM venue_booking_requests vbr JOIN venues v ON vbr.venue_id = v.id JOIN users u ON vbr.organiser_id = u.id WHERE v.manager_id = ? ORDER BY vbr.submitted_at DESC LIMIT 5");
$stmt->bind_param('i', $managerId);
$stmt->execute();
$recentRequests = $stmt->get_result();
$stmt->close();

$stmt = $conn->prepare("SELECT e.title, e.event_datetime, u.name as organiser_name, v.name as venue_name FROM events e JOIN venues v ON e.venue_id = v.id JOIN users u ON e.organiser_id = u.id WHERE v.manager_id = ? AND e.status = 'published' AND e.event_datetime >= NOW() ORDER BY e.event_datetime ASC LIMIT 5");
$stmt->bind_param('i', $managerId);
$stmt->execute();
$upcomingList = $stmt->get_result();
$stmt->close();
$conn->close();

$activePage = 'dashboard';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="rounded-3 p-4 mb-4 text-white position-relative overflow-hidden"
     style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #1e40af 100%); min-height:130px;">
  <div style="position:absolute; top:-30px; right:-30px; width:200px; height:200px;
              background:rgba(255,255,255,0.04); border-radius:50%;"></div>
  <div style="position:absolute; bottom:-50px; right:80px; width:150px; height:150px;
              background:rgba(255,255,255,0.03); border-radius:50%;"></div>
  <div class="d-flex justify-content-between align-items-center position-relative">
    <div>
      <p class="mb-1" style="font-size:13px; opacity:0.7;">
        <i class="bi bi-calendar3 me-1"></i><?= date('l, d F Y') ?>
      </p>
      <h4 class="fw-bold mb-1" style="font-size:22px;">
        Good <?= (date('H') < 12) ? 'Morning' : ((date('H') < 17) ? 'Afternoon' : 'Evening') ?>,
        <?= htmlspecialchars($_SESSION['user_name']) ?> 👋
      </h4>
      <p class="mb-0" style="font-size:13px; opacity:0.75;">
        Here is your venue management overview for today.
      </p>
    </div>
    <div class="d-none d-md-flex gap-4 text-center">
      <div>
        <h3 class="fw-bold mb-0"><?= $totalVenues ?></h3>
        <small style="opacity:0.7;">Active Venues</small>
      </div>
      <div style="width:1px; background:rgba(255,255,255,0.2);"></div>
      <div>
        <h3 class="fw-bold mb-0"><?= $upcomingEvents ?></h3>
        <small style="opacity:0.7;">Upcoming Events</small>
      </div>
      <div style="width:1px; background:rgba(255,255,255,0.2);"></div>
      <div>
        <h3 class="fw-bold mb-0"><?= $pendingRequests ?></h3>
        <small style="opacity:0.7;">Pending</small>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="rounded-3 p-3 h-100" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #3b82f6;">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div style="background:#eff6ff; width:44px; height:44px; border-radius:10px;
                    display:flex; align-items:center; justify-content:center; color:#3b82f6; font-size:18px;">
          <i class="bi bi-building"></i>
        </div>
        <span style="font-size:11px; background:#eff6ff; color:#3b82f6; padding:3px 8px; border-radius:20px;">Active</span>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:30px; color:#1e293b;"><?= $totalVenues ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Total Venues</p>
    </div>
  </div>

  <div class="col-md-3">
    <div class="rounded-3 p-3 h-100" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #f59e0b;">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div style="background:#fffbeb; width:44px; height:44px; border-radius:10px;
                    display:flex; align-items:center; justify-content:center; color:#f59e0b; font-size:18px;">
          <i class="bi bi-hourglass-split"></i>
        </div>
        <?php if ($pendingRequests > 0): ?>
          <span style="font-size:11px; background:#fffbeb; color:#d97706; padding:3px 8px; border-radius:20px;">Action Needed</span>
        <?php endif; ?>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:30px; color:#1e293b;"><?= $pendingRequests ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Pending Requests</p>
    </div>
  </div>

  <div class="col-md-3">
    <div class="rounded-3 p-3 h-100" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #10b981;">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div style="background:#ecfdf5; width:44px; height:44px; border-radius:10px;
                    display:flex; align-items:center; justify-content:center; color:#10b981; font-size:18px;">
          <i class="bi bi-calendar-check"></i>
        </div>
        <span style="font-size:11px; background:#ecfdf5; color:#059669; padding:3px 8px; border-radius:20px;">Upcoming</span>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:30px; color:#1e293b;"><?= $upcomingEvents ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Upcoming Events</p>
    </div>
  </div>

  <div class="col-md-3">
    <div class="rounded-3 p-3 h-100" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #8b5cf6;">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div style="background:#f5f3ff; width:44px; height:44px; border-radius:10px;
                    display:flex; align-items:center; justify-content:center; color:#8b5cf6; font-size:18px;">
          <i class="bi bi-cash-stack"></i>
        </div>
        <span style="font-size:11px; background:#f5f3ff; color:#7c3aed; padding:3px 8px; border-radius:20px;">This Month</span>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:24px; color:#1e293b;">৳<?= number_format($monthlyRevenue) ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Revenue This Month</p>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="rounded-3 p-3" style="background:#fff; border:1px solid #e2e8f0;">
      <p class="fw-semibold mb-3" style="font-size:13px; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Quick Actions</p>
      <div class="d-flex gap-2 flex-wrap">
        <a href="create_venue.php" class="btn btn-sm" style="background:#eff6ff; color:#3b82f6; border:none; border-radius:8px; padding:8px 16px;">
          <i class="bi bi-plus-circle me-1"></i> Add Venue
        </a>
        <a href="booking_requests.php" class="btn btn-sm" style="background:#fffbeb; color:#d97706; border:none; border-radius:8px; padding:8px 16px;">
          <i class="bi bi-inbox me-1"></i> View Requests
        </a>
        <a href="calendar.php" class="btn btn-sm" style="background:#ecfdf5; color:#059669; border:none; border-radius:8px; padding:8px 16px;">
          <i class="bi bi-calendar3 me-1"></i> Availability Calendar
        </a>
        <a href="pricing.php" class="btn btn-sm" style="background:#f5f3ff; color:#7c3aed; border:none; border-radius:8px; padding:8px 16px;">
          <i class="bi bi-tag me-1"></i> Manage Pricing
        </a>
        <a href="occupancy_report.php" class="btn btn-sm" style="background:#fef2f2; color:#ef4444; border:none; border-radius:8px; padding:8px 16px;">
          <i class="bi bi-bar-chart me-1"></i> Occupancy Report
        </a>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-7">
    <div class="rounded-3 overflow-hidden" style="background:#fff; border:1px solid #e2e8f0;">
      <div class="px-4 py-3 d-flex justify-content-between align-items-center"
           style="border-bottom:1px solid #f1f5f9;">
        <span class="fw-semibold" style="font-size:14px;">
          <i class="bi bi-inbox me-2" style="color:#3b82f6;"></i>Recent Booking Requests
        </span>
        <a href="booking_requests.php" class="btn btn-outline-secondary btn-sm">View All</a>
      </div>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Event</th>
              <th>Organiser</th>
              <th>Venue</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $recentRequests->fetch_assoc()): ?>
            <tr>
              <td><strong><?= htmlspecialchars($row['event_title_preview']) ?></strong></td>
              <td style="font-size:13px;"><?= htmlspecialchars($row['organiser_name']) ?></td>
              <td style="font-size:12px; color:#64748b;"><?= htmlspecialchars($row['venue_name']) ?></td>
              <td>
                <?php if ($row['status'] === 'pending'): ?>
                  <span class="badge" style="background:#fffbeb; color:#d97706; font-size:11px;">Pending</span>
                <?php elseif ($row['status'] === 'approved'): ?>
                  <span class="badge" style="background:#ecfdf5; color:#059669; font-size:11px;">Approved</span>
                <?php else: ?>
                  <span class="badge" style="background:#fef2f2; color:#ef4444; font-size:11px;">Rejected</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-5">
    <div class="rounded-3 overflow-hidden" style="background:#fff; border:1px solid #e2e8f0;">
      <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9;">
        <span class="fw-semibold" style="font-size:14px;">
          <i class="bi bi-calendar-event me-2" style="color:#10b981;"></i>Upcoming Events
        </span>
      </div>
      <div class="p-3">
        <?php
        $hasEvents = false;
        while ($row = $upcomingList->fetch_assoc()):
          $hasEvents = true;
          $daysLeft = (int)ceil((strtotime($row['event_datetime']) - time()) / 86400);
        ?>
        <div class="d-flex align-items-center gap-3 p-2 rounded-2 mb-2"
             style="background:#f8fafc; border:1px solid #f1f5f9;">
          <div style="background: linear-gradient(135deg, #3b82f6, #8b5cf6);
                      width:40px; height:40px; border-radius:10px; flex-shrink:0;
                      display:flex; flex-direction:column; align-items:center;
                      justify-content:center; color:#fff; line-height:1.2;">
            <span style="font-size:14px; font-weight:700;"><?= date('d', strtotime($row['event_datetime'])) ?></span>
            <span style="font-size:9px; opacity:0.85;"><?= date('M', strtotime($row['event_datetime'])) ?></span>
          </div>
          <div style="flex:1; min-width:0;">
            <p class="fw-semibold mb-0" style="font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
              <?= htmlspecialchars($row['title']) ?>
            </p>
            <p class="text-muted mb-0" style="font-size:11px;">
              <?= htmlspecialchars($row['venue_name']) ?>
            </p>
          </div>
          <span style="font-size:11px; background:#eff6ff; color:#3b82f6;
                       padding:2px 8px; border-radius:20px; white-space:nowrap;">
            <?= $daysLeft ?>d left
          </span>
        </div>
        <?php endwhile; ?>

        <?php if (!$hasEvents): ?>
        <div class="text-center py-4">
          <i class="bi bi-calendar-x" style="font-size:32px; color:#e2e8f0;"></i>
          <p class="text-muted mt-2 mb-0" style="font-size:13px;">No upcoming events</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include '../shared/footer.php'; ?>