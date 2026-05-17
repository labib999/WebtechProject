<?php
$pageTitle  = 'Analytics';
$activePage = 'analytics';
include __DIR__ . '/../../layouts/organiser-header.php';

$ticketsSold  = (int)($kpi['tickets_sold']   ?? 0);
$totalRevenue = (float)($kpi['revenue']      ?? 0);
$checkedIn    = (int)($kpi['checked_in']     ?? 0);
$avgRating    = $ratingData['avg_rating']    ?? 0;
$totalReviews = (int)($ratingData['total']   ?? 0);

$salesMap = [];
foreach ($salesChart as $row) {
    $salesMap[$row['sale_date']] = ['tickets'=>(int)$row['tickets']];
}
$labels = []; $ticketData = [];
for ($i = $period-1; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-{$i} days"));
    $labels[]     = date('d M', strtotime($d));
    $ticketData[] = $salesMap[$d]['tickets'] ?? 0;
}

$hourMap = [];
foreach ($hourChart as $h) $hourMap[(int)$h['hr']] = (int)$h['cnt'];
$hourLabels = []; $hourData = [];
for ($h = 0; $h < 24; $h++) {
    $hourLabels[] = str_pad($h,2,'0',STR_PAD_LEFT).':00';
    $hourData[]   = $hourMap[$h] ?? 0;
}

$tierNames   = array_column($tierChart, 'tier_name');
$tierRevenue = array_map('floatval', array_column($tierChart, 'revenue'));
?>
<style>
  .an-card { background:#fff; border-radius:14px; padding:1.25rem 1.5rem;
    box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 14px rgba(0,0,0,0.04);
    border:1px solid rgba(0,0,0,0.05); }
  .kpi-lbl { font-size:.7rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.5px; color:#9ca3af; margin-bottom:.3rem; }
  .kpi-val { font-size:1.6rem; font-weight:800; color:#111; line-height:1.1; }
  .kpi-sub { font-size:.75rem; color:#9ca3af; margin-top:.2rem; }
  .period-btn { padding:.35rem .9rem; border-radius:20px; font-size:.8rem; font-weight:600;
    border:1.5px solid #e5e7eb; background:none; color:#6b7280;
    cursor:pointer; text-decoration:none; transition:all .15s; }
  .period-btn.active { background:#0F6E56; color:#fff; border-color:#0F6E56; }
  .period-btn:hover:not(.active) { border-color:#0F6E56; color:#0F6E56; }
  .btn-print { padding:.35rem .85rem; border-radius:20px; font-size:.8rem; font-weight:600;
    border:1.5px solid #e5e7eb; background:#fff; color:#374151;
    cursor:pointer; display:flex; align-items:center; gap:.4rem; transition:all .15s; }
  .btn-print:hover { border-color:#0F6E56; color:#0F6E56; }
  .ev-row { display:flex; align-items:center; gap:1rem; padding:.75rem 0;
    border-bottom:1px solid #f3f4f6; }
  .ev-row:last-child { border-bottom:none; }
  [data-bs-theme="dark"] .an-card { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .kpi-val  { color:#f3f4f6; }
  [data-bs-theme="dark"] .ev-row   { border-color:#374151; }
  [data-bs-theme="dark"] .btn-print { background:#1f2937; border-color:#374151; color:#d1d5db; }
</style>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1.05rem;">Analytics</h4>
    <p class="text-muted mb-0" style="font-size:.8rem;">Performance overview across all your events</p>
  </div>
  <div class="d-flex align-items-center gap-2">
    <?php foreach ([7=>'7 days',30=>'30 days',90=>'90 days'] as $d=>$lbl): ?>
      <a href="/WebtechProject/public/organiser/analytics?days=<?= $d ?>"
         class="period-btn <?= $period==$d?'active':'' ?>"><?= $lbl ?></a>
    <?php endforeach; ?>
    <div style="width:1px;height:20px;background:#e5e7eb;margin:0 .1rem;"></div>
    <button onclick="window.print()" class="btn-print">
      <i class="bi bi-printer"></i> Print
    </button>
  </div>
</div>

<!-- KPIs -->
<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="an-card">
      <div class="kpi-lbl"><i class="bi bi-ticket-perforated me-1"></i>Tickets Sold</div>
      <div class="kpi-val"><?= number_format($ticketsSold) ?></div>
      <div class="kpi-sub">all time</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="an-card">
      <div class="kpi-lbl"><i class="bi bi-graph-up me-1"></i>Total Revenue</div>
      <div class="kpi-val" style="color:#0F6E56;">$<?= number_format($totalRevenue,2) ?></div>
      <div class="kpi-sub">active bookings</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="an-card">
      <div class="kpi-lbl"><i class="bi bi-check2-circle me-1"></i>Checked In</div>
      <div class="kpi-val" style="color:#4f46e5;"><?= $checkedIn ?></div>
      <div class="kpi-sub"><?= $ticketsSold>0?round($checkedIn/$ticketsSold*100,1):0 ?>% rate</div>
    </div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="an-card">
      <div class="kpi-lbl"><i class="bi bi-star-fill me-1"></i>Avg Rating</div>
      <div class="kpi-val" style="color:#d97706;"><?= $avgRating ?: '—' ?></div>
      <div class="kpi-sub"><?= $totalReviews ?> review<?= $totalReviews!=1?'s':'' ?></div>
    </div>
  </div>
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="an-card">
      <h6 style="font-size:.88rem;font-weight:700;margin-bottom:.25rem;">Ticket Sales</h6>
      <p style="font-size:.75rem;color:#9ca3af;margin-bottom:1rem;">Last <?= $period ?> days</p>
      <div style="position:relative;height:220px;"><canvas id="salesChart"></canvas></div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="an-card">
      <h6 style="font-size:.88rem;font-weight:700;margin-bottom:.25rem;">Revenue by Tier</h6>
      <p style="font-size:.75rem;color:#9ca3af;margin-bottom:1rem;">All time</p>
      <div style="position:relative;height:220px;"><canvas id="tierChart"></canvas></div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="an-card">
      <h6 style="font-size:.88rem;font-weight:700;margin-bottom:.25rem;">Check-in by Hour</h6>
      <p style="font-size:.75rem;color:#9ca3af;margin-bottom:1rem;">When attendees arrive</p>
      <div style="position:relative;height:200px;"><canvas id="hourChart"></canvas></div>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="an-card">
      <h6 style="font-size:.88rem;font-weight:700;margin-bottom:1rem;">Event Performance</h6>
      <?php if (empty($eventChart)): ?>
        <p style="color:#9ca3af;font-size:.85rem;">No events yet.</p>
      <?php else: foreach ($eventChart as $ev): ?>
        <div class="ev-row">
          <div style="flex:1;min-width:0;">
            <div style="font-size:.85rem;font-weight:600;color:#111;" class="text-truncate">
              <?= htmlspecialchars($ev['title']) ?>
            </div>
            <div style="font-size:.74rem;color:#9ca3af;margin-top:.1rem;">
              <?= $ev['bookings'] ?> booking<?= $ev['bookings']!=1?'s':'' ?> ·
              <?= $ev['checked_in'] ?> checked in
            </div>
          </div>
          <div style="text-align:right;flex-shrink:0;">
            <div style="font-size:.9rem;font-weight:700;color:#0F6E56;">
              $<?= number_format($ev['revenue'],0) ?>
            </div>
            <span style="display:inline-block;padding:2px 8px;border-radius:12px;
              font-size:.68rem;font-weight:600;
              <?= $ev['status']==='published'?'background:#ECFDF5;color:#065f46;':'background:#F3F4F6;color:#6b7280;' ?>">
              <?= ucfirst($ev['status']) ?>
            </span>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</div>

<script>
new Chart(document.getElementById('salesChart'), {
  type:'line',
  data:{ labels:<?= json_encode($labels) ?>,
    datasets:[{ data:<?= json_encode($ticketData) ?>,
      borderColor:'#0F6E56', backgroundColor:'rgba(15,110,86,0.08)',
      borderWidth:2.5, tension:0.35, fill:true,
      pointBackgroundColor:'#0F6E56', pointRadius:3 }]
  },
  options:{ responsive:true, maintainAspectRatio:false,
    plugins:{legend:{display:false}},
    scales:{ x:{grid:{display:false},ticks:{maxTicksLimit:8,font:{size:10}}},
             y:{beginAtZero:true,grid:{color:'#f3f4f6'},ticks:{font:{size:10}}} }
  }
});
new Chart(document.getElementById('tierChart'), {
  type:'bar',
  data:{ labels:<?= json_encode($tierNames) ?>,
    datasets:[{ data:<?= json_encode($tierRevenue) ?>,
      backgroundColor:['#378ADD','#D85A30','#7F77DD','#0F6E56'], borderRadius:5 }]
  },
  options:{ responsive:true, maintainAspectRatio:false,
    plugins:{legend:{display:false}},
    scales:{ x:{grid:{display:false},ticks:{font:{size:10}}},
             y:{beginAtZero:true,ticks:{callback:v=>'$'+v,font:{size:10}},grid:{color:'#f3f4f6'}} }
  }
});
new Chart(document.getElementById('hourChart'), {
  type:'bar',
  data:{ labels:<?= json_encode($hourLabels) ?>,
    datasets:[{ data:<?= json_encode($hourData) ?>,
      backgroundColor:'rgba(79,70,229,0.7)', borderRadius:3 }]
  },
  options:{ responsive:true, maintainAspectRatio:false,
    plugins:{legend:{display:false}},
    scales:{ x:{grid:{display:false},ticks:{maxTicksLimit:8,font:{size:9}}},
             y:{beginAtZero:true,ticks:{font:{size:10}},grid:{color:'#f3f4f6'}} }
  }
});
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>