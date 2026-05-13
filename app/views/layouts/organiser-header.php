<?php
Auth::requireRole('organiser');
$userName    = $_SESSION['user_name']  ?? 'Organiser';
$userEmail   = $_SESSION['user_email'] ?? '';
$userInitial = strtoupper(substr($userName, 0, 1));

// Fetch org logo for avatars
$_headerLogo = null;
try {
    $db = Database::getInstance();
    $pr = $db->query("SELECT org_logo_path FROM organiser_profiles WHERE user_id=?",
                     "i", [Auth::userId()]);
    $_headerLogo = $pr[0]['org_logo_path'] ?? null;
} catch (Exception $e) {}

function nav($page, $active) {
    return 'sb-link' . ($page === $active ? ' active' : '');
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title><?= htmlspecialchars($pageTitle ?? 'Event Platform') ?> — Event Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    :root { --sb-w:190px; --green:#0F6E56; --green-dark:#063D30; --green-light:#E1F5EE; }
    *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:'Inter',system-ui,sans-serif; background:#f4f6f8;
           display:flex; min-height:100vh; font-size:.92rem; }

    /* Sidebar */
    .sidebar { width:var(--sb-w); background:#0D3B2E; color:#fff;
      display:flex; flex-direction:column; position:fixed; top:0; left:0;
      height:100vh; z-index:100; overflow-y:auto; }
    .sb-brand { display:flex; align-items:center; gap:.6rem;
      padding:1.1rem 1rem .9rem; border-bottom:1px solid rgba(255,255,255,.08); }
    .sb-brand-icon { width:30px; height:30px; background:var(--green-light); border-radius:7px;
      display:flex; align-items:center; justify-content:center; color:var(--green);
      font-size:1rem; flex-shrink:0; }
    .sb-brand-name { font-size:.88rem; font-weight:800; color:#fff; line-height:1.2; }

    .sb-user { display:flex; align-items:center; gap:.65rem; padding:.85rem 1rem;
      border-bottom:1px solid rgba(255,255,255,.08); text-decoration:none; }
    .sb-user:hover { background:rgba(255,255,255,.05); }
    .sb-avatar { width:36px; height:36px; border-radius:50%; background:var(--green);
      display:flex; align-items:center; justify-content:center; font-size:.88rem;
      font-weight:800; color:#fff; flex-shrink:0; overflow:hidden; }
    .sb-avatar img { width:100%; height:100%; object-fit:cover; }
    .sb-user-name { font-size:.8rem; font-weight:700; color:#fff; line-height:1.2;
      white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .sb-user-role { font-size:.68rem; color:rgba(255,255,255,.5); }

    .sb-nav  { flex:1; padding:.6rem 0; }
    .sb-section { font-size:.62rem; font-weight:800; text-transform:uppercase;
      letter-spacing:.8px; color:rgba(255,255,255,.35); padding:.6rem 1rem .3rem; }
    .sb-link { display:flex; align-items:center; gap:.6rem; padding:.5rem 1rem;
      font-size:.8rem; font-weight:500; color:rgba(255,255,255,.7); text-decoration:none;
      transition:all .12s; margin:1px 0; }
    .sb-link:hover { background:rgba(255,255,255,.07); color:#fff; }
    .sb-link.active { background:rgba(255,255,255,.12); color:#fff; font-weight:700;
      border-right:3px solid #5DCAA5; }
    .sb-link i { font-size:.95rem; width:16px; flex-shrink:0; }
    .nb { background:#ef4444; color:#fff; border-radius:10px; font-size:.62rem;
      padding:1px 5px; font-weight:700; margin-left:auto; }
    .sb-logout { display:flex; align-items:center; gap:.6rem; padding:.75rem 1rem;
      font-size:.8rem; font-weight:500; color:rgba(255,255,255,.5); text-decoration:none;
      transition:all .12s; border-top:1px solid rgba(255,255,255,.08); }
    .sb-logout:hover { color:#fca5a5; background:rgba(239,68,68,.08); }

    /* Main */
    .main-wrap { margin-left:var(--sb-w); flex:1; display:flex; flex-direction:column; min-height:100vh; }
    .topbar { background:#fff; border-bottom:1px solid #e5e7eb; padding:.6rem 1.5rem;
      display:flex; align-items:center; justify-content:space-between;
      position:sticky; top:0; z-index:50; height:52px; }
    .topbar-title { font-size:.92rem; font-weight:700; color:#111; }
    .topbar-right { display:flex; align-items:center; gap:.75rem; }
    .theme-toggle { background:none; border:none; cursor:pointer; font-size:1.1rem;
      color:#6b7280; padding:.3rem; border-radius:6px; transition:all .15s; }
    .theme-toggle:hover { background:#f3f4f6; }

    /* Top-right user dropdown */
    .user-dropdown .dropdown-toggle {
      background:none; border:1.5px solid #e5e7eb; border-radius:25px;
      padding:.3rem .75rem .3rem .4rem;
      display:flex; align-items:center; gap:.5rem;
      font-size:.82rem; font-weight:600; color:#374151;
      cursor:pointer; transition:all .15s; }
    .user-dropdown .dropdown-toggle:hover { border-color:#0F6E56; color:#0F6E56; }
    .user-dropdown .dropdown-toggle::after { display:none; }
    .top-avatar { width:26px; height:26px; border-radius:50%; background:var(--green);
      display:flex; align-items:center; justify-content:center; font-size:.72rem;
      font-weight:800; color:#fff; flex-shrink:0; overflow:hidden; }
    .top-avatar img { width:100%; height:100%; object-fit:cover; }
    .user-dropdown .dropdown-menu { border:1px solid #e5e7eb; border-radius:12px;
      box-shadow:0 8px 30px rgba(0,0,0,.1); padding:.4rem; min-width:190px; }
    .user-dropdown .dropdown-item { border-radius:8px; font-size:.84rem;
      padding:.5rem .75rem; font-weight:500; }
    .user-dropdown .dropdown-item:hover { background:#f0faf5; color:#0F6E56; }
    .user-dropdown .dropdown-item.text-danger:hover { background:#FEF2F2; }

    .content { padding:1.5rem; flex:1; }
    .section-card { background:#fff; border-radius:14px;
      box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 14px rgba(0,0,0,.04);
      border:1px solid rgba(0,0,0,.05); overflow:hidden; }

    /* Dark mode */
    [data-bs-theme="dark"] body    { background:#111827; }
    [data-bs-theme="dark"] .topbar { background:#1f2937; border-color:#374151; }
    [data-bs-theme="dark"] .topbar-title { color:#f3f4f6; }
    [data-bs-theme="dark"] .theme-toggle { color:#9ca3af; }
    [data-bs-theme="dark"] .theme-toggle:hover { background:#374151; }
    [data-bs-theme="dark"] .section-card { background:#1f2937; border-color:#374151; }
    [data-bs-theme="dark"] .user-dropdown .dropdown-toggle { border-color:#374151; color:#d1d5db; }
    [data-bs-theme="dark"] .user-dropdown .dropdown-menu { background:#1f2937; border-color:#374151; }
    [data-bs-theme="dark"] .user-dropdown .dropdown-item { color:#d1d5db; }
    [data-bs-theme="dark"] .user-dropdown .dropdown-item:hover { background:#253245; }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
  <div class="sb-brand">
    <div class="sb-brand-icon"><i class="bi bi-calendar-check-fill"></i></div>
    <span class="sb-brand-name">Event Platform</span>
  </div>

  <!-- Sidebar avatar — clicking goes to profile -->
  <a href="/WebtechProject/public/organiser/profile" class="sb-user">
    <div class="sb-avatar">
      <?php if ($_headerLogo): ?>
        <img src="/WebtechProject/public/<?= htmlspecialchars($_headerLogo) ?>" alt="logo"/>
      <?php else: ?>
        <?= $userInitial ?>
      <?php endif; ?>
    </div>
    <div style="min-width:0;">
      <div class="sb-user-name"><?= htmlspecialchars($userName) ?></div>
      <div class="sb-user-role">Organiser</div>
    </div>
  </a>

  <div class="sb-nav">
    <div class="sb-section">Main</div>
    <a href="/WebtechProject/public/organiser/dashboard"      class="<?= nav('dashboard',      $activePage) ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <a href="/WebtechProject/public/organiser/events"         class="<?= nav('events',         $activePage) ?>"><i class="bi bi-calendar-event"></i> My Events</a>
    <a href="/WebtechProject/public/organiser/analytics"      class="<?= nav('analytics',      $activePage) ?>"><i class="bi bi-bar-chart-line-fill"></i> Analytics</a>

    <div class="sb-section">Tickets</div>
    <a href="/WebtechProject/public/organiser/checkin"        class="<?= nav('checkin',        $activePage) ?>"><i class="bi bi-qr-code-scan"></i> Live Check-in</a>
    <a href="/WebtechProject/public/organiser/bookings"       class="<?= nav('bookings',       $activePage) ?>"><i class="bi bi-ticket-perforated"></i> Bookings</a>
    <a href="/WebtechProject/public/organiser/discounts"      class="<?= nav('discounts',      $activePage) ?>"><i class="bi bi-tag-fill"></i> Discount Codes</a>

    <div class="sb-section">Management</div>
    <a href="/WebtechProject/public/organiser/refunds"        class="<?= nav('refunds',        $activePage) ?>">
      <i class="bi bi-receipt-cutoff"></i> Refund Requests
      <?php if (!empty($pendingRefunds) && $pendingRefunds > 0): ?>
        <span class="nb"><?= $pendingRefunds ?></span>
      <?php endif; ?>
    </a>
    <a href="/WebtechProject/public/organiser/reviews"        class="<?= nav('reviews',        $activePage) ?>"><i class="bi bi-star-fill"></i> Reviews</a>
    <a href="/WebtechProject/public/organiser/announcements"  class="<?= nav('announcements',  $activePage) ?>"><i class="bi bi-megaphone-fill"></i> Announcements</a>

    <div class="sb-section">Venues</div>
    <a href="/WebtechProject/public/organiser/venues"         class="<?= nav('venues',         $activePage) ?>"><i class="bi bi-building"></i> Browse Venues</a>
    <a href="/WebtechProject/public/organiser/venue-requests" class="<?= nav('venue-requests', $activePage) ?>"><i class="bi bi-send-fill"></i> My Requests</a>

    <div class="sb-section">Account</div>
    <a href="/WebtechProject/public/organiser/profile"        class="<?= nav('profile',        $activePage) ?>"><i class="bi bi-person-circle"></i> My Profile</a>
  </div>

  <a href="/WebtechProject/public/logout" class="sb-logout">
    <i class="bi bi-box-arrow-left"></i> Sign out
  </a>
</nav>

<!-- Main -->
<div class="main-wrap">
  <div class="topbar">
    <span class="topbar-title"><?= htmlspecialchars($pageTitle ?? '') ?></span>
    <div class="topbar-right">

      <button class="theme-toggle" id="themeToggle" title="Toggle dark mode">
        <i class="bi bi-moon-fill" id="themeIcon"></i>
      </button>

      <!-- User dropdown -->
      <div class="dropdown user-dropdown">
        <button class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="top-avatar">
            <?php if ($_headerLogo): ?>
              <img src="/WebtechProject/public/<?= htmlspecialchars($_headerLogo) ?>" alt=""/>
            <?php else: ?>
              <?= $userInitial ?>
            <?php endif; ?>
          </div>
          <?= htmlspecialchars($userName) ?>
          <i class="bi bi-chevron-down" style="font-size:.65rem;color:#9ca3af;"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <div style="padding:.5rem .75rem;">
              <div style="font-size:.84rem;font-weight:700;"><?= htmlspecialchars($userName) ?></div>
              <div style="font-size:.73rem;color:#9ca3af;"><?= htmlspecialchars($userEmail) ?></div>
            </div>
          </li>
          <li><hr class="dropdown-divider" style="margin:.3rem 0;"/></li>
          <li>
            <a class="dropdown-item" href="/WebtechProject/public/organiser/profile">
              <i class="bi bi-person-circle me-2" style="color:#0F6E56;"></i>My Profile
            </a>
          </li>
          <li>
            <a class="dropdown-item text-danger" href="/WebtechProject/public/logout">
              <i class="bi bi-box-arrow-left me-2"></i>Sign out
            </a>
          </li>
        </ul>
      </div>

    </div>
  </div>
  <div class="content">

<script>
(function(){
  const saved = localStorage.getItem('ep_theme') || 'light';
  document.documentElement.setAttribute('data-bs-theme', saved);
  const icon = document.getElementById('themeIcon');
  if (icon) icon.className = saved === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('themeToggle')?.addEventListener('click', function(){
  const html = document.documentElement;
  const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
  html.setAttribute('data-bs-theme', next);
  localStorage.setItem('ep_theme', next);
  document.getElementById('themeIcon').className = next === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
});
</script>