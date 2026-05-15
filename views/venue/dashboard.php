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

<!-- Topbar -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Dashboard</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?> 👋
    </p>
  </div>
  <div style="font-size:13px; color:#64748b;">
    <i class="bi bi-calendar3 me-1"></i>
    <?= date('l, d F Y') ?>
  </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="p-4 rounded-3 text-white d-flex align-items-center gap-3"
         style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
      <div style="background:rgba(255,255,255,0.2); width:52px; height:52px; border-radius:12px;
                  display:flex; align-items:center; justify-content:center; font-size:22px;">
        <i class="bi bi-building"></i>
      </div>
      <div>
        <h3 class="fw-bold mb-0" style="font-size:28px;"><?= $totalVenues ?></h3>
        <p class="mb-0" style="font-size:13px; opacity:0.85;">Total Venues</p>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="p-4 rounded-3 text-white d-flex align-items-center gap-3"
         style="background: linear-gradient(135deg, #f59e0b, #d97706);">
      <div style="background:rgba(255,255,255,0.2); width:52px; height:52px; border-radius:12px;
                  display:flex; align-items:center; justify-content:center; font-size:22px;">
        <i class="bi bi-clock"></i>
      </div>
      <div>
        <h3 class="fw-bold mb-0" style="font-size:28px;"><?= $pendingRequests ?></h3>
        <p class="mb-0" style="font-size:13px; opacity:0.85;">Pending Requests</p>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="p-4 rounded-3 text-white d-flex align-items-center gap-3"
         style="background: linear-gradient(135deg, #10b981, #059669);">
      <div style="background:rgba(255,255,255,0.2); width:52px; height:52px; border-radius:12px;
                  display:flex; align-items:center; justify-content:center; font-size:22px;">
        <i class="bi bi-calendar-check"></i>
      </div>
      <div>
        <h3 class="fw-bold mb-0" style="font-size:28px;"><?= $upcomingEvents ?></h3>
        <p class="mb-0" style="font-size:13px; opacity:0.85;">Upcoming Events</p>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="p-4 rounded-3 text-white d-flex align-items-center gap-3"
         style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
      <div style="background:rgba(255,255,255,0.2); width:52px; height:52px; border-radius:12px;
                  display:flex; align-items:center; justify-content:center; font-size:22px;">
        <i class="bi bi-cash-stack"></i>
      </div>
      <div>
        <h3 class="fw-bold mb-0" style="font-size:28px;">৳<?= number_format($monthlyRevenue) ?></h3>
        <p class="mb-0" style="font-size:13px; opacity:0.85;">Revenue This Month</p>
      </div>
    </div>
  </div>
</div>

<!-- Tables -->
<div class="row g-3">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">
          <i class="bi bi-inbox me-2"></i>Recent Booking Requests
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
              <td><?= htmlspecialchars($row['organiser_name']) ?></td>
              <td style="font-size:13px;"><?= htmlspecialchars($row['venue_name']) ?></td>
              <td>
                <?php if ($row['status'] === 'pending'): ?>
                  <span class="badge bg-warning text-dark">Pending</span>
                <?php elseif ($row['status'] === 'approved'): ?>
                  <span class="badge bg-success">Approved</span>
                <?php else: ?>
                  <span class="badge bg-danger">Rejected</span>
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
    <div class="card">
      <div class="card-header fw-semibold">
        <i class="bi bi-calendar-event me-2"></i>Upcoming Events
      </div>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Event</th>
              <th>Venue</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $upcomingList->fetch_assoc()): ?>
            <tr>
              <td><strong><?= htmlspecialchars($row['title']) ?></strong></td>
              <td style="font-size:13px;"><?= htmlspecialchars($row['venue_name']) ?></td>
              <td>
                <span class="badge bg-primary">
                  <?= date('M d', strtotime($row['event_datetime'])) ?>
                </span>
              </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($upcomingList->num_rows === 0): ?>
            <tr>
              <td colspan="3" class="text-center text-muted py-3" style="font-size:13px;">
                No upcoming events
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../shared/footer.php'; ?>