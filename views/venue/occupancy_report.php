<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model     = new VenueModel();
$managerId = $_SESSION['user_id'];

$month  = (int)($_GET['month'] ?? date('n'));
$year   = (int)($_GET['year']  ?? date('Y'));
$report = $model->getOccupancyReport($managerId, $month, $year);

$monthNames       = ['January','February','March','April','May','June',
                     'July','August','September','October','November','December'];
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

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Occupancy Report</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      Monthly revenue and utilisation analytics
    </p>
  </div>
  <form method="GET" class="d-flex gap-2 align-items-center">
    <select name="month" class="form-select form-select-sm" style="width:130px;">
      <?php foreach ($monthNames as $i => $name): ?>
        <option value="<?= $i+1 ?>" <?= $month == $i+1 ? 'selected' : '' ?>>
          <?= $name ?>
        </option>
      <?php endforeach; ?>
    </select>
    <select name="year" class="form-select form-select-sm" style="width:90px;">
      <?php for ($y = date('Y'); $y >= date('Y')-2; $y--): ?>
        <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
      <?php endfor; ?>
    </select>
    <button type="submit" class="btn btn-primary btn-sm px-3">
      <i class="bi bi-search me-1"></i> Search
    </button>
  </form>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="rounded-3 p-3" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #3b82f6;">
      <div style="background:#eff6ff; width:44px; height:44px; border-radius:10px;
                  display:flex; align-items:center; justify-content:center;
                  color:#3b82f6; font-size:20px; margin-bottom:12px;">
        <i class="bi bi-calendar-check"></i>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:28px; color:#1e293b;"><?= $totalBookedDays ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Total Booked Days</p>
    </div>
  </div>
  <div class="col-md-3">
    <div class="rounded-3 p-3" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #10b981;">
      <div style="background:#ecfdf5; width:44px; height:44px; border-radius:10px;
                  display:flex; align-items:center; justify-content:center;
                  color:#10b981; font-size:20px; margin-bottom:12px;">
        <i class="bi bi-cash-stack"></i>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:22px; color:#1e293b;">৳<?= number_format($totalRevenue) ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Total Revenue</p>
    </div>
  </div>
  <div class="col-md-3">
    <div class="rounded-3 p-3" style="background:#fff; border:1px solid #e2e8f0;
         border-top:3px solid <?= $avgUtilisation >= 50 ? '#10b981' : '#f59e0b' ?>;">
      <div style="background:<?= $avgUtilisation >= 50 ? '#ecfdf5' : '#fffbeb' ?>; width:44px; height:44px;
                  border-radius:10px; display:flex; align-items:center; justify-content:center;
                  color:<?= $avgUtilisation >= 50 ? '#10b981' : '#f59e0b' ?>; font-size:20px; margin-bottom:12px;">
        <i class="bi bi-graph-up"></i>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:28px; color:<?= $avgUtilisation >= 50 ? '#10b981' : '#f59e0b' ?>;">
        <?= $avgUtilisation ?>%
      </h3>
      <p class="text-muted mb-0" style="font-size:13px;">Avg Utilisation Rate</p>
    </div>
  </div>
  <div class="col-md-3">
    <div class="rounded-3 p-3" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #8b5cf6;">
      <div style="background:#f5f3ff; width:44px; height:44px; border-radius:10px;
                  display:flex; align-items:center; justify-content:center;
                  color:#8b5cf6; font-size:20px; margin-bottom:12px;">
        <i class="bi bi-building"></i>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:28px; color:#1e293b;"><?= $totalVenues ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Active Venues</p>
    </div>
  </div>
</div>

<?php if (empty($report)): ?>
  <div class="text-center py-5 rounded-3" style="background:#fff; border:2px dashed #e2e8f0;">
    <i class="bi bi-bar-chart" style="font-size:52px; color:#e2e8f0;"></i>
    <h5 class="mt-3 fw-semibold">No data for this month</h5>
    <p class="text-muted" style="font-size:14px;">No booked dates found for <?= $monthNames[$month-1] ?> <?= $year ?></p>
  </div>

<?php else: ?>

  <!-- Chart + Utilisation -->
  <div class="row g-3 mb-4">

    <!-- Bar Chart -->
    <div class="col-md-7">
      <div class="rounded-3 p-4" style="background:#fff; border:1px solid #e2e8f0;">
        <h6 class="fw-semibold mb-4" style="font-size:14px;">
          <i class="bi bi-bar-chart me-2" style="color:#3b82f6;"></i>
          Revenue by Venue — <?= $monthNames[$month-1] ?> <?= $year ?>
        </h6>
        <canvas id="revenueChart" height="220"></canvas>
      </div>
    </div>

    <!-- Utilisation Bars -->
    <div class="col-md-5">
      <div class="rounded-3 p-4" style="background:#fff; border:1px solid #e2e8f0; height:100%;">
        <h6 class="fw-semibold mb-4" style="font-size:14px;">
          <i class="bi bi-activity me-2" style="color:#10b981;"></i>
          Utilisation Rate
        </h6>
        <?php foreach ($report as $row):
          $utilisation = round(($row['booked_days'] / $totalDaysInMonth) * 100);
          $color = $utilisation >= 70 ? '#10b981' : ($utilisation >= 40 ? '#3b82f6' : '#f59e0b');
          $shortName = strlen($row['name']) > 20 ? substr($row['name'], 0, 20).'...' : $row['name'];
        ?>
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1">
            <span style="font-size:12px; font-weight:600; color:#1e293b;"><?= htmlspecialchars($shortName) ?></span>
            <span style="font-size:12px; color:<?= $color ?>; font-weight:700;"><?= $utilisation ?>%</span>
          </div>
          <div style="background:#f1f5f9; border-radius:20px; height:8px; overflow:hidden;">
            <div style="width:<?= $utilisation ?>%; background:<?= $color ?>;
                        height:100%; border-radius:20px; transition:width 1s ease;"></div>
          </div>
          <div class="d-flex justify-content-between mt-1">
            <span style="font-size:11px; color:#94a3b8;"><?= $row['booked_days'] ?>/<?= $totalDaysInMonth ?> days</span>
            <span style="font-size:11px; color:#64748b;">৳<?= number_format($row['revenue']) ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="rounded-3 overflow-hidden" style="background:#fff; border:1px solid #e2e8f0;">
    <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9; background:#fafafa;">
      <h6 class="fw-semibold mb-0" style="font-size:14px;">
        <i class="bi bi-table me-2" style="color:#8b5cf6;"></i>
        Venue Breakdown — <?= $monthNames[$month-1] ?> <?= $year ?>
      </h6>
    </div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr style="background:#f8fafc;">
            <th style="font-size:12px; color:#64748b; font-weight:600;">#</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">VENUE</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">BOOKED</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">AVAILABLE</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">REVENUE</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">UTILISATION</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($report as $i => $row):
            $utilisation   = round(($row['booked_days'] / $totalDaysInMonth) * 100);
            $availableDays = $totalDaysInMonth - $row['booked_days'];
            $badgeBg = $utilisation >= 70 ? '#ecfdf5' : ($utilisation >= 40 ? '#eff6ff' : '#fffbeb');
            $badgeColor = $utilisation >= 70 ? '#059669' : ($utilisation >= 40 ? '#2563eb' : '#d97706');
          ?>
          <tr>
            <td style="font-size:13px; color:#94a3b8;"><?= $i+1 ?></td>
            <td>
              <strong style="font-size:13px;"><?= htmlspecialchars($row['name']) ?></strong>
            </td>
            <td>
              <span style="background:#eff6ff; color:#3b82f6; padding:2px 8px;
                           border-radius:20px; font-size:12px; font-weight:600;">
                <?= $row['booked_days'] ?> days
              </span>
            </td>
            <td style="font-size:13px; color:#64748b;"><?= $availableDays ?> days</td>
            <td>
              <strong style="color:#10b981; font-size:13px;">৳<?= number_format($row['revenue']) ?></strong>
            </td>
            <td>
              <span style="background:<?= $badgeBg ?>; color:<?= $badgeColor ?>;
                           padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                <?= $utilisation ?>%
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr style="background:#f8fafc; border-top:2px solid #e2e8f0;">
            <td colspan="2"><strong style="font-size:13px;">Total</strong></td>
            <td>
              <span style="background:#eff6ff; color:#3b82f6; padding:2px 8px;
                           border-radius:20px; font-size:12px; font-weight:600;">
                <?= $totalBookedDays ?> days
              </span>
            </td>
            <td style="font-size:13px; color:#64748b;">
              <?= ($totalDaysInMonth * $totalVenues) - $totalBookedDays ?> days
            </td>
            <td><strong style="color:#10b981; font-size:13px;">৳<?= number_format($totalRevenue) ?></strong></td>
            <td>
              <span style="background:#eff6ff; color:#3b82f6;
                           padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600;">
                <?= $avgUtilisation ?>% avg
              </span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

<?php endif; ?>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($report)): ?>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?= implode(',', array_map(fn($r) => '"'.addslashes(substr($r['name'],0,15)).'"', $report)) ?>],
        datasets: [{
            label: 'Revenue (৳)',
            data: [<?= implode(',', array_column($report, 'revenue')) ?>],
            backgroundColor: [
                '#3b82f6','#10b981','#f59e0b','#8b5cf6','#ef4444','#06b6d4','#84cc16','#f97316'
            ],
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => '৳' + ctx.raw.toLocaleString()
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' },
                ticks: {
                    callback: val => '৳' + val.toLocaleString(),
                    font: { size: 11 }
                }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 11 } }
            }
        }
    }
});
<?php endif; ?>
</script>

<?php include '../shared/footer.php'; ?>