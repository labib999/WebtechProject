<?php
$pageTitle  = $pageTitle  ?? 'Dashboard';
$activePage = $activePage ?? 'dashboard';
$userName   = Auth::userName();
function nav($page, $active) {
    return $page === $active ? 'nav-link active' : 'nav-link';
}
?>
<!DOCTYPE html>
<html lang="en">
<script>
  const _t = localStorage.getItem('ep_theme') || 'light';
  document.documentElement.setAttribute('data-bs-theme', _t);
</script>
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($pageTitle) ?> — Event Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <style>
    :root {
      --sb-bg:#063D30; --sb-hover:rgba(255,255,255,0.08);
      --sb-active:rgba(255,255,255,0.13); --brand:#0F6E56; --sb-w:240px;
    }
    body { font-family:'Segoe UI',system-ui,sans-serif; margin:0; }

    /* ── Sidebar ── */
    .sidebar {
      width:var(--sb-w); min-height:100vh; background:var(--sb-bg); color:#fff;
      position:fixed; top:0; left:0; display:flex; flex-direction:column;
      overflow-y:auto; z-index:1000;
    }
    .sb-brand {
      display:flex; align-items:center; gap:.75rem; padding:1.25rem 1.5rem;
      border-bottom:1px solid rgba(255,255,255,0.07); text-decoration:none; color:#fff;
    }
    .sb-brand .logo {
      width:36px; height:36px; background:rgba(255,255,255,0.15); border-radius:10px;
      display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0;
    }
    .sb-brand span { font-weight:700; font-size:1rem; }
    .sb-user {
      display:flex; align-items:center; gap:.75rem; padding:1rem 1.5rem;
      border-bottom:1px solid rgba(255,255,255,0.07);
    }
    .sb-avatar {
      width:38px; height:38px; border-radius:50%; background:var(--brand);
      display:flex; align-items:center; justify-content:center;
      font-weight:700; font-size:.95rem; flex-shrink:0;
    }
    .sb-user .name { font-size:.85rem; font-weight:600; color:#fff; line-height:1.2; }
    .sb-user .role { font-size:.72rem; color:rgba(255,255,255,0.5); }
    .sb-section {
      font-size:.67rem; font-weight:700; letter-spacing:.8px; text-transform:uppercase;
      color:rgba(255,255,255,0.35); padding:.9rem 1.5rem .3rem;
    }
    .nav-link {
      display:flex; align-items:center; gap:.75rem; padding:.55rem 1.5rem;
      color:rgba(255,255,255,0.68); font-size:.855rem; text-decoration:none;
      border-left:3px solid transparent; transition:all .15s ease;
    }
    .nav-link:hover { background:var(--sb-hover); color:#fff; border-left-color:rgba(255,255,255,0.2); }
    .nav-link.active { background:var(--sb-active); color:#fff; font-weight:600; border-left-color:#5DCAA5; }
    .nav-link i { font-size:1rem; width:18px; text-align:center; flex-shrink:0; }
    .nb {
      margin-left:auto; background:#ef4444; color:#fff; border-radius:10px;
      font-size:.68rem; font-weight:700; padding:1px 6px; min-width:18px; text-align:center;
    }
    .sb-spacer { flex:1; }
    .sb-logout {
      margin:.75rem 1rem 1rem; border:1px solid rgba(255,255,255,0.15); border-radius:8px;
      padding:.5rem 1rem; color:rgba(255,255,255,0.6); font-size:.83rem; cursor:pointer;
      background:transparent; width:calc(100% - 2rem); display:flex; align-items:center;
      gap:.5rem; text-decoration:none; transition:all .15s;
    }
    .sb-logout:hover { background:rgba(255,0,0,0.12); color:#fca5a5; border-color:#fca5a5; }

    /* ── Main ── */
    .main-wrap { margin-left:var(--sb-w); min-height:100vh; display:flex; flex-direction:column; background:#f0f2f5; }
    .topbar {
      height:58px; background:#fff; border-bottom:1px solid #e5e7eb;
      display:flex; align-items:center; justify-content:space-between;
      padding:0 1.75rem; position:sticky; top:0; z-index:100;
    }
    .topbar .page-title { font-size:1.05rem; font-weight:600; color:#111; margin:0; }
    .topbar .top-right  { display:flex; align-items:center; gap:.75rem; }
    .topbar .tb-user    { font-size:.85rem; color:#374151; font-weight:500; }
    .page-body { padding:1.5rem; flex:1; }

    /* ── Dark mode ── */
    [data-bs-theme="dark"] body,
    [data-bs-theme="dark"] .main-wrap      { background:#111827; }
    [data-bs-theme="dark"] .topbar         { background:#1f2937; border-color:#374151; }
    [data-bs-theme="dark"] .topbar .page-title { color:#f9fafb; }
    [data-bs-theme="dark"] .topbar .tb-user    { color:#d1d5db; }
    [data-bs-theme="dark"] .kpi-card,
    [data-bs-theme="dark"] .chart-card,
    [data-bs-theme="dark"] .section-card   { background:#1f2937 !important; border-color:#374151 !important; }
    [data-bs-theme="dark"] .kpi-label      { color:#9ca3af; }
    [data-bs-theme="dark"] .kpi-value      { color:#f3f4f6; }
    [data-bs-theme="dark"] .kpi-sub        { color:#9ca3af; }
    [data-bs-theme="dark"] .chart-card h6,
    [data-bs-theme="dark"] .section-hdr h6 { color:#f3f4f6; }
    [data-bs-theme="dark"] .chart-card .ch-sub { color:#6b7280; }
    [data-bs-theme="dark"] .bk-table th   { background:#253245 !important; color:#9ca3af; }
    [data-bs-theme="dark"] .bk-table td   { border-color:#253245; color:#d1d5db; }
    [data-bs-theme="dark"] .bk-table tr:hover td { background:#253245 !important; }
    [data-bs-theme="dark"] .section-hdr   { border-color:#374151; }
    [data-bs-theme="dark"] .ev-item        { border-color:#374151; }
    [data-bs-theme="dark"] .ev-icon        { background:#1a3a2e !important; }
    [data-bs-theme="dark"] .qa-outline     { background:#1f2937; color:#5DCAA5; border-color:#0F6E56; }
    [data-bs-theme="dark"] .qa-warn        { background:#1f2937; color:#fbbf24; border-color:#d97706; }
    [data-bs-theme="dark"] .view-all       { background:#1a3a2e !important; color:#5DCAA5 !important; }
    [data-bs-theme="dark"] .ev-item .fw-semibold { color:#f3f4f6; }
    [data-bs-theme="dark"] code            { color:#5DCAA5; background:#1a3a2e; padding:1px 5px; border-radius:4px; }
    [data-bs-theme="dark"] .pill-gen   { background:#1e3a5f; color:#93c5fd; }
    [data-bs-theme="dark"] .pill-vip   { background:#431407; color:#fdba74; }
    [data-bs-theme="dark"] .pill-early { background:#2e1065; color:#c4b5fd; }
    [data-bs-theme="dark"] .pill-ok    { background:#064e3b; color:#6ee7b7; }
    [data-bs-theme="dark"] .pill-pend  { background:#451a03; color:#fcd34d; }
    [data-bs-theme="dark"] .pill-ref   { background:#450a0a; color:#fca5a5; }

    /* ── Theme toggle button ── */
    .theme-btn {
      width:34px; height:34px; border-radius:9px; border:none; cursor:pointer;
      display:flex; align-items:center; justify-content:center; font-size:.95rem; transition:all .2s;
    }
    [data-bs-theme="light"] .theme-btn { background:#f3f4f6; color:#374151; }
    [data-bs-theme="light"] .theme-btn:hover { background:#e5e7eb; }
    [data-bs-theme="dark"]  .theme-btn { background:#374151; color:#fbbf24; }
    [data-bs-theme="dark"]  .theme-btn:hover { background:#4b5563; }
  </style>
</head>
<body>

<nav class="sidebar">
  <a href="/WebtechProject/public/organiser/dashboard" class="sb-brand">
    <div class="logo"><i class="bi bi-calendar-event-fill"></i></div>
    <span>Event Platform</span>
  </a>
  <div class="sb-user">
    <div class="sb-avatar"><?= strtoupper(substr($userName, 0, 1)) ?></div>
    <div>
      <div class="name"><?= htmlspecialchars($userName) ?></div>
      <div class="role">Organiser</div>
    </div>
  </div>

  <div class="sb-section">Main</div>
  <a href="/WebtechProject/public/organiser/dashboard"      class="<?= nav('dashboard',       $activePage) ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
  <a href="/WebtechProject/public/organiser/events"         class="<?= nav('events',          $activePage) ?>"><i class="bi bi-calendar-event"></i> My Events</a>
  <a href="/WebtechProject/public/organiser/analytics"      class="<?= nav('analytics',       $activePage) ?>"><i class="bi bi-bar-chart-line-fill"></i> Analytics</a>

  <div class="sb-section">Tickets</div>
  <a href="/WebtechProject/public/organiser/checkin"        class="<?= nav('checkin',         $activePage) ?>"><i class="bi bi-qr-code-scan"></i> Live Check-in</a>
  <a href="/WebtechProject/public/organiser/bookings"       class="<?= nav('bookings',        $activePage) ?>"><i class="bi bi-ticket-perforated"></i> Bookings</a>
  <a href="/WebtechProject/public/organiser/discounts"      class="<?= nav('discounts',       $activePage) ?>"><i class="bi bi-tag-fill"></i> Discount Codes</a>

  <div class="sb-section">Management</div>
  <a href="/WebtechProject/public/organiser/refunds"        class="<?= nav('refunds',         $activePage) ?>">
    <i class="bi bi-receipt-cutoff"></i> Refund Requests
    <?php if (!empty($pendingRefunds) && $pendingRefunds > 0): ?><span class="nb"><?= $pendingRefunds ?></span><?php endif; ?>
  </a>
  <a href="/WebtechProject/public/organiser/reviews"        class="<?= nav('reviews',         $activePage) ?>"><i class="bi bi-star-fill"></i> Reviews</a>
  <a href="/WebtechProject/public/organiser/announcements"  class="<?= nav('announcements',   $activePage) ?>"><i class="bi bi-megaphone-fill"></i> Announcements</a>

  <div class="sb-section">Venues</div>
  <a href="/WebtechProject/public/organiser/venues"         class="<?= nav('venues',          $activePage) ?>"><i class="bi bi-building"></i> Browse Venues</a>
  <a href="/WebtechProject/public/organiser/venue-requests" class="<?= nav('venue-requests',  $activePage) ?>"><i class="bi bi-send-fill"></i> My Requests</a>

  <div class="sb-spacer"></div>
  <a href="/WebtechProject/public/logout" class="sb-logout">
    <i class="bi bi-box-arrow-left"></i> Sign out
  </a>
</nav>

<div class="main-wrap">
  <div class="topbar">
    <h1 class="page-title"><?= htmlspecialchars($pageTitle) ?></h1>
    <div class="top-right">
      <button class="theme-btn" onclick="toggleTheme()" title="Toggle dark mode">
        <i id="themeIcon" class="bi bi-moon-fill"></i>
      </button>
      <span class="tb-user">
        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($userName) ?>
      </span>
    </div>
  </div>
  <div class="page-body">

<script>
  function toggleTheme() {
    const cur  = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const next = cur === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', next);
    localStorage.setItem('ep_theme', next);
    document.getElementById('themeIcon').className =
      next === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
  }
  document.getElementById('themeIcon').className =
    (localStorage.getItem('ep_theme') || 'light') === 'dark'
      ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
</script>