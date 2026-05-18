<?php
$pageTitle  = 'Analytics';
$activePage = 'analytics';
include __DIR__ . '/../../layouts/organiser-header.php';

$ticketsSold  = (int)($kpi['tickets_sold']   ?? 0);
$totalRevenue = (float)($kpi['revenue']      ?? 0);
$checkedIn    = (int)($kpi['checked_in']     ?? 0);
$avgRating    = $ratingData['avg_rating']    ?? 0;
$totalReviews = (int)($ratingData['total']   ?? 0);
$checkinRate  = $ticketsSold > 0 ? round($checkedIn/$ticketsSold*100,1) : 0;

$salesMap = [];
foreach ($salesChart as $row) $salesMap[$row['sale_date']] = (int)$row['tickets'];
$labels = []; $ticketData = [];
for ($i = $period-1; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-{$i} days"));
    $labels[]     = date('d M', strtotime($d));
    $ticketData[] = $salesMap[$d] ?? 0;
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
/* ── Page header ── */
.an-header {
  display:flex; justify-content:space-between; align-items:center;
  flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;
}
.an-title { font-size:1.3rem; font-weight:900; color:#0f172a; letter-spacing:-.3px; }
.an-sub   { font-size:.82rem; color:#94a3b8; margin-top:.15rem; }
[data-bs-theme="dark"] .an-title { color:#f1f5f9; }

/* ── Period selector ── */
.period-row { display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; }
.period-btn {
  padding:.42rem .9rem; border-radius:99px; font-size:.8rem;
  font-weight:600; border:1.5px solid #e2e8f0; background:#fff;
  color:#64748b; cursor:pointer; text-decoration:none;
  transition:all .18s; box-shadow:var(--sh-sm);
}
.period-btn:hover:not(.active) { border-color:var(--g700); color:var(--g700); }
.period-btn.active {
  background:linear-gradient(135deg,var(--g700),var(--g500));
  border-color:transparent; color:#fff;
  box-shadow:0 4px 14px rgba(15,110,86,.3);
}
.divider-v { width:1px; height:22px; background:#e2e8f0; margin:0 .15rem; }
.btn-print {
  display:inline-flex; align-items:center; gap:.4rem;
  padding:.42rem .9rem; border-radius:99px; font-size:.8rem;
  font-weight:600; border:1.5px solid #e2e8f0; background:#fff;
  color:#64748b; cursor:pointer; transition:all .18s;
  box-shadow:var(--sh-sm);
}
.btn-print:hover { border-color:var(--g700); color:var(--g700); }
[data-bs-theme="dark"] .period-btn:not(.active) {
  background:#1a2030; border-color:#2d3748; color:#94a3b8;
}
[data-bs-theme="dark"] .btn-print {
  background:#1a2030; border-color:#2d3748; color:#94a3b8;
}
[data-bs-theme="dark"] .divider-v { background:#2d3748; }

/* ── KPI Cards ── */
.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
@media(max-width:900px){ .kpi-grid{ grid-template-columns:repeat(2,1fr); } }

.an-kpi {
  background:#fff; border-radius:16px; padding:1.2rem 1.3rem;
  box-shadow:0 4px 20px rgba(0,0,0,.07),0 1px 3px rgba(0,0,0,.04);
  border:1px solid rgba(0,0,0,.05);
  position:relative; overflow:hidden;
  transition:all .22s cubic-bezier(.4,0,.2,1);
}
.an-kpi::before {
  content:''; position:absolute; top:0; left:0; right:0; height:3px;
  background:var(--kg, linear-gradient(90deg,#0F6E56,#5DCAA5));
}
.an-kpi:hover { transform:translateY(-4px); box-shadow:0 12px 36px rgba(0,0,0,.1); }
.an-kpi-icon {
  width:42px; height:42px; border-radius:12px;
  display:flex; align-items:center; justify-content:center;
  font-size:1.1rem; margin-bottom:.9rem;
  background:linear-gradient(135deg,var(--ic1,#0F6E56),var(--ic2,#5DCAA5));
  box-shadow:0 4px 12px color-mix(in srgb, var(--ic1,#0F6E56) 35%, transparent);
}
.an-kpi-icon i { color:#fff; }
.an-kpi-lbl {
  font-size:.67rem; font-weight:700; text-transform:uppercase;
  letter-spacing:.6px; color:#94a3b8; margin-bottom:.3rem;
}
.an-kpi-val {
  font-size:1.85rem; font-weight:900; color:#0f172a;
  letter-spacing:-.4px; line-height:1.1;
}
.an-kpi-sub { font-size:.73rem; color:#94a3b8; margin-top:.25rem; }

[data-bs-theme="dark"] .an-kpi { background:#1a2030; border-color:rgba(255,255,255,.06); }
[data-bs-theme="dark"] .an-kpi-val { color:#f1f5f9; }
[data-bs-theme="dark"] .an-kpi-lbl { color:#64748b; }

/* ── Chart Cards ── */
.an-chart {
  background:#fff; border-radius:16px;
  box-shadow:0 4px 20px rgba(0,0,0,.07);
  border:1px solid rgba(0,0,0,.05);
  overflow:hidden; transition:box-shadow .25s;
}
.an-chart:hover { box-shadow:0 8px 32px rgba(0,0,0,.1); }
.an-chart-hdr {
  padding:1rem 1.25rem .5rem;
  display:flex; justify-content:space-between; align-items:flex-start;
  border-bottom:1px solid rgba(0,0,0,.05);
}
.an-chart-title { font-size:.9rem; font-weight:700; color:#0f172a; }
.an-chart-sub   { font-size:.73rem; color:#94a3b8; margin-top:.1rem; }
.an-chart-body  { padding:.75rem 1rem 1rem; }

[data-bs-theme="dark"] .an-chart { background:#1a2030; border-color:rgba(255,255,255,.06); }
[data-bs-theme="dark"] .an-chart-hdr { border-color:rgba(255,255,255,.07); }
[data-bs-theme="dark"] .an-chart-title { color:#f1f5f9; }

/* ── Event Performance ── */
.ev-row {
  display:flex; align-items:center; gap:1rem;
  padding:.8rem 0; border-bottom:1px solid #f1f5f9;
  transition:background .15s;
}
.ev-row:last-child { border:none; }
.ev-icon {
  width:38px; height:38px; border-radius:10px; flex-shrink:0;
  background:linear-gradient(135deg,#E1F5EE,#f0fdf4);
  display:flex; align-items:center; justify-content:center;
  color:var(--g700); font-size:1rem;
  border:1px solid #E1F5EE;
}
.ev-name { font-size:.86rem; font-weight:700; color:#0f172a; }
.ev-meta { font-size:.73rem; color:#94a3b8; margin-top:.1rem; }
.ev-revenue { font-size:.95rem; font-weight:800; color:var(--g700); }
.st-pill {
  display:inline-flex; padding:2px 9px; border-radius:99px;
  font-size:.68rem; font-weight:700; margin-top:.2rem;
}
.st-pub  { background:#ECFDF5; color:#065f46; }
.st-dft  { background:#F1F5F9; color:#475569; }
.st-cxl  { background:#FEF2F2; color:#dc2626; }

[data-bs-theme="dark"] .ev-row { border-color:#1e2a3a; }
[data-bs-theme="dark"] .ev-name { color:#f1f5f9; }
[data-bs-theme="dark"] .ev-icon { background:#1e2a3a; border-color:#2d3748; }

/* ── Rating bar ── */
.rating-bar-wrap { display:flex; align-items:center; gap:.6rem; margin-bottom:.35rem; }
.rating-star-lbl { font-size:.75rem; color:#94a3b8; width:30px; flex-shrink:0; text-align:right; }
.rating-bar-track { flex:1; height:7px; background:#f1f5f9; border-radius:99px; overflow:hidden; }
.rating-bar-fill  { height:100%; border-radius:99px; background:linear-gradient(90deg,#f59e0b,#fbbf24); }
.rating-bar-count { font-size:.73rem; color:#94a3b8; width:20px; text-align:right; flex-shrink:0; }
</style>

<!-- ══ PAGE HEADER ══════════════════════════════════════ -->
<div class="an-header">
  <div>
    <div class="an-title">Analytics</div>
    <div class="an-sub">Performance overview across all your events</div>
  </div>
  <div class="period-row">
    <?php foreach([7=>'7 days',30=>'30 days',90=>'90 days'] as $d=>$lbl): ?>
      <a href="/WebtechProject/public/organiser/analytics?days=<?= $d ?>"
         class="period-btn <?= $period==$d?'active':'' ?>"><?= $lbl ?></a>
    <?php endforeach; ?>
    <div class="divider-v"></div>
    <button onclick="window.print()" class="btn-print">
      <i class="bi bi-printer"></i> Print
    </button>
  </div>
</div>

<!-- ══ KPI CARDS ════════════════════════════════════════ -->
<div class="kpi-grid">

  <div class="an-kpi" style="--kg:linear-gradient(90deg,#0F6E56,#5DCAA5);--ic1:#0F6E56;--ic2:#5DCAA5;">
    <div class="an-kpi-icon"><i class="bi bi-ticket-perforated-fill"></i></div>
    <div class="an-kpi-lbl">Tickets Sold</div>
    <div class="an-kpi-val"><?= number_format($ticketsSold) ?></div>
    <div class="an-kpi-sub">all time</div>
  </div>

  <div class="an-kpi" style="--kg:linear-gradient(90deg,#d97706,#f59e0b);--ic1:#d97706;--ic2:#f59e0b;">
    <div class="an-kpi-icon"><i class="bi bi-graph-up-arrow"></i></div>
    <div class="an-kpi-lbl">Total Revenue</div>
    <div class="an-kpi-val" style="color:#d97706;">$<?= number_format($totalRevenue,0) ?></div>
    <div class="an-kpi-sub">from active bookings</div>
  </div>

  <div class="an-kpi" style="--kg:linear-gradient(90deg,#6366f1,#818cf8);--ic1:#6366f1;--ic2:#818cf8;">
    <div class="an-kpi-icon"><i class="bi bi-check2-circle"></i></div>
    <div class="an-kpi-lbl">Checked In</div>
    <div class="an-kpi-val" style="color:#6366f1;"><?= $checkedIn ?></div>
    <div class="an-kpi-sub"><?= $checkinRate ?>% check-in rate</div>
  </div>

  <div class="an-kpi" style="--kg:linear-gradient(90deg,#d97706,#fbbf24);--ic1:#d97706;--ic2:#fbbf24;">
    <div class="an-kpi-icon"><i class="bi bi-star-fill"></i></div>
    <div class="an-kpi-lbl">Avg Rating</div>
    <div class="an-kpi-val" style="color:#d97706;"><?= $avgRating ?: '—' ?></div>
    <div class="an-kpi-sub"><?= $totalReviews ?> review<?= $totalReviews!=1?'s':'' ?></div>
  </div>

</div>

<!-- ══ CHARTS ROW 1 ═════════════════════════════════════ -->
<div class="row g-3 mb-3">
  <div class="col-lg-8">
    <div class="an-chart">
      <div class="an-chart-hdr">
        <div>
          <div class="an-chart-title">Ticket Sales</div>
          <div class="an-chart-sub">Last <?= $period ?> days</div>
        </div>
        <div style="background:var(--g50);border:1px solid var(--g100);border-radius:99px;
                    padding:3px 11px;font-size:.73rem;font-weight:700;color:var(--g700);">
          <i class="bi bi-calendar3 me-1"></i><?= $period ?>d period
        </div>
      </div>
      <div class="an-chart-body">
        <div style="position:relative;height:220px;">
          <canvas id="salesChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="an-chart">
      <div class="an-chart-hdr">
        <div>
          <div class="an-chart-title">Revenue by Tier</div>
          <div class="an-chart-sub">All time breakdown</div>
        </div>
      </div>
      <div class="an-chart-body">
        <div style="position:relative;height:220px;">
          <canvas id="tierChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ══ CHARTS ROW 2 ═════════════════════════════════════ -->
<div class="row g-3">
  <div class="col-lg-5">
    <div class="an-chart">
      <div class="an-chart-hdr">
        <div>
          <div class="an-chart-title">Check-in by Hour</div>
          <div class="an-chart-sub">When attendees arrive</div>
        </div>
      </div>
      <div class="an-chart-body">
        <div style="position:relative;height:210px;">
          <canvas id="hourChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="an-chart" style="height:100%;">
      <div class="an-chart-hdr">
        <div>
          <div class="an-chart-title">Event Performance</div>
          <div class="an-chart-sub">Revenue and check-in per event</div>
        </div>
        <a href="/WebtechProject/public/organiser/events"
           style="font-size:.75rem;font-weight:600;color:var(--g700);
                  text-decoration:none;padding:3px 10px;background:var(--g50);
                  border-radius:99px;border:1px solid var(--g100);">
          View events
        </a>
      </div>
      <div class="an-chart-body">
        <?php if(empty($eventChart)): ?>
          <p style="color:#94a3b8;font-size:.85rem;text-align:center;padding:2rem;">
            No events yet
          </p>
        <?php else: foreach($eventChart as $ev): ?>
          <div class="ev-row">
            <div class="ev-icon"><i class="bi bi-calendar-event-fill"></i></div>
            <div style="flex:1;min-width:0;">
              <div class="ev-name text-truncate">
                <?= htmlspecialchars($ev['title']) ?>
              </div>
              <div class="ev-meta">
                <?= $ev['bookings'] ?> booking<?= $ev['bookings']!=1?'s':'' ?>
                · <?= $ev['checked_in'] ?> checked in
              </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
              <div class="ev-revenue">$<?= number_format($ev['revenue'],0) ?></div>
              <span class="st-pill <?= $ev['status']==='published'?'st-pub':($ev['status']==='draft'?'st-dft':'st-cxl') ?>">
                <?= ucfirst($ev['status']) ?>
              </span>
            </div>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
const chartDefaults = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { display: false },
    tooltip: {
      backgroundColor:'#fff', titleColor:'#111', bodyColor:'#6b7280',
      borderColor:'rgba(0,0,0,.08)', borderWidth:1, padding:10, cornerRadius:10,
    }
  }
};

// Sales Chart
new Chart(document.getElementById('salesChart'), {
  type: 'line',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      data: <?= json_encode($ticketData) ?>,
      borderColor: '#0F6E56',
      backgroundColor: (ctx) => {
        const g = ctx.chart.ctx.createLinearGradient(0,0,0,220);
        g.addColorStop(0,'rgba(15,110,86,.15)');
        g.addColorStop(1,'rgba(15,110,86,0)');
        return g;
      },
      borderWidth: 2.5, tension: 0.4, fill: true,
      pointBackgroundColor: '#fff',
      pointBorderColor: '#0F6E56',
      pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6,
    }]
  },
  options: {
    ...chartDefaults,
    scales: {
      x: { grid:{display:false}, ticks:{maxTicksLimit:8,font:{size:11},color:'#9ca3af'} },
      y: { beginAtZero:true, grid:{color:'rgba(0,0,0,.04)'},
           ticks:{font:{size:11},color:'#9ca3af'} }
    }
  }
});

// Tier Chart
new Chart(document.getElementById('tierChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($tierNames) ?>,
    datasets: [{
      data: <?= json_encode($tierRevenue) ?>,
      backgroundColor: [
        'rgba(55,138,221,.8)', 'rgba(216,90,48,.8)',
        'rgba(127,119,221,.8)', 'rgba(15,110,86,.8)'
      ],
      borderRadius: 8, borderSkipped: false,
    }]
  },
  options: {
    ...chartDefaults,
    scales: {
      x: { grid:{display:false}, ticks:{font:{size:11},color:'#9ca3af'} },
      y: { beginAtZero:true, grid:{color:'rgba(0,0,0,.04)'},
           ticks:{font:{size:11},color:'#9ca3af',callback:v=>'$'+v} }
    }
  }
});

// Hour Chart
new Chart(document.getElementById('hourChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($hourLabels) ?>,
    datasets: [{
      data: <?= json_encode($hourData) ?>,
      backgroundColor: 'rgba(99,102,241,.75)',
      borderRadius: 4, borderSkipped: false,
    }]
  },
  options: {
    ...chartDefaults,
    scales: {
      x: { grid:{display:false}, ticks:{maxTicksLimit:8,font:{size:9},color:'#9ca3af'} },
      y: { beginAtZero:true, grid:{color:'rgba(0,0,0,.04)'},
           ticks:{font:{size:10},color:'#9ca3af'} }
    }
  }
});
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>