<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model     = new VenueModel();
$managerId = $_SESSION['user_id'];

$month  = (int)($_GET['month'] ?? date('n'));
$year   = (int)($_GET['year']  ?? date('Y'));
$report = $model->getOccupancyReport($managerId, $month, $year);

$totalDaysInMonth = (int)date('t', mktime(0,0,0,$month,1,$year));
$totalBookedDays  = array_sum(array_column($report, 'booked_days'));
$totalRevenue     = array_sum(array_column($report, 'revenue'));
$totalVenues      = count($report);
$avgUtilisation   = $totalVenues > 0
    ? round(($totalBookedDays / ($totalDaysInMonth * $totalVenues)) * 100)
    : 0;

$activePage = 'occupancy';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Occupancy Report</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      Monthly revenue and utilisation analytics
    </p>
  </div>
  <form method="GET" class="d-flex gap-2 align-items-center">
    <select name="month" class="form-select form-select-sm">
      <?php
      $monthNames = ['January','February','March','April','May','June',
                     'July','August','September','October','November','December'];
      foreach ($monthNames as $i => $name):
      ?>
        <option value="<?= $i+1 ?>" <?= $month == $i+1 ? 'selected' : '' ?>>
          <?= $name ?>
        </option>
      <?php endforeach; ?>
    </select>
    <select name="year" class="form-select form-select-sm">
      <?php for ($y = date('Y'); $y >= date('Y')-2; $y--): ?>
        <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
      <?php endfor; ?>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">
      <i class="bi bi-search"></i>
    </button>
  </form>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="report-metric">
      <div class="value"><?= $totalBookedDays ?></div>
      <div class="label">Total Booked Days</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="report-metric">
      <div class="value">৳<?= number_format($totalRevenue) ?></div>
      <div class="label">Total Revenue</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="report-metric">
      <div class="value" style="color:<?= $avgUtilisation >= 50 ? '#10b981' : '#f59e0b' ?>">
        <?= $avgUtilisation ?>%
      </div>
      <div class="label">Avg Utilisation Rate</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="report-metric">
      <div class="value"><?= $totalVenues ?></div>
      <div class="label">Active Venues</div>
    </div>
  </div>
</div>

<?php if (empty($report)): ?>
  <div class="card p-5 text-center">
    <i class="bi bi-bar-chart" style="font-size:48px; color:#e2e8f0;"></i>
    <h5 class="mt-3 text-muted">No data for this month</h5>
    <p class="text-muted" style="font-size:14px;">No booked dates found for the selected month</p>
  </div>

<?php else: ?>

  <div class="card mb-4">
    <div class="card-header">
      Utilisation Rate —
      <?= $monthNames[$month-1] ?> <?= $year ?>
    </div>
    <div class="card-body">
      <?php foreach ($report as $row):
        $utilisation = round(($row['booked_days'] / $totalDaysInMonth) * 100);
      ?>
      <div class="mb-4">
        <div class="d-flex justify-content-between mb-1" style="font-size:14px;">
          <strong><?= htmlspecialchars($row['name']) ?></strong>
          <span>
            <?= $row['booked_days'] ?>/<?= $totalDaysInMonth ?> days &nbsp;|&nbsp;
            <span style="color:#3b82f6; font-weight:600;">
              ৳<?= number_format($row['revenue']) ?>
            </span>
          </span>
        </div>
        <div class="progress" style="height:10px; border-radius:20px;">
          <div class="progress-bar" role="progressbar"
               style="width:<?= $utilisation ?>%;
                      background:<?= $utilisation >= 50 ? '#10b981' : '#f59e0b' ?>;
                      border-radius:20px;">
          </div>
        </div>
        <small class="text-muted"><?= $utilisation ?>% utilised</small>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header">Venue Breakdown</div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>Venue</th>
            <th>Booked Days</th>
            <th>Available Days</th>
            <th>Revenue (৳)</th>
            <th>Utilisation</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($report as $row):
            $utilisation  = round(($row['booked_days'] / $totalDaysInMonth) * 100);
            $availableDays = $totalDaysInMonth - $row['booked_days'];
          ?>
          <tr>
            <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
            <td><?= $row['booked_days'] ?></td>
            <td><?= $availableDays ?></td>
            <td><?= number_format($row['revenue']) ?></td>
            <td>
              <?php if ($utilisation >= 50): ?>
                <span class="badge bg-success"><?= $utilisation ?>%</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark"><?= $utilisation ?>%</span>
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