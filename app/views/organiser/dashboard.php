<?php
$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
$hour       = (int)date('H');
$greeting   = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
include __DIR__ . '/../layouts/organiser-header.php';
?>
<style>
  .welcome-card {
    background: linear-gradient(135deg, #063D30 0%, #0F6E56 60%, #1a8a6e 100%);
    border-radius: 16px; padding: 1.75rem 2rem; margin-bottom: 1.5rem;
    color: #fff; position: relative; overflow: hidden;
  }
  .welcome-card::before {
    content: ''; position: absolute; width: 350px; height: 350px;
    border-radius: 50%; background: rgba(255,255,255,0.06);
    top: -140px; right: -80px; pointer-events: none;
  }
  .welcome-card::after {
    content: ''; position: absolute; width: 200px; height: 200px;
    border-radius: 50%; background: rgba(255,255,255,0.04);
    bottom: -60px; left: 40%; pointer-events: none;
  }
  .kpi-card {
    background: #fff; border-radius: 14px; padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 16px rgba(0,0,0,0.05);
    transition: transform .18s ease, box-shadow .18s ease;
    border: 1px solid rgba(0,0,0,0.05); height: 100%;
  }
  .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,0.1); }
  .kpi-icon {
    width: 48px; height: 48px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
  }
  .kpi-label { font-size: .7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .6px; color: #9ca3af; margin-bottom: .2rem; }
  .kpi-value { font-size: 1.65rem; font-weight: 800; color: #111; line-height: 1.1; }
  .kpi-sub   { font-size: .78rem; color: #6b7280; margin-top: .2rem; }

  .qa-btn {
    display: flex; align-items: center; gap: .6rem;
    padding: .65rem 1.1rem; border-radius: 10px; font-size: .84rem;
    font-weight: 600; text-decoration: none; transition: all .16s;
    border: 1.5px solid transparent; white-space: nowrap;
  }
  .qa-primary { background: #0F6E56; color: #fff; border-color: #0F6E56; }
  .qa-primary:hover { background: #063D30; color: #fff; }
  .qa-outline { background: #fff; color: #0F6E56; border-color: #0F6E56; }
  .qa-outline:hover { background: #E1F5EE; color: #063D30; }
  .qa-warn   { background: #fff; color: #b45309; border-color: #d97706; }
  .qa-warn:hover { background: #FFFBEB; }

  .chart-card {
    background: #fff; border-radius: 14px; padding: 1.4rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 16px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.05);
  }
  .chart-card h6 { font-size: .88rem; font-weight: 700; color: #111; margin-bottom: .15rem; }
  .chart-card .ch-sub { font-size: .75rem; color: #9ca3af; margin-bottom: 1rem; }

  .bk-table { width: 100%; border-collapse: collapse; font-size: .845rem; }
  .bk-table th {
    padding: .6rem 1rem; font-size: .7rem; font-weight: 700; letter-spacing: .5px;
    text-transform: uppercase; color: #9ca3af; border-bottom: 1px solid #f3f4f6;
    background: #fafafa;
  }
  .bk-table td { padding: .7rem 1rem; border-bottom: 1px solid #f9fafb; vertical-align: middle; }
  .bk-table tr:last-child td { border-bottom: none; }
  .bk-table tr:hover td { background: #fafffe; }

  .ev-item { display: flex; align-items: flex-start; gap: .75rem; padding: .75rem 0;
    border-bottom: 1px solid #f3f4f6; }
  .ev-item:last-child { border-bottom: none; padding-bottom: 0; }
  .ev-icon { width: 38px; height: 38px; background: #E1F5EE; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    color: #0F6E56; font-size: 1rem; flex-shrink: 0; }

  .pill { display: inline-block; padding: 2px 9px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
  .pill-gen   { background: #EFF6FF; color: #1d4ed8; }
  .pill-vip   { background: #FFF7ED; color: #c2410c; }
  .pill-early { background: #F5F3FF; color: #6d28d9; }
  .pill-ok    { background: #ECFDF5; color: #065f46; }
  .pill-pend  { background: #FFFBEB; color: #92400e; }
  .pill-ref   { background: #FEF2F2; color: #991b1b; }

  .section-card {
    background: #fff; border-radius: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 16px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.05); overflow: hidden;
  }
  .section-hdr {
    display: flex; justify-content: space-between; align-items: center;
    padding: 1rem 1.25rem .5rem; border-bottom: 1px solid #f3f4f6;
  }
  .section-hdr h6 { font-size: .9rem; font-weight: 700; margin: 0; }
  .view-all {
    font-size: .78rem; font-weight: 600; color: #0F6E56;
    text-decoration: none; padding: .3rem .75rem; border-radius: 8px;
    background: #E1F5EE; transition: background .15s;
  }
  .view-all:hover { background: #d0eddf; }
</style>

<!-- ══ Welcome hero ══ -->
<div class="welcome-card mb-4">
  <div class="d-flex justify-content-between align-items-start flex-wrap gap-3" style="position:relative;z-index:1;">
    <div>
      <p style="font-size:.85rem;opacity:.75;margin-bottom:.3rem;">
        <i class="bi bi-calendar3 me-1"></i><?= date('l, d F Y') ?>
      </p>
      <h2 style="font-size:1.6rem;font-weight:800;margin-bottom:.3rem;">
        <?= $greeting ?>, <?= htmlspecialchars(explode(' ', Auth::userName())[0]) ?> 👋
      </h2>
      <p style="opacity:.8;margin:0;font-size:.9rem;">
        Here is your organiser overview.
        <?php if ($pendingRefunds > 0): ?>
          <span style="background:rgba(239,68,68,.2);color:#fca5a5;padding:2px 10px;border-radius:20px;font-size:.8rem;margin-left:.5rem;">
            <i class="bi bi-exclamation-circle me-1"></i><?= $pendingRefunds ?> refund<?= $pendingRefunds != 1 ? 's' : '' ?> need attention
          </span>
        <?php endif; ?>
      </p>
    </div>
    <div class="d-flex gap-4 flex-wrap" style="position:relative;z-index:1;">
      <div style="text-align:right;">
        <div style="font-size:1.4rem;font-weight:800;"><?= number_format($ticketsSold) ?></div>
        <div style="font-size:.75rem;opacity:.65;">Tickets sold</div>
      </div>
      <div style="text-align:right;">
        <div style="font-size:1.4rem;font-weight:800;">$<?= number_format($totalRevenue, 0) ?></div>
        <div style="font-size:.75rem;opacity:.65;">Revenue</div>
      </div>
      <div style="text-align:right;">
        <div style="font-size:1.4rem;font-weight:800;"><?= $checkinRate ?>%</div>
        <div style="font-size:.75rem;opacity:.65;">Check-in rate</div>
      </div>
    </div>
  </div>
</div>

<!-- ══ Quick actions ══ -->
<div class="d-flex flex-wrap gap-2 mb-4">
  <a href="/WebtechProject/public/organiser/events/create" class="qa-btn qa-primary">
    <i class="bi bi-plus-circle-fill"></i> Create Event
  </a>
  <a href="/WebtechProject/public/organiser/checkin" class="qa-btn qa-outline">
    <i class="bi bi-qr-code-scan"></i> Live Check-in
  </a>
  <a href="/WebtechProject/public/organiser/refunds" class="qa-btn qa-warn">
    <i class="bi bi-receipt-cutoff"></i> Refund Requests
    <?php if ($pendingRefunds > 0): ?>
      <span style="background:#ef4444;color:#fff;border-radius:12px;padding:1px 7px;font-size:.7rem;">
        <?= $pendingRefunds ?>
      </span>
    <?php endif; ?>
  </a>
  <a href="/WebtechProject/public/organiser/announcements" class="qa-btn qa-outline">
    <i class="bi bi-megaphone-fill"></i> Post Announcement
  </a>
</div>

<!-- ══ KPI Cards ══ -->
<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="kpi-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="kpi-icon" style="background:#E1F5EE;">
          <i class="bi bi-ticket-perforated-fill" style="color:#0F6E56;"></i>
        </div>
        <span style="font-size:.72rem;color:#0F6E56;background:#E1F5EE;padding:2px 8px;border-radius:12px;font-weight:600;">
          <?= $publishedEvents ?> live
        </span>
      </div>
      <div class="kpi-label">Tickets Sold</div>
      <div class="kpi-value"><?= number_format($ticketsSold) ?></div>
      <div class="kpi-sub"><?= $totalEvents ?> event<?= $totalEvents != 1 ? 's' : '' ?> total</div>
    </div>
  </div>

  <div class="col-6 col-xl-3">
    <div class="kpi-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="kpi-icon" style="background:#FFFBEB;">
          <i class="bi bi-graph-up-arrow" style="color:#d97706;"></i>
        </div>
      </div>
      <div class="kpi-label">Total Revenue</div>
      <div class="kpi-value">$<?= number_format($totalRevenue, 2) ?></div>
      <div class="kpi-sub">from active bookings</div>
    </div>
  </div>

  <div class="col-6 col-xl-3">
    <div class="kpi-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="kpi-icon" style="background:#EEF2FF;">
          <i class="bi bi-qr-code-scan" style="color:#4f46e5;"></i>
        </div>
        <span style="font-size:.72rem;font-weight:700;color:#4f46e5;"><?= $checkinRate ?>%</span>
      </div>
      <div class="kpi-label">Checked In</div>
      <div class="kpi-value"><?= $totalCheckedIn ?> <span style="font-size:1rem;font-weight:500;color:#9ca3af;">/ <?= $totalActive ?></span></div>
      <div class="progress mt-2" style="height:5px;border-radius:3px;background:#EEF2FF;">
        <div class="progress-bar" style="width:<?= $checkinRate ?>%;background:#4f46e5;border-radius:3px;"></div>
      </div>
    </div>
  </div>

  <div class="col-6 col-xl-3">
    <div class="kpi-card">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="kpi-icon" style="background:#FEF9E7;">
          <i class="bi bi-star-fill" style="color:#d97706;"></i>
        </div>
        <?php if ($reviewCount > 0): ?>
          <span style="font-size:.72rem;color:#d97706;background:#FEF9E7;padding:2px 8px;border-radius:12px;font-weight:600;">
            <?= $reviewCount ?> reviews
          </span>
        <?php endif; ?>
      </div>
      <div class="kpi-label">Avg Rating</div>
      <div class="kpi-value"><?= $avgRating ?: '—' ?></div>
      <div class="kpi-sub">
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <i class="bi bi-star<?= $i <= round($avgRating) ? '-fill' : '' ?>"
             style="color:<?= $i <= round($avgRating) ? '#d97706' : '#e5e7eb' ?>;font-size:.65rem;"></i>
        <?php endfor; ?>
      </div>
    </div>
  </div>
</div>

<!-- ══ Charts ══ -->
<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="chart-card">
      <h6>Ticket sales</h6>
      <p class="ch-sub">Daily sales volume over the last 7 days</p>
      <div style="position:relative; height:220px;">
        <canvas id="salesChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="chart-card">
      <h6>Revenue by tier</h6>
      <p class="ch-sub">Total revenue breakdown per ticket type</p>
     <div style="position:relative; height:220px;">
        <canvas id="tierChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- ══ Bookings + Events ══ -->
<div class="row g-3">
  <div class="col-lg-8">
    <div class="section-card">
      <div class="section-hdr">
        <h6><i class="bi bi-ticket-perforated me-2" style="color:#0F6E56;"></i>Recent bookings</h6>
        <a href="/WebtechProject/public/organiser/bookings" class="view-all">View all</a>
      </div>
      <div class="table-responsive">
        <table class="bk-table">
          <thead>
            <tr>
              <th class="ps-4">Attendee</th>
              <th>Tier</th>
              <th>Ticket code</th>
              <th>Paid</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($recentBookings)): ?>
              <tr><td colspan="5" class="text-center py-4 text-muted">No bookings yet</td></tr>
            <?php else: foreach ($recentBookings as $b): ?>
            <tr>
              <td class="ps-4 fw-semibold"><?= htmlspecialchars($b['attendee_name']) ?></td>
              <td>
                <?php
                  $tierLower = strtolower($b['tier_name']);
                  $pillClass = str_contains($tierLower,'vip') ? 'pill-vip'
                    : (str_contains($tierLower,'early') ? 'pill-early' : 'pill-gen');
                ?>
                <span class="pill <?= $pillClass ?>"><?= htmlspecialchars($b['tier_name']) ?></span>
              </td>
              <td><code style="font-size:.78rem;color:#0F6E56;"><?= htmlspecialchars($b['ticket_code']) ?></code></td>
              <td class="fw-semibold">$<?= number_format($b['total_price'], 2) ?></td>
              <td>
                <?php if ($b['checked_in']): ?>
                  <span class="pill pill-ok"><i class="bi bi-check-circle-fill me-1"></i>Checked in</span>
                <?php elseif ($b['status'] === 'refunded'): ?>
                  <span class="pill pill-ref"><i class="bi bi-x-circle-fill me-1"></i>Refunded</span>
                <?php else: ?>
                  <span class="pill pill-pend"><i class="bi bi-clock me-1"></i>Pending</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="section-card h-100">
      <div class="section-hdr">
        <h6><i class="bi bi-calendar-event me-2" style="color:#0F6E56;"></i>My events</h6>
        <a href="/WebtechProject/public/organiser/events" class="view-all">View all</a>
      </div>
      <div class="p-3">
        <?php if (empty($myEvents)): ?>
          <p class="text-muted text-center py-3 mb-0">No events yet</p>
        <?php else: foreach ($myEvents as $ev): ?>
        <div class="ev-item">
          <div class="ev-icon"><i class="bi bi-calendar-event"></i></div>
          <div style="flex:1;min-width:0;">
            <div class="fw-semibold text-truncate" style="font-size:.855rem;">
              <?= htmlspecialchars($ev['title']) ?>
            </div>
            <div style="font-size:.75rem;color:#6b7280;margin-top:.15rem;">
              <?= date('d M Y', strtotime($ev['event_datetime'])) ?>
              &middot; <?= $ev['bookings_count'] ?> booking<?= $ev['bookings_count'] != 1 ? 's' : '' ?>
            </div>
            <span class="pill mt-1 <?= $ev['status'] === 'published' ? 'pill-ok' : '' ?>"
                  style="<?= $ev['status'] !== 'published' ? 'background:#F3F4F6;color:#6b7280;' : '' ?>">
              <?= ucfirst($ev['status']) ?>
            </span>
          </div>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</div>

<?php
$salesMap     = array_column($salesChart, 'tickets', 'sale_date');
$last7Labels  = [];
$last7Tickets = [];
for ($i = 6; $i >= 0; $i--) {
    $date           = date('Y-m-d', strtotime("-{$i} days"));
    $last7Labels[]  = date('D d', strtotime($date));
    $last7Tickets[] = (int)($salesMap[$date] ?? 0);
}
$tierNames   = array_column($tierChart, 'tier_name');
$tierRevenue = array_map('floatval', array_column($tierChart, 'revenue'));
?>
<script>
new Chart(document.getElementById('salesChart'), {
  type: 'line',
  data: {
    labels: <?= json_encode($last7Labels) ?>,
    datasets: [{
      data: <?= json_encode($last7Tickets) ?>,
      borderColor: '#0F6E56',
      backgroundColor: 'rgba(15,110,86,0.08)',
      borderWidth: 2.5, tension: 0.4, fill: true,
      pointBackgroundColor: '#0F6E56', pointRadius: 4, pointHoverRadius: 6
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 11 } } },
      y: { beginAtZero: true, ticks: { font: { size: 11 }, stepSize: 5 }, grid: { color: '#f3f4f6' } }
    }
  }
});

new Chart(document.getElementById('tierChart'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($tierNames) ?>,
    datasets: [{
      data: <?= json_encode($tierRevenue) ?>,
      backgroundColor: ['#378ADD','#D85A30','#7F77DD','#0F6E56'],
      borderRadius: 6, borderSkipped: false
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 11 } } },
      y: {
        beginAtZero: true, grid: { color: '#f3f4f6' },
        ticks: { font: { size: 11 }, callback: v => '$' + v }
      }
    }
  }
});
</script>

<?php include __DIR__ . '/../layouts/organiser-footer.php'; ?>
