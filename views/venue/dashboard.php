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

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Dashboard</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      Welcome back, <?= htmlspecialchars($_SESSION['user_name']) ?>
    </p>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="bi bi-building"></i></div>
      <div class="stat-info">
        <h3><?= $totalVenues ?></h3>
        <p>Total Venues</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon yellow"><i class="bi bi-clock"></i></div>
      <div class="stat-info">
        <h3><?= $pendingRequests ?></h3>
        <p>Pending Requests</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon green"><i class="bi bi-calendar-check"></i></div>
      <div class="stat-info">
        <h3><?= $upcomingEvents ?></h3>
        <p>Upcoming Events</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="bi bi-cash-stack"></i></div>
      <div class="stat-info">
        <h3>৳<?= number_format($monthlyRevenue) ?></h3>
        <p>Revenue This Month</p>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">

  <div class="col-md-7">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Recent Booking Requests</span>
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
              <td><?= htmlspecialchars($row['event_title_preview']) ?></td>
              <td><?= htmlspecialchars($row['organiser_name']) ?></td>
              <td><?= htmlspecialchars($row['venue_name']) ?></td>
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
      <div class="card-header">Upcoming Events</div>
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
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><?= htmlspecialchars($row['venue_name']) ?></td>
              <td><?= date('M d', strtotime($row['event_datetime'])) ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<?php include '../shared/footer.php'; ?>