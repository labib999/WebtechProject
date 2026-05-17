<?php
$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
include __DIR__ . '/../layouts/organiser-header.php';
?>
<style>
/* ── Hero ── */
.hero {
  background: linear-gradient(135deg, #031f17 0%, #042C20 25%, #0F6E56 65%, #1a8a6e 100%);
  background-size: 300% 300%;
  animation: gradientShift 10s ease infinite;
  border-radius: 20px; padding: 2rem 2.25rem;
  position: relative; overflow: hidden; margin-bottom: 1.5rem;
  box-shadow: 0 8px 40px rgba(15,110,86,.35);
}
.hero::before {
  content: ''; position: absolute;
  width: 400px; height: 400px; border-radius: 50%;
  background: rgba(255,255,255,.04);
  top: -150px; right: -80px; pointer-events: none;
}
.hero::after {
  content: ''; position: absolute;
  width: 250px; height: 250px; border-radius: 50%;
  background: rgba(93,202,165,.08);
  bottom: -80px; left: 35%; pointer-events: none;
}
.hero-circle {
  position: absolute; border-radius: 50%;
  background: rgba(255,255,255,.03);
  pointer-events: none;
}
.hero-date {
  font-size: .82rem; color: rgba(255,255,255,.6);
  margin-bottom: .5rem; display: flex; align-items: center; gap: .4rem;
}
.hero-greeting {
  font-size: 2rem; font-weight: 900; color: #fff;
  letter-spacing: -.5px; margin-bottom: .35rem; line-height: 1.1;
}
.hero-sub { font-size: .92rem; color: rgba(255,255,255,.7); margin: 0; }
.hero-badge {
  display: inline-flex; align-items: center; gap: .4rem;
  background: rgba(239,68,68,.2); color: #fca5a5;
  padding: 3px 12px; border-radius: 99px; font-size: .8rem;
  font-weight: 600; margin-left: .75rem;
  border: 1px solid rgba(239,68,68,.3);
}
.hero-stat { text-align: right; position: relative; z-index: 1; }
.hero-stat-val {
  font-size: 1.9rem; font-weight: 900; color: #fff;
  letter-spacing: -.5px; line-height: 1; display: block;
}
.hero-stat-lbl { font-size: .72rem; color: rgba(255,255,255,.5); margin-top: .2rem; }
.hero-divider {
  width: 1px; background: rgba(255,255,255,.15);
  align-self: stretch; margin: 0 .5rem;
}

/* ── Quick Actions ── */
.qa-row { display: flex; flex-wrap: wrap; gap: .6rem; margin-bottom: 1.5rem; }
.qa { display: inline-flex; align-items: center; gap: .5rem;
  padding: .55rem 1.1rem; border-radius: 99px; font-size: .84rem;
  font-weight: 600; text-decoration: none; transition: all .2s var(--ease);
  border: 2px solid transparent; white-space: nowrap; }
.qa-p { background: linear-gradient(135deg,var(--g500),var(--g700)); color:#fff; box-shadow:var(--sh-green); }
.qa-p:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(15,110,86,.4); color:#fff; }
.qa-o { background:#fff; color:var(--g700); border-color:var(--g200,#e5e7eb); box-shadow:var(--sh-sm); }
.qa-o:hover { border-color:var(--g700); background:var(--g50); transform:translateY(-2px); }
.qa-w { background:#fff; color:#b45309; border-color:#fde68a; box-shadow:var(--sh-sm); }
.qa-w:hover { background:#FFFBEB; border-color:#f59e0b; transform:translateY(-2px); }
.qa-badge {
  background:linear-gradient(135deg,#ef4444,#dc2626);
  color:#fff; border-radius:99px; font-size:.68rem;
  padding:0 6px; font-weight:700; min-width:18px; text-align:center;
  box-shadow:0 2px 6px rgba(239,68,68,.4);
}

/* ── KPI Cards ── */
.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:1.5rem; }
@media(max-width:900px){ .kpi-grid{ grid-template-columns:repeat(2,1fr); } }
.kpi {
  background:var(--surface); border-radius:var(--r-lg);
  padding:1.35rem 1.4rem; position:relative; overflow:hidden;
  box-shadow:var(--sh-md); border:1px solid var(--border);
  transition:all .25s var(--ease); cursor:default;
}
.kpi::before {
  content:''; position:absolute; top:0; left:0; right:0;
  height:3px; border-radius:var(--r-lg) var(--r-lg) 0 0;
  background:linear-gradient(90deg,var(--kpi-c1,var(--g700)),var(--kpi-c2,var(--g300)));
}
.kpi:hover { transform:translateY(-4px); box-shadow:0 12px 40px rgba(0,0,0,.12); }
.kpi-icon {
  width:46px; height:46px; border-radius:14px;
  display:flex; align-items:center; justify-content:center;
  font-size:1.25rem; margin-bottom:.9rem;
  background:linear-gradient(135deg,var(--kpi-c1,var(--g700)),var(--kpi-c2,var(--g300)));
  box-shadow:0 4px 14px color-mix(in srgb, var(--kpi-c1,var(--g700)) 40%, transparent);
}
.kpi-icon i { color:#fff; }
.kpi-label { font-size:.7rem; font-weight:700; text-transform:uppercase;
  letter-spacing:.7px; color:var(--gray500); margin-bottom:.3rem; }
.kpi-value { font-size:1.8rem; font-weight:900; color:var(--gray900); line-height:1.1;
  letter-spacing:-.5px; }
.kpi-sub { font-size:.76rem; color:var(--gray500); margin-top:.3rem; }
.kpi-badge {
  position:absolute; top:1rem; right:1rem;
  font-size:.72rem; font-weight:700; padding:2px 9px;
  border-radius:99px;
}

/* ── Chart Cards ── */
.chart-card {
  background:var(--surface); border-radius:var(--r-lg);
  box-shadow:var(--sh-md); border:1px solid var(--border);
  overflow:hidden; transition:box-shadow .25s;
}
.chart-card:hover { box-shadow:var(--sh-lg); }
.chart-hdr {
  padding:1.1rem 1.35rem .75rem;
  display:flex; justify-content:space-between; align-items:flex-start;
}
.chart-title { font-size:.92rem; font-weight:700; color:var(--gray900); }
.chart-sub { font-size:.74rem; color:var(--gray500); margin-top:.1rem; }
.chart-body { padding:.25rem 1rem 1rem; }

/* ── Section Cards ── */
.sc-hdr {
  display:flex; justify-content:space-between; align-items:center;
  padding:.9rem 1.25rem .5rem;
  border-bottom:1px solid rgba(0,0,0,.05);
}
.sc-title { font-size:.9rem; font-weight:700; color:var(--gray900);
  display:flex; align-items:center; gap:.5rem; }
.view-all {
  font-size:.78rem; font-weight:600; color:var(--g700);
  text-decoration:none; padding:.28rem .8rem; border-radius:99px;
  background:var(--g50); transition:all .15s;
}
.view-all:hover { background:var(--g100); color:var(--g800); }

/* ── Bookings table ── */
.bk-table { width:100%; border-collapse:collapse; font-size:.845rem; }
.bk-table th {
  padding:.6rem 1rem; font-size:.68rem; font-weight:700;
  letter-spacing:.5px; text-transform:uppercase; color:var(--gray500);
  border-bottom:1px solid var(--gray100); background:var(--gray50);
}
.bk-table td { padding:.72rem 1rem; border-bottom:1px solid rgba(0,0,0,.04); vertical-align:middle; }
.bk-table tr:last-child td { border:none; }
.bk-table tr:hover td { background:var(--g50); }
.pill { display:inline-flex; align-items:center; gap:.3rem;
  padding:3px 10px; border-radius:99px; font-size:.73rem; font-weight:600; }
.pill-ok   { background:#ECFDF5; color:#065f46; }
.pill-pend { background:#FFFBEB; color:#92400e; }
.pill-ref  { background:#FEF2F2; color:#991b1b; }
.pill-gen  { background:#EFF6FF; color:#1d4ed8; }
.pill-vip  { background:#FFF7ED; color:#c2410c; }
.pill-early{ background:#F5F3FF; color:#6d28d9; }

/* ── Events mini ── */
.ev-item { display:flex; align-items:flex-start; gap:.8rem;
  padding:.8rem 0; border-bottom:1px solid rgba(0,0,0,.05); }
.ev-item:last-child { border:none; }
.ev-icon {
  width:40px; height:40px;
  background:linear-gradient(135deg,var(--g100),var(--g50));
  border-radius:12px; display:flex; align-items:center;
  justify-content:center; color:var(--g700); font-size:1.1rem;
  flex-shrink:0; border:1px solid var(--g100);
}
.ev-name { font-size:.87rem; font-weight:600; color:var(--gray900); }
.ev-meta { font-size:.74rem; color:var(--gray500); margin-top:.1rem; }

/* ── Dark mode ── */
[data-bs-theme="dark"] .kpi,
[data-bs-theme="dark"] .chart-card { background:#161b22; border-color:rgba(255,255,255,.07); }
[data-bs-theme="dark"] .kpi-value,
[data-bs-theme="dark"] .chart-title,
[data-bs-theme="dark"] .ev-name,
[data-bs-theme="dark"] .sc-title { color:#f0f6fc; }
[data-bs-theme="dark"] .bk-table th { background:#1c2128; color:#8b949e; border-color:#21262d; }
[data-bs-theme="dark"] .bk-table td { border-color:rgba(255,255,255,.05); color:#c9d1d9; }
[data-bs-theme="dark"] .bk-table tr:hover td { background:#1c2128; }
[data-bs-theme="dark"] .sc-hdr { border-color:rgba(255,255,255,.07); }
[data-bs-theme="dark"] .ev-icon { background:#1c2128; border-color:#21262d; }
[data-bs-theme="dark"] .qa-o { background:#21262d; border-color:#30363d; color:#c9d1d9; }
</style>

<!-- ══ HERO ═══════════════════════════════════════════════ -->
<div class="hero">
  <div class="hero-circle" style="width:500px;height:500px;top:-200px;right:-120px;"></div>
  <div class="hero-circle" style="width:200px;height:200px;bottom:-50px;left:40%;"></div>
  <div class="hero-circle" style="width:120px;height:120px;top:10px;left:55%;opacity:.5;"></div>

  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3"
       style="position:relative;z-index:1;">
    <div>
      <div class="hero-date">
        <i class="bi bi-calendar3"></i>
        <?= date('l, d F Y') ?>
        &nbsp;·&nbsp;
        <i class="bi bi-clock"></i>
        <?= date('g:i A') ?>
      </div>
      <h2 class="hero-greeting">
        <?= $greeting ?>, <?= htmlspecialchars(explode(' ', $userName)[0]) ?> 👋
      </h2>
      <p class="hero-sub">
        Here is your organiser overview.
        <?php if ($pendingRefunds > 0): ?>
          <span class="hero-badge">
            <i class="bi bi-exclamation-circle"></i>
            <?= $pendingRefunds ?> refund<?= $pendingRefunds!=1?'s':'' ?> need attention
          </span>
        <?php endif; ?>
      </p>
    </div>

    <div class="d-flex align-items-center gap-3 flex-wrap">
      <div class="hero-stat">
        <span class="hero-stat-val" data-count="<?= $ticketsSold ?>"><?= number_format($ticketsSold) ?></span>
        <div class="hero-stat-lbl">Tickets sold</div>
      </div>
      <div class="hero-divider"></div>
      <div class="hero-stat">
        <span class="hero-stat-val">$<?= number_format($totalRevenue,0) ?></span>
        <div class="hero-stat-lbl">Revenue</div>
      </div>
      <div class="hero-divider"></div>
      <div class="hero-stat">
        <span class="hero-stat-val"><?= $checkinRate ?>%</span>
        <div class="hero-stat-lbl">Check-in rate</div>
      </div>
    </div>
  </div>
</div>

<!-- ══ QUICK ACTIONS ════════════════════════════════════════ -->
<div class="qa-row">
  <a href="/WebtechProject/public/organiser/events/create" class="qa qa-p">
    <i class="bi bi-plus-circle-fill"></i> Create Event
  </a>
  <a href="/WebtechProject/public/organiser/checkin" class="qa qa-o">
    <i class="bi bi-qr-code-scan"></i> Live Check-in
  </a>
  <a href="/WebtechProject/public/organiser/refunds" class="qa qa-w">
    <i class="bi bi-receipt-cutoff"></i> Refund Requests
    <?php if ($pendingRefunds > 0): ?>
      <span class="qa-badge"><?= $pendingRefunds ?></span>
    <?php endif; ?>
  </a>
  <a href="/WebtechProject/public/organiser/announcements" class="qa qa-o">
    <i class="bi bi-megaphone-fill"></i> Post Announcement
  </a>
</div>

<!-- ══ KPI CARDS ════════════════════════════════════════════ -->
<div class="kpi-grid stagger">

  <div class="kpi anim-up" style="--kpi-c1:#0F6E56;--kpi-c2:#5DCAA5;">
    <span class="kpi-badge" style="background:#ECFDF5;color:#065f46;">
      <?= $publishedEvents ?> live
    </span>
    <div class="kpi-icon"><i class="bi bi-ticket-perforated-fill"></i></div>
    <div class="kpi-label">Tickets Sold</div>
    <div class="kpi-value" data-count="<?= $ticketsSold ?>"><?= number_format($ticketsSold) ?></div>
    <div class="kpi-sub"><?= $totalEvents ?> event<?= $totalEvents!=1?'s':'' ?> total</div>
  </div>

  <div class="kpi anim-up" style="--kpi-c1:#d97706;--kpi-c2:#f59e0b;">
    <div class="kpi-icon"><i class="bi bi-graph-up-arrow"></i></div>
    <div class="kpi-label">Total Revenue</div>
    <div class="kpi-value">$<?= number_format($totalRevenue,0) ?></div>
    <div class="kpi-sub">from active bookings</div>
  </div>

  <div class="kpi anim-up" style="--kpi-c1:#4f46e5;--kpi-c2:#818cf8;">
    <span class="kpi-badge" style="background:#EEF2FF;color:#4338ca;">
      <?= $checkinRate ?>%
    </span>
    <div class="kpi-icon"><i class="bi bi-qr-code-scan"></i></div>
    <div class="kpi-label">Checked In</div>
    <div class="kpi-value">
      <span data-count="<?= $totalCheckedIn ?>"><?= $totalCheckedIn ?></span>
      <span style="font-size:1rem;font-weight:500;color:var(--gray500);"> / <?= $totalActive ?></span>
    </div>
    <div style="height:4px;background:var(--gray100);border-radius:99px;margin-top:.6rem;overflow:hidden;">
      <div style="height:100%;width:<?= $checkinRate ?>%;background:linear-gradient(90deg,#4f46e5,#818cf8);border-radius:99px;transition:width .8s var(--ease);"></div>
    </div>
  </div>

  <div class="kpi anim-up" style="--kpi-c1:#d97706;--kpi-c2:#fbbf24;">
    <?php if ($reviewCount > 0): ?>
      <span class="kpi-badge" style="background:#FFFBEB;color:#92400e;">
        <?= $reviewCount ?> reviews
      </span>
    <?php endif; ?>
    <div class="kpi-icon"><i class="bi bi-star-fill"></i></div>
    <div class="kpi-label">Avg Rating</div>
    <div class="kpi-value"><?= $avgRating ?: '—' ?></div>
    <div class="kpi-sub" style="display:flex;gap:2px;margin-top:.3rem;">
      <?php for ($i=1;$i<=5;$i++): ?>
        <i class="bi bi-star<?= $i<=round($avgRating)?'-fill':'' ?>"
           style="color:<?= $i<=round($avgRating)?'#f59e0b':'#e5e7eb' ?>;font-size:.75rem;"></i>
      <?php endfor; ?>
    </div>
  </div>

</div>

<!-- ══ CHARTS ═══════════════════════════════════════════════ -->
<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="chart-card">
      <div class="chart-hdr">
        <div>
          <div class="chart-title">Ticket Sales</div>
          <div class="chart-sub">Daily volume — last 7 days</div>
        </div>
        <div style="background:var(--g50);border:1px solid var(--g100);border-radius:99px;
                    padding:3px 12px;font-size:.75rem;font-weight:700;color:var(--g700);">
          <i class="bi bi-arrow-up me-1"></i>This week
        </div>
      </div>
      <div class="chart-body">
        <div style="position:relative;height:220px;"><canvas id="salesChart"></canvas></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="chart-card">
      <div class="chart-hdr">
        <div>
          <div class="chart-title">Revenue by Tier</div>
          <div class="chart-sub">All time breakdown</div>
        </div>
      </div>
      <div class="chart-body">
        <div style="position:relative;height:220px;"><canvas id="tierChart"></canvas></div>
      </div>
    </div>
  </div>
</div>

<!-- ══ BOOKINGS + EVENTS ═════════════════════════════════════ -->
<div class="row g-3">
  <div class="col-lg-8">
    <div class="section-card">
      <div class="sc-hdr">
        <div class="sc-title">
          <i class="bi bi-ticket-perforated-fill" style="color:var(--g700);"></i>
          Recent Bookings
        </div>
        <a href="/WebtechProject/public/organiser/bookings" class="view-all">View all</a>
      </div>
      <div class="table-responsive">
        <table class="bk-table">
          <thead>
            <tr>
              <th class="ps-4">Attendee</th>
              <th>Tier</th>
              <th>Ticket Code</th>
              <th>Paid</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($recentBookings)): ?>
              <tr><td colspan="5" class="text-center py-4" style="color:var(--gray500);">No bookings yet</td></tr>
            <?php else: foreach ($recentBookings as $b): ?>
            <tr>
              <td class="ps-4" style="font-weight:600;color:var(--gray900);">
                <?= htmlspecialchars($b['attendee_name']) ?>
              </td>
              <td>
                <?php
                  $tl = strtolower($b['tier_name']);
                  $pc = str_contains($tl,'vip')?'pill-vip':(str_contains($tl,'early')?'pill-early':'pill-gen');
                ?>
                <span class="pill <?= $pc ?>"><?= htmlspecialchars($b['tier_name']) ?></span>
              </td>
              <td>
                <code style="font-size:.78rem;color:var(--g700);background:var(--g50);
                             padding:2px 7px;border-radius:6px;">
                  <?= htmlspecialchars($b['ticket_code']) ?>
                </code>
              </td>
              <td style="font-weight:700;color:var(--gray900);">
                $<?= number_format($b['total_price'],2) ?>
              </td>
              <td>
                <?php if ($b['checked_in']): ?>
                  <span class="pill pill-ok"><i class="bi bi-check-circle-fill"></i>Checked in</span>
                <?php elseif ($b['status']==='refunded'): ?>
                  <span class="pill pill-ref"><i class="bi bi-x-circle-fill"></i>Refunded</span>
                <?php else: ?>
                  <span class="pill pill-pend"><i class="bi bi-clock"></i>Pending</span>
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
      <div class="sc-hdr">
        <div class="sc-title">
          <i class="bi bi-calendar-event-fill" style="color:var(--g700);"></i>
          My Events
        </div>
        <a href="/WebtechProject/public/organiser/events" class="view-all">View all</a>
      </div>
      <div style="padding:.75rem 1.25rem;">
        <?php if (empty($myEvents)): ?>
          <p style="color:var(--gray500);text-align:center;padding:2rem 0;font-size:.88rem;">
            No events yet
          </p>
        <?php else: foreach ($myEvents as $ev): ?>
        <div class="ev-item">
          <div class="ev-icon"><i class="bi bi-calendar-event"></i></div>
          <div style="flex:1;min-width:0;">
            <div class="ev-name text-truncate"><?= htmlspecialchars($ev['title']) ?></div>
            <div class="ev-meta">
              <?= date('d M Y', strtotime($ev['event_datetime'])) ?> ·
              <?= $ev['bookings_count'] ?> booking<?= $ev['bookings_count']!=1?'s':'' ?>
            </div>
          </div>
          <span class="pill <?= $ev['status']==='published'?'pill-ok':'' ?>"
                style="<?= $ev['status']!=='published'?'background:var(--gray100);color:var(--gray500);':'' ?>flex-shrink:0;">
            <?= ucfirst($ev['status']) ?>
          </span>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</div>

<?php
$salesMap = array_column($salesChart,'tickets','sale_date');
$last7Labels = []; $last7Tickets = [];
for ($i=6;$i>=0;$i--) {
  $d = date('Y-m-d',strtotime("-{$i} days"));
  $last7Labels[]  = date('D d',strtotime($d));
  $last7Tickets[] = (int)($salesMap[$d]??0);
}
$tierNames   = array_column($tierChart,'tier_name');
$tierRevenue = array_map('floatval',array_column($tierChart,'revenue'));
?>
<script>
// Sales chart
new Chart(document.getElementById('salesChart'),{
  type:'line',
  data:{
    labels:<?= json_encode($last7Labels) ?>,
    datasets:[{
      data:<?= json_encode($last7Tickets) ?>,
      borderColor:'#0F6E56',
      backgroundColor:(ctx)=>{
        const g=ctx.chart.ctx.createLinearGradient(0,0,0,220);
        g.addColorStop(0,'rgba(15,110,86,.18)');
        g.addColorStop(1,'rgba(15,110,86,0)');
        return g;
      },
      borderWidth:2.5,tension:.4,fill:true,
      pointBackgroundColor:'#fff',
      pointBorderColor:'#0F6E56',
      pointBorderWidth:2,
      pointRadius:4,
      pointHoverRadius:6,
    }]
  },
  options:{
    responsive:true,maintainAspectRatio:false,
    plugins:{legend:{display:false},tooltip:{
      backgroundColor:'#fff',titleColor:'#111',bodyColor:'#6b7280',
      borderColor:'rgba(0,0,0,.08)',borderWidth:1,
      padding:10,cornerRadius:10,
    }},
    scales:{
      x:{grid:{display:false},ticks:{font:{size:11,family:'Inter'},color:'#9ca3af'}},
      y:{beginAtZero:true,grid:{color:'rgba(0,0,0,.04)'},
         ticks:{font:{size:11,family:'Inter'},color:'#9ca3af'}}
    }
  }
});

// Tier chart
new Chart(document.getElementById('tierChart'),{
  type:'bar',
  data:{
    labels:<?= json_encode($tierNames) ?>,
    datasets:[{
      data:<?= json_encode($tierRevenue) ?>,
      backgroundColor:['rgba(55,138,221,.8)','rgba(216,90,48,.8)','rgba(127,119,221,.8)','rgba(15,110,86,.8)'],
      borderRadius:8,borderSkipped:false,
      hoverBackgroundColor:['#378ADD','#D85A30','#7F77DD','#0F6E56'],
    }]
  },
  options:{
    responsive:true,maintainAspectRatio:false,
    plugins:{legend:{display:false},tooltip:{
      backgroundColor:'#fff',titleColor:'#111',bodyColor:'#6b7280',
      borderColor:'rgba(0,0,0,.08)',borderWidth:1,padding:10,cornerRadius:10,
      callbacks:{label:ctx=>'$'+ctx.raw.toLocaleString()}
    }},
    scales:{
      x:{grid:{display:false},ticks:{font:{size:11,family:'Inter'},color:'#9ca3af'}},
      y:{beginAtZero:true,grid:{color:'rgba(0,0,0,.04)'},
         ticks:{font:{size:11,family:'Inter'},color:'#9ca3af',callback:v=>'$'+v}}
    }
  }
});

document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('[data-count]').forEach(el=>{
    const target = parseInt(el.dataset.count) || 0;
    if(target === 0) return;
    let current = 0;
    const step = target / 40;
    const timer = setInterval(()=>{
      current = Math.min(current + step, target);
      el.textContent = Math.floor(current);
      if(current >= target) clearInterval(timer);
    }, 25);
  });
});
</script>

<?php include __DIR__ . '/../layouts/organiser-footer.php'; ?>