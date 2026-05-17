<?php
Auth::requireRole('organiser');
$userName    = $_SESSION['user_name']  ?? 'Organiser';
$userEmail   = $_SESSION['user_email'] ?? '';
$userInitial = strtoupper(substr($userName, 0, 1));

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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    /* ═══════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════ */
    :root {
      --g900:#042C20; --g800:#063D30; --g700:#0F6E56;
      --g500:#1a8a6e; --g300:#5DCAA5; --g100:#E1F5EE; --g50:#F0FAF6;
      --gold:#F59E0B; --purple:#6366F1; --red:#EF4444; --blue:#3B82F6;
      --gray900:#111827; --gray700:#374151; --gray500:#6B7280;
      --gray300:#D1D5DB; --gray200:#E5E7EB; --gray100:#F3F4F6; --gray50:#F9FAFB;
      --bg:#EEF2F7; --surface:#FFFFFF; --border:rgba(0,0,0,0.06);
      --sh-sm:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
      --sh-md:0 4px 16px rgba(0,0,0,.08),0 1px 4px rgba(0,0,0,.04);
      --sh-lg:0 10px 40px rgba(0,0,0,.12),0 2px 8px rgba(0,0,0,.06);
      --sh-green:0 4px 20px rgba(15,110,86,.3);
      --r-sm:8px; --r-md:12px; --r-lg:16px; --r-xl:24px; --r-full:9999px;
      --ease:cubic-bezier(.4,0,.2,1); --sb-w:210px;
    }

    /* ═══════════════════════════════════════
       BASE
    ═══════════════════════════════════════ */
    *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
    html { scroll-behavior:smooth; }
    body {
      font-family:'Inter',system-ui,sans-serif; background:var(--bg);
      color:var(--gray700); display:flex; min-height:100vh;
      -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
    }

    /* ═══════════════════════════════════════
       SCROLLBAR
    ═══════════════════════════════════════ */
    ::-webkit-scrollbar { width:5px; height:5px; }
    ::-webkit-scrollbar-track { background:transparent; }
    ::-webkit-scrollbar-thumb { background:var(--gray300); border-radius:99px; }
    ::-webkit-scrollbar-thumb:hover { background:var(--gray500); }
    ::selection { background:var(--g100); color:var(--g700); }

    /* ═══════════════════════════════════════
       ANIMATIONS
    ═══════════════════════════════════════ */
    @keyframes fadeUp {
      from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)}
    }
    @keyframes fadeIn { from{opacity:0} to{opacity:1} }
    @keyframes slideRight {
      from{opacity:0;transform:translateX(-16px)} to{opacity:1;transform:translateX(0)}
    }
    @keyframes shimmer {
      from{background-position:-200% 0} to{background-position:200% 0}
    }
    @keyframes pulseDot {
      0%,100%{box-shadow:0 0 0 0 rgba(93,202,165,.5)}
      50%{box-shadow:0 0 0 8px rgba(93,202,165,0)}
    }
    @keyframes shake {
      0%,100%{transform:translateX(0)} 20%{transform:translateX(-8px)}
      40%{transform:translateX(8px)} 60%{transform:translateX(-5px)}
      80%{transform:translateX(5px)}
    }
    @keyframes bounceIn {
      0%{transform:scale(.3);opacity:0} 50%{transform:scale(1.05)}
      70%{transform:scale(.9)} 100%{transform:scale(1);opacity:1}
    }
    @keyframes ripple { to{transform:scale(4);opacity:0} }
    @keyframes spin { to{transform:rotate(360deg)} }
    @keyframes countUp {
      from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)}
    }
    @keyframes gradientShift {
      0%{background-position:0% 50%} 50%{background-position:100% 50%}
      100%{background-position:0% 50%}
    }

    /* ═══════════════════════════════════════
       ANIMATION UTILITIES
    ═══════════════════════════════════════ */
    .anim-up    { animation:fadeUp    .5s var(--ease) both; }
    .anim-in    { animation:fadeIn    .4s ease both; }
    .anim-right { animation:slideRight .4s var(--ease) both; }
    .anim-bounce{ animation:bounceIn  .5s var(--ease) both; }
    .shake      { animation:shake .4s ease; }
    .stagger>*:nth-child(1){animation-delay:.05s}
    .stagger>*:nth-child(2){animation-delay:.10s}
    .stagger>*:nth-child(3){animation-delay:.15s}
    .stagger>*:nth-child(4){animation-delay:.20s}
    .stagger>*:nth-child(5){animation-delay:.25s}
    .stagger>*:nth-child(6){animation-delay:.30s}
    :focus-visible{outline:2px solid var(--g700);outline-offset:2px;border-radius:4px;}

    /* ═══════════════════════════════════════
       SIDEBAR
    ═══════════════════════════════════════ */
    .sidebar {
      width:var(--sb-w);
      background:linear-gradient(180deg,#031f17 0%,#042C20 30%,#063D30 100%);
      display:flex; flex-direction:column; position:fixed; top:0; left:0;
      height:100vh; z-index:100; overflow-y:auto; overflow-x:hidden;
      box-shadow:4px 0 32px rgba(0,0,0,.2);
    }
    .sidebar::after {
      content:''; position:absolute; top:0; right:0; width:1px; height:100%;
      background:linear-gradient(180deg,rgba(93,202,165,.15),transparent,rgba(93,202,165,.08));
    }
    .sidebar::-webkit-scrollbar { width:3px; }
    .sidebar::-webkit-scrollbar-thumb { background:rgba(255,255,255,.1); }

    .sb-brand {
      display:flex; align-items:center; gap:.75rem;
      padding:1.3rem 1.1rem 1.1rem;
      border-bottom:1px solid rgba(255,255,255,.06);
    }
    .sb-brand-icon {
      width:36px; height:36px;
      background:linear-gradient(135deg,var(--g300),var(--g700));
      border-radius:10px; display:flex; align-items:center;
      justify-content:center; color:#fff; font-size:1.05rem; flex-shrink:0;
      box-shadow:0 4px 16px rgba(93,202,165,.35);
    }
    .sb-brand-name { font-size:.92rem; font-weight:800; color:#fff; letter-spacing:-.3px; }
    .sb-brand-sub  { font-size:.63rem; color:rgba(255,255,255,.35); margin-top:.05rem; }

    .sb-user {
      display:flex; align-items:center; gap:.7rem; padding:.9rem 1.1rem;
      border-bottom:1px solid rgba(255,255,255,.06);
      text-decoration:none; transition:background .2s;
      background:rgba(0,0,0,.12);
    }
    .sb-user:hover { background:rgba(255,255,255,.06); }
    .sb-avatar {
      width:36px; height:36px; border-radius:50%;
      background:linear-gradient(135deg,var(--g300),var(--g700));
      display:flex; align-items:center; justify-content:center;
      font-size:.88rem; font-weight:800; color:#fff; flex-shrink:0;
      overflow:hidden; border:2px solid rgba(255,255,255,.15);
      box-shadow:0 2px 10px rgba(0,0,0,.25);
    }
    .sb-avatar img { width:100%; height:100%; object-fit:cover; }
    .sb-user-name { font-size:.81rem; font-weight:700; color:#fff; line-height:1.2;
      white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .sb-user-role { font-size:.67rem; color:rgba(255,255,255,.4); }

    .sb-nav  { flex:1; padding:.6rem 0; }
    .sb-section {
      font-size:.61rem; font-weight:700; text-transform:uppercase;
      letter-spacing:1px; color:rgba(255,255,255,.25);
      padding:.75rem 1.1rem .3rem; margin-top:.2rem;
    }
    .sb-link {
      display:flex; align-items:center; gap:.65rem; padding:.52rem 1rem;
      font-size:.81rem; font-weight:500; color:rgba(255,255,255,.58);
      text-decoration:none; transition:all .18s var(--ease);
      margin:1px .45rem; border-radius:var(--r-sm); position:relative;
    }
    .sb-link::before {
      content:''; position:absolute; left:0; top:20%; bottom:20%;
      width:3px; background:linear-gradient(180deg,var(--g300),var(--g500));
      border-radius:0 2px 2px 0; transform:scaleY(0);
      transition:transform .2s var(--ease); transform-origin:center;
    }
    .sb-link:hover {
      background:rgba(255,255,255,.07); color:#fff;
      transform:translateX(3px);
    }
    .sb-link.active {
      background:rgba(93,202,165,.13); color:#fff; font-weight:700;
      box-shadow:inset 0 0 0 1px rgba(93,202,165,.15);
    }
    .sb-link.active::before { transform:scaleY(1); }
    .sb-link i { font-size:.95rem; width:18px; flex-shrink:0; opacity:.85; }
    .sb-link.active i { opacity:1; }
    .nb {
      background:linear-gradient(135deg,#ef4444,#dc2626);
      color:#fff; border-radius:99px; font-size:.63rem;
      padding:1px 6px; font-weight:700; margin-left:auto;
      box-shadow:0 2px 6px rgba(239,68,68,.4);
    }
    .sb-logout {
      display:flex; align-items:center; gap:.65rem; padding:.75rem 1rem;
      font-size:.8rem; font-weight:500; color:rgba(255,255,255,.38);
      text-decoration:none; transition:all .2s; margin:.5rem .45rem;
      border-radius:var(--r-sm); border:1px solid rgba(255,255,255,.07);
    }
    .sb-logout:hover {
      color:#fca5a5; background:rgba(239,68,68,.1);
      border-color:rgba(239,68,68,.25);
    }

    /* ═══════════════════════════════════════
       MAIN WRAP
    ═══════════════════════════════════════ */
    .main-wrap {
      margin-left:var(--sb-w); flex:1; display:flex;
      flex-direction:column; min-height:100vh;
    }

    /* ═══════════════════════════════════════
       TOPBAR
    ═══════════════════════════════════════ */
    .topbar {
      background:rgba(255,255,255,.88);
      backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
      border-bottom:1px solid rgba(0,0,0,.06);
      padding:.65rem 1.5rem; display:flex; align-items:center;
      justify-content:space-between; position:sticky; top:0; z-index:50;
      height:54px;
    }
    .topbar-title {
      font-size:.93rem; font-weight:700; color:var(--gray900);
      letter-spacing:-.3px;
    }
    .topbar-right { display:flex; align-items:center; gap:.65rem; }
    .theme-toggle {
      background:none; border:none; cursor:pointer; font-size:1.05rem;
      color:var(--gray500); padding:.35rem; border-radius:var(--r-sm);
      transition:all .2s; width:34px; height:34px;
      display:flex; align-items:center; justify-content:center;
    }
    .theme-toggle:hover {
      background:var(--gray100); color:var(--gray700);
      transform:rotate(20deg);
    }
    .user-dropdown .dropdown-toggle {
      background:var(--gray50); border:1.5px solid var(--gray200);
      border-radius:var(--r-full); padding:.3rem .85rem .3rem .4rem;
      display:flex; align-items:center; gap:.5rem;
      font-size:.83rem; font-weight:600; color:var(--gray700);
      cursor:pointer; transition:all .2s;
    }
    .user-dropdown .dropdown-toggle:hover {
      border-color:var(--g700); color:var(--g700);
      background:var(--g50); box-shadow:var(--sh-green);
    }
    .user-dropdown .dropdown-toggle::after { display:none; }
    .top-avatar {
      width:27px; height:27px; border-radius:50%;
      background:linear-gradient(135deg,var(--g300),var(--g700));
      display:flex; align-items:center; justify-content:center;
      font-size:.73rem; font-weight:800; color:#fff; flex-shrink:0; overflow:hidden;
    }
    .top-avatar img { width:100%; height:100%; object-fit:cover; }
    .user-dropdown .dropdown-menu {
      border:1px solid rgba(0,0,0,.08); border-radius:var(--r-lg);
      box-shadow:var(--sh-lg); padding:.5rem; min-width:200px;
      animation:fadeUp .2s var(--ease);
    }
    .user-dropdown .dropdown-item {
      border-radius:var(--r-sm); font-size:.84rem;
      padding:.5rem .75rem; font-weight:500; transition:all .15s;
    }
    .user-dropdown .dropdown-item:hover { background:var(--g50); color:var(--g700); }
    .user-dropdown .dropdown-item.text-danger:hover { background:#FEF2F2; color:#dc2626; }
    .user-email-dd { font-size:.72rem; color:var(--gray500); padding:.5rem .75rem .3rem; }

    /* ═══════════════════════════════════════
       CONTENT
    ═══════════════════════════════════════ */
    .content { padding:1.5rem; flex:1; animation:fadeUp .45s var(--ease) both; }

    /* ═══════════════════════════════════════
       SECTION CARD
    ═══════════════════════════════════════ */
    .section-card {
      background:var(--surface); border-radius:var(--r-lg);
      box-shadow:var(--sh-md); border:1px solid var(--border);
      overflow:hidden; transition:box-shadow .25s var(--ease);
    }
    .section-card:hover { box-shadow:var(--sh-lg); }

    /* ═══════════════════════════════════════
       RIPPLE
    ═══════════════════════════════════════ */
    .ripple-effect {
      position:absolute; border-radius:50%;
      background:rgba(255,255,255,.3);
      transform:scale(0); animation:ripple .6s linear;
      pointer-events:none;
    }

    /* ═══════════════════════════════════════
       TOAST
    ═══════════════════════════════════════ */
    .toast-wrap {
      position:fixed; top:1rem; right:1rem; z-index:9999;
      display:flex; flex-direction:column; gap:.5rem; pointer-events:none;
    }
    .toast-msg {
      display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem;
      border-radius:var(--r-md); background:#fff; box-shadow:var(--sh-lg);
      border-left:4px solid var(--g700); font-size:.87rem; font-weight:500;
      color:var(--gray700); animation:fadeUp .3s var(--ease) both;
      min-width:260px; max-width:360px; pointer-events:all;
      transition:opacity .3s,transform .3s;
    }
    .toast-msg.err { border-color:#ef4444; }
    .toast-msg.warn{ border-color:#f59e0b; }

    /* ═══════════════════════════════════════
       SKELETON
    ═══════════════════════════════════════ */
    .skeleton {
      background:linear-gradient(90deg,var(--gray100) 25%,var(--gray200) 50%,var(--gray100) 75%);
      background-size:200% 100%; animation:shimmer 1.5s infinite;
      border-radius:var(--r-sm);
    }

    /* ═══════════════════════════════════════
       DARK MODE
    ═══════════════════════════════════════ */
    [data-bs-theme="dark"] body      { background:#0d1117; color:#d1d5db; }
    [data-bs-theme="dark"] .topbar   {
      background:rgba(22,27,34,.9);
      border-color:rgba(255,255,255,.06);
    }
    [data-bs-theme="dark"] .topbar-title { color:#f0f6fc; }
    [data-bs-theme="dark"] .theme-toggle { color:#8b949e; }
    [data-bs-theme="dark"] .theme-toggle:hover { background:#21262d; color:#f0f6fc; }
    [data-bs-theme="dark"] .section-card { background:#161b22; border-color:rgba(255,255,255,.07); }
    [data-bs-theme="dark"] .user-dropdown .dropdown-toggle {
      background:#21262d; border-color:#30363d; color:#c9d1d9; }
    [data-bs-theme="dark"] .user-dropdown .dropdown-menu {
      background:#161b22; border-color:#30363d; }
    [data-bs-theme="dark"] .user-dropdown .dropdown-item { color:#c9d1d9; }
    [data-bs-theme="dark"] .user-dropdown .dropdown-item:hover { background:#21262d; color:var(--g300); }
    [data-bs-theme="dark"] .toast-msg { background:#161b22; color:#c9d1d9; }
    [data-bs-theme="dark"] ::-webkit-scrollbar-thumb { background:#30363d; }
  </style>
</head>
<body>

<!-- ══ SIDEBAR ══════════════════════════════ -->
<nav class="sidebar">
  <div class="sb-brand">
    <div class="sb-brand-icon"><i class="bi bi-calendar-check-fill"></i></div>
    <div>
      <div class="sb-brand-name">Event Platform</div>
      <div class="sb-brand-sub">Organiser Portal</div>
    </div>
  </div>

  <a href="/WebtechProject/public/organiser/profile" class="sb-user">
    <div class="sb-avatar">
      <?php if ($_headerLogo): ?>
        <img src="/WebtechProject/public/<?= htmlspecialchars($_headerLogo) ?>" alt=""/>
      <?php else: ?>
        <?= $userInitial ?>
      <?php endif; ?>
    </div>
    <div style="min-width:0;">
      <div class="sb-user-name"><?= htmlspecialchars($userName) ?></div>
      <div class="sb-user-role">Organiser · Approved</div>
    </div>
    <i class="bi bi-chevron-right" style="color:rgba(255,255,255,.2);font-size:.7rem;margin-left:auto;flex-shrink:0;"></i>
  </a>

  <div class="sb-nav">
    <div class="sb-section">Main</div>
    <a href="/WebtechProject/public/organiser/dashboard"      class="<?= nav('dashboard',      $activePage) ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <a href="/WebtechProject/public/organiser/events"         class="<?= nav('events',         $activePage) ?>"><i class="bi bi-calendar-event-fill"></i> My Events</a>
    <a href="/WebtechProject/public/organiser/analytics"      class="<?= nav('analytics',      $activePage) ?>"><i class="bi bi-bar-chart-line-fill"></i> Analytics</a>

    <div class="sb-section">Tickets</div>
    <a href="/WebtechProject/public/organiser/checkin"        class="<?= nav('checkin',        $activePage) ?>"><i class="bi bi-qr-code-scan"></i> Live Check-in</a>
    <a href="/WebtechProject/public/organiser/bookings"       class="<?= nav('bookings',       $activePage) ?>"><i class="bi bi-ticket-perforated-fill"></i> Bookings</a>
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
    <a href="/WebtechProject/public/organiser/venues"         class="<?= nav('venues',         $activePage) ?>"><i class="bi bi-building-fill"></i> Browse Venues</a>
    <a href="/WebtechProject/public/organiser/venue-requests" class="<?= nav('venue-requests', $activePage) ?>"><i class="bi bi-send-fill"></i> My Requests</a>

    <div class="sb-section">Account</div>
    <a href="/WebtechProject/public/organiser/profile"        class="<?= nav('profile',        $activePage) ?>"><i class="bi bi-person-circle"></i> My Profile</a>
  </div>

  <a href="/WebtechProject/public/logout" class="sb-logout">
    <i class="bi bi-box-arrow-left"></i> Sign out
  </a>
</nav>

<!-- ══ MAIN ═════════════════════════════════ -->
<div class="main-wrap">
  <div class="topbar">
    <span class="topbar-title"><?= htmlspecialchars($pageTitle ?? '') ?></span>
    <div class="topbar-right">
      <button class="theme-toggle" id="themeToggle" title="Toggle dark mode">
        <i class="bi bi-moon-fill" id="themeIcon"></i>
      </button>
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
          <i class="bi bi-chevron-down" style="font-size:.6rem;color:var(--gray300);"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><div class="user-email-dd"><?= htmlspecialchars($userEmail) ?></div></li>
          <li><hr class="dropdown-divider" style="margin:.3rem 0;"/></li>
          <li>
            <a class="dropdown-item" href="/WebtechProject/public/organiser/profile">
              <i class="bi bi-person-circle me-2" style="color:var(--g700);"></i>My Profile
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

<!-- Toast container -->
<div class="toast-wrap" id="toastWrap"></div>

<script>
(function(){
  const s=localStorage.getItem('ep_theme')||'light';
  document.documentElement.setAttribute('data-bs-theme',s);
})();
document.addEventListener('DOMContentLoaded',function(){
  const s=localStorage.getItem('ep_theme')||'light';
  const ic=document.getElementById('themeIcon');
  if(ic) ic.className=s==='dark'?'bi bi-sun-fill':'bi bi-moon-fill';
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Theme toggle
document.getElementById('themeToggle')?.addEventListener('click',function(){
  const h=document.documentElement;
  const n=h.getAttribute('data-bs-theme')==='dark'?'light':'dark';
  h.setAttribute('data-bs-theme',n);
  localStorage.setItem('ep_theme',n);
  document.getElementById('themeIcon').className=n==='dark'?'bi bi-sun-fill':'bi bi-moon-fill';
});

// Ripple on buttons
document.addEventListener('click',function(e){
  const btn=e.target.closest('button,a.qa-btn,a.sb-link,.btn-ci,.btn-add,.btn-ev,.btn-req');
  if(!btn||btn.dataset.noRipple) return;
  const r=document.createElement('span');
  const d=Math.max(btn.offsetWidth,btn.offsetHeight);
  const rect=btn.getBoundingClientRect();
  r.className='ripple-effect';
  r.style.cssText=`width:${d}px;height:${d}px;left:${e.clientX-rect.left-d/2}px;top:${e.clientY-rect.top-d/2}px;`;
  btn.style.position='relative'; btn.style.overflow='hidden';
  btn.appendChild(r);
  setTimeout(()=>r.remove(),700);
});

// Toast system
function showToast(msg,type='success'){
  const wrap=document.getElementById('toastWrap');
  const t=document.createElement('div');
  t.className='toast-msg'+(type==='error'?' err':type==='warn'?' warn':'');
  const icons={success:'bi-check-circle-fill',error:'bi-x-circle-fill',warn:'bi-exclamation-triangle-fill'};
  const colors={success:'var(--g700)',error:'#ef4444',warn:'#f59e0b'};
  t.innerHTML=`<i class="bi ${icons[type]||icons.success}" style="color:${colors[type]||colors.success};font-size:1rem;flex-shrink:0;"></i><span>${msg}</span>`;
  wrap.appendChild(t);
  setTimeout(()=>{
    t.style.opacity='0'; t.style.transform='translateX(20px)';
    setTimeout(()=>t.remove(),350);
  },3500);
}

// Number count-up animation
function countUp(el,target,duration=1200){
  const start=performance.now();
  const from=0;
  function update(time){
    const p=Math.min((time-start)/duration,1);
    const ease=p<.5?2*p*p:(4-2*p)*p-1;
    el.textContent=Math.round(from+(target-from)*ease).toLocaleString();
    if(p<1) requestAnimationFrame(update);
  }
  requestAnimationFrame(update);
}

// Form loading states
document.addEventListener('DOMContentLoaded',function(){
  document.querySelectorAll('form[method="POST"]').forEach(function(form){
    form.addEventListener('submit',function(){
      const btn=form.querySelector('button[type="submit"]:not([data-no-load])');
      if(btn){
        const orig=btn.innerHTML;
        btn.disabled=true;
        btn.innerHTML='<span class="spinner-border spinner-border-sm me-2"></span>Please wait...';
        setTimeout(()=>{btn.disabled=false;btn.innerHTML=orig;},5000);
      }
    });
  });
});
/* ═══════════════════════════════════════
   GLOBAL PAGE IMPROVEMENTS
═══════════════════════════════════════ */

body {
  background:#e8edf3;
  background-image:radial-gradient(#c8d0db 1px, transparent 1px);
  background-size:24px 24px;
}
[data-bs-theme="dark"] body {
  background:#0d1117!important;
  background-image:radial-gradient(rgba(255,255,255,.035) 1px, transparent 1px)!important;
  background-size:24px 24px!important;
}

/* Better form controls globally */
.form-control, .form-select {
  border:1.5px solid #e2e8f0 !important;
  border-radius:10px !important;
  font-family:'Inter',sans-serif !important;
  font-size:.9rem !important;
  padding:.65rem .9rem !important;
  transition:all .2s var(--ease) !important;
  background:#fff !important;
  color:#374151 !important;
}
.form-control:focus, .form-select:focus {
  border-color:var(--g700) !important;
  box-shadow:0 0 0 3px rgba(15,110,86,.1) !important;
  background:#fff !important;
}
[data-bs-theme="dark"] .form-control,
[data-bs-theme="dark"] .form-select {
  background:#1e2536 !important;
  border-color:#2d3748 !important;
  color:#e2e8f0 !important;
}
[data-bs-theme="dark"] .form-control:focus,
[data-bs-theme="dark"] .form-select:focus {
  border-color:var(--g300) !important;
  box-shadow:0 0 0 3px rgba(93,202,165,.12) !important;
}

/* Better stat/summary cards used across pages */
.summary-card, .stat-ci, .kpi-card {
  background:#fff;
  border-radius:var(--r-lg) !important;
  border:1px solid rgba(0,0,0,.05) !important;
  box-shadow:var(--sh-md) !important;
  transition:all .25s var(--ease) !important;
}
.summary-card:hover, .stat-ci:hover {
  transform:translateY(-3px);
  box-shadow:var(--sh-lg) !important;
}
[data-bs-theme="dark"] .summary-card,
[data-bs-theme="dark"] .stat-ci {
  background:#1a2030 !important;
  border-color:rgba(255,255,255,.06) !important;
}

/* Better filter bars */
.filter-bar {
  background:#fff !important;
  border-radius:var(--r-lg) !important;
  border:1px solid rgba(0,0,0,.05) !important;
  box-shadow:var(--sh-sm) !important;
  padding:1rem 1.25rem !important;
}
[data-bs-theme="dark"] .filter-bar {
  background:#1a2030 !important;
  border-color:rgba(255,255,255,.06) !important;
}

/* Better buttons globally */
button[type="submit"]:not(.btn-auth):not(.btn-ci):not(.btn-qr),
.btn-add, .btn-send, .btn-submit-reply {
  border-radius:var(--r-md) !important;
  font-family:'Inter',sans-serif !important;
  font-weight:600 !important;
  transition:all .2s var(--ease) !important;
}

/* Better page headings */
h4.fw-bold {
  font-size:1.15rem !important;
  letter-spacing:-.3px !important;
  color:#0f172a !important;
}
[data-bs-theme="dark"] h4.fw-bold { color:#f1f5f9 !important; }

/* Pill/badge improvements */
.pill {
  font-size:.74rem !important;
  padding:3px 10px !important;
  border-radius:99px !important;
  font-weight:600 !important;
  letter-spacing:.1px !important;
}

/* Better table styling */
.bk-table th {
  background:linear-gradient(180deg,#f8fafc,#f1f5f9) !important;
  color:#64748b !important;
  font-size:.68rem !important;
  letter-spacing:.7px !important;
  border-bottom:2px solid #e2e8f0 !important;
}
.bk-table tr:hover td { background:#f0fdf4 !important; }
[data-bs-theme="dark"] .bk-table th {
  background:#1e2536 !important;
  color:#64748b !important;
  border-color:#2d3748 !important;
}
[data-bs-theme="dark"] .bk-table tr:hover td { background:#1c2a1f !important; }

/* Card content sections */
.section-card {
  border-radius:var(--r-lg) !important;
  box-shadow:var(--sh-md) !important;
  border:1px solid rgba(0,0,0,.05) !important;
}
.section-card:hover {
  box-shadow:0 8px 32px rgba(0,0,0,.1) !important;
}

/* Better filter tabs */
.filter-tab {
  border-radius:99px !important;
  font-weight:600 !important;
  font-size:.82rem !important;
  transition:all .18s var(--ease) !important;
}
.filter-tab.active {
  background:linear-gradient(135deg,var(--g700),var(--g500)) !important;
  border-color:transparent !important;
  box-shadow:0 4px 14px rgba(15,110,86,.3) !important;
}

/* Form section cards */
.form-section {
  border-radius:var(--r-lg) !important;
  box-shadow:var(--sh-md) !important;
  border:1px solid rgba(0,0,0,.05) !important;
}
[data-bs-theme="dark"] .form-section {
  background:#1a2030 !important;
  border-color:rgba(255,255,255,.06) !important;
}

/* Venue cards */
.venue-card {
  border-radius:var(--r-lg) !important;
  box-shadow:var(--sh-md) !important;
  border:1px solid rgba(0,0,0,.05) !important;
  transition:all .25s var(--ease) !important;
}
.venue-card:hover {
  transform:translateY(-4px) !important;
  box-shadow:var(--sh-lg) !important;
}

/* Review cards */
.rev-card, .req-card, .ann-item {
  border-radius:var(--r-lg) !important;
  box-shadow:var(--sh-md) !important;
  border:1px solid rgba(0,0,0,.05) !important;
  transition:box-shadow .2s !important;
}
.rev-card:hover, .req-card:hover {
  box-shadow:var(--sh-lg) !important;
}

/* Code card discount */
.code-card {
  border-radius:var(--r-lg) !important;
  box-shadow:var(--sh-md) !important;
  border:1px solid rgba(0,0,0,.05) !important;
  transition:all .25s var(--ease) !important;
}
.code-card:hover {
  transform:translateY(-3px) !important;
  box-shadow:var(--sh-lg) !important;
}

/* Analytics cards */
.an-card {
  border-radius:var(--r-lg) !important;
  box-shadow:var(--sh-md) !important;
  transition:all .25s var(--ease) !important;
}
.an-card:hover { box-shadow:var(--sh-lg) !important; }

/* Scanner wrap */
.scanner-wrap {
  border-radius:var(--r-xl) !important;
  box-shadow:0 16px 48px rgba(0,0,0,.2) !important;
}

/* Feed card */
.feed-card {
  border-radius:var(--r-lg) !important;
  box-shadow:var(--sh-md) !important;
}

/* Chart cards */
.chart-card {
  border-radius:var(--r-lg) !important;
  box-shadow:var(--sh-md) !important;
  transition:box-shadow .25s !important;
}
.chart-card:hover { box-shadow:var(--sh-lg) !important; }
</script>