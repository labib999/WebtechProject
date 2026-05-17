<?php
$pageTitle  = 'My Events';
$activePage = 'events';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
.page-header {
  display:flex; justify-content:space-between; align-items:flex-start;
  margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;
}
.page-title  { font-size:1.4rem; font-weight:900; color:#0f172a; letter-spacing:-.4px; }
.page-sub    { font-size:.84rem; color:var(--gray500); margin-top:.15rem; }
[data-bs-theme="dark"] .page-title { color:#f1f5f9; }

.create-btn {
  display:inline-flex; align-items:center; gap:.5rem;
  padding:.65rem 1.25rem; border-radius:var(--r-full);
  background:linear-gradient(135deg,var(--g800),var(--g500));
  color:#fff; font-size:.88rem; font-weight:700;
  text-decoration:none; transition:all .2s;
  box-shadow:0 4px 16px rgba(15,110,86,.35);
  border:none; cursor:pointer; font-family:'Inter',sans-serif;
}
.create-btn:hover {
  transform:translateY(-2px); color:#fff;
  box-shadow:0 8px 24px rgba(15,110,86,.45);
}

/* Search */
.search-wrap {
  display:flex; align-items:center; gap:.75rem; margin-bottom:1.1rem;
}
.search-box {
  flex:1; max-width:380px; display:flex; align-items:center;
  background:#fff; border:1.5px solid #e2e8f0; border-radius:var(--r-full);
  padding:.5rem .9rem; gap:.5rem;
  box-shadow:var(--sh-sm); transition:all .2s;
}
.search-box:focus-within {
  border-color:var(--g700);
  box-shadow:0 0 0 3px rgba(15,110,86,.1),var(--sh-sm);
}
.search-box i { color:#94a3b8; font-size:1rem; flex-shrink:0; }
.search-box input {
  border:none; outline:none; background:none; font-family:'Inter',sans-serif;
  font-size:.9rem; color:#374151; width:100%;
}
.search-box input::placeholder { color:#94a3b8; }
[data-bs-theme="dark"] .search-box {
  background:#1a2030; border-color:#2d3748;
}
[data-bs-theme="dark"] .search-box input { color:#e2e8f0; }

/* Filter tabs */
.tab-row { display:flex; flex-wrap:wrap; gap:.4rem; margin-bottom:1.25rem; }
.tab-pill {
  display:inline-flex; align-items:center; gap:.4rem;
  padding:.38rem .9rem; border-radius:var(--r-full);
  font-size:.82rem; font-weight:600; text-decoration:none;
  border:1.5px solid #e2e8f0; color:#64748b;
  background:#fff; transition:all .18s; white-space:nowrap;
  box-shadow:var(--sh-sm);
}
.tab-pill:hover { border-color:var(--g300); color:var(--g700); }
.tab-pill.active {
  background:linear-gradient(135deg,var(--g700),var(--g500));
  border-color:transparent; color:#fff;
  box-shadow:0 4px 14px rgba(15,110,86,.3);
}
.tab-count {
  background:rgba(255,255,255,.25); border-radius:99px;
  font-size:.7rem; padding:1px 6px; font-weight:700;
}
.tab-pill:not(.active) .tab-count {
  background:#f1f5f9; color:#64748b;
}
[data-bs-theme="dark"] .tab-pill:not(.active) {
  background:#1a2030; border-color:#2d3748; color:#94a3b8;
}

/* Events list card */
.events-card {
  background:#fff; border-radius:var(--r-xl);
  box-shadow:var(--sh-md); border:1px solid rgba(0,0,0,.05);
  overflow:hidden;
}
[data-bs-theme="dark"] .events-card {
  background:#1a2030; border-color:rgba(255,255,255,.06);
}

.ev-row {
  display:flex; align-items:center; gap:1rem;
  padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9;
  transition:all .18s var(--ease);
}
.ev-row:last-child { border-bottom:none; }
.ev-row:hover { background:#f8fffe; }
[data-bs-theme="dark"] .ev-row { border-color:#1e2a3a; }
[data-bs-theme="dark"] .ev-row:hover { background:#1c2d2a; }

.ev-thumb {
  width:54px; height:54px; border-radius:14px; flex-shrink:0;
  overflow:hidden; display:flex; align-items:center; justify-content:center;
  background:linear-gradient(135deg,var(--g100),var(--g50));
  color:var(--g700); font-size:1.35rem;
  border:1px solid var(--g100);
}
.ev-thumb img { width:100%; height:100%; object-fit:cover; }

.ev-info { flex:1; min-width:0; }
.ev-title {
  font-size:.95rem; font-weight:700; color:#0f172a;
  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
  margin-bottom:.25rem; letter-spacing:-.2px;
}
[data-bs-theme="dark"] .ev-title { color:#f1f5f9; }
.ev-meta {
  display:flex; flex-wrap:wrap; gap:.6rem; align-items:center;
}
.ev-meta span {
  display:inline-flex; align-items:center; gap:.3rem;
  font-size:.76rem; color:#94a3b8;
}
.ev-meta i { font-size:.8rem; }
.ev-countdown {
  background:var(--g50); color:var(--g700);
  padding:2px 8px; border-radius:99px;
  font-size:.73rem; font-weight:700;
  border:1px solid var(--g100);
}
.ev-countdown.past { background:#fef2f2; color:#dc2626; border-color:#fecaca; }
.ev-countdown.today { background:#fffbeb; color:#d97706; border-color:#fde68a; }

.ev-stats { text-align:right; min-width:90px; flex-shrink:0; }
.ev-revenue { font-size:1rem; font-weight:800; color:#0f172a; letter-spacing:-.3px; }
[data-bs-theme="dark"] .ev-revenue { color:#f1f5f9; }
.ev-bookings { font-size:.74rem; color:#94a3b8; margin-top:.1rem; }

.ev-status { min-width:90px; text-align:center; flex-shrink:0; }
.status-pill {
  display:inline-flex; align-items:center; gap:.35rem;
  padding:4px 12px; border-radius:99px; font-size:.76rem; font-weight:700;
}
.sp-published { background:#dcfce7; color:#15803d; }
.sp-draft     { background:#f1f5f9; color:#475569; }
.sp-cancelled { background:#fef2f2; color:#dc2626; }
.sp-completed { background:#eff6ff; color:#1d4ed8; }

.ev-actions { display:flex; gap:.4rem; flex-shrink:0; }
.act-btn {
  display:inline-flex; align-items:center; gap:.3rem;
  padding:.35rem .75rem; border-radius:var(--r-sm);
  font-size:.78rem; font-weight:600; cursor:pointer;
  text-decoration:none; border:1.5px solid; background:none;
  transition:all .15s; font-family:'Inter',sans-serif;
  white-space:nowrap;
}
.act-edit { color:#475569; border-color:#e2e8f0; }
.act-edit:hover { background:#f8fafc; color:#0f172a; border-color:#cbd5e1; }
.act-pub  { color:#15803d; border-color:#86efac; }
.act-pub:hover  { background:#f0fdf4; }
.act-cxl  { color:#dc2626; border-color:#fecaca; }
.act-cxl:hover  { background:#fef2f2; }

/* Empty state */
.empty-state {
  text-align:center; padding:4rem 2rem; color:#94a3b8;
}
.empty-icon {
  width:72px; height:72px; border-radius:20px;
  background:linear-gradient(135deg,var(--g100),var(--g50));
  display:flex; align-items:center; justify-content:center;
  font-size:1.8rem; color:var(--g300); margin:0 auto 1.25rem;
}
.empty-title { font-size:1rem; font-weight:700; color:#475569; margin-bottom:.4rem; }
.empty-sub   { font-size:.86rem; color:#94a3b8; margin-bottom:1.5rem; }

/* No results */
#noResults {
  display:none; text-align:center; padding:3rem;
  color:#94a3b8; font-size:.9rem;
}
</style>

<!-- Page Header -->
<div class="page-header">
  <div>
    <div class="page-title">My Events</div>
    <div class="page-sub">
      <span id="evCount"><?= $statusCounts['all'] ?></span>
      event<?= $statusCounts['all']!=1?'s':'' ?> total
    </div>
  </div>
  <a href="/WebtechProject/public/organiser/events/create" class="create-btn">
    <i class="bi bi-plus-circle-fill"></i> Create New Event
  </a>
</div>

<!-- Flash -->
<?php if (!empty($success)): ?>
  <div style="display:flex;align-items:center;gap:.6rem;padding:.8rem 1rem;
              background:#f0fdf4;border:1px solid #86efac;color:#15803d;
              border-radius:var(--r-md);font-size:.88rem;margin-bottom:1rem;font-weight:500;">
    <i class="bi bi-check-circle-fill"></i><?= htmlspecialchars($success) ?>
  </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <div style="display:flex;align-items:center;gap:.6rem;padding:.8rem 1rem;
              background:#fef2f2;border:1px solid #fecaca;color:#dc2626;
              border-radius:var(--r-md);font-size:.88rem;margin-bottom:1rem;font-weight:500;">
    <i class="bi bi-exclamation-circle-fill"></i><?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<!-- Search -->
<div class="search-wrap">
  <div class="search-box">
    <i class="bi bi-search"></i>
    <input type="text" id="liveSearch" placeholder="Search events by title..."/>
  </div>
</div>

<!-- Filter tabs -->
<div class="tab-row">
  <?php
  $tabs=['all'=>'All','published'=>'Published','draft'=>'Draft',
         'cancelled'=>'Cancelled','completed'=>'Completed'];
  foreach($tabs as $key=>$label):
    $cnt=$statusCounts[$key]??0;
    if($key!=='all'&&$cnt===0) continue;
  ?>
  <a href="/WebtechProject/public/organiser/events<?= $key!=='all'?'?status='.$key:'' ?>"
     class="tab-pill <?= $filter===$key?'active':'' ?>">
    <?= $label ?><span class="tab-count"><?= $cnt ?></span>
  </a>
  <?php endforeach; ?>
</div>

<!-- Events list -->
<div class="events-card">
  <?php if (empty($events)): ?>
    <div class="empty-state">
      <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
      <div class="empty-title">No <?= $filter!=='all'?$filter.' ':'' ?>events yet</div>
      <div class="empty-sub">
        <?= $filter==='all'?'Create your first event to start selling tickets.':'No events with this status.' ?>
      </div>
      <?php if($filter==='all'): ?>
        <a href="/WebtechProject/public/organiser/events/create" class="create-btn">
          <i class="bi bi-plus-circle-fill"></i> Create First Event
        </a>
      <?php endif; ?>
    </div>
  <?php else: foreach($events as $ev):
    $eventDate = strtotime($ev['event_datetime']);
    $today     = strtotime('today');
    $diff      = (int)(($eventDate-$today)/86400);
    if($diff>0)       { $cdClass='ev-countdown'; $cdText="in {$diff}d"; }
    elseif($diff===0) { $cdClass='ev-countdown today'; $cdText='Today!'; }
    else              { $cdClass='ev-countdown past'; $cdText=abs($diff).'d ago'; }
  ?>
  <div class="ev-row">
    <div class="ev-thumb">
      <?php if(!empty($ev['banner_image_path'])): ?>
        <img src="/WebtechProject/public/<?= htmlspecialchars($ev['banner_image_path']) ?>" alt=""/>
      <?php else: ?>
        <i class="bi bi-calendar-event-fill"></i>
      <?php endif; ?>
    </div>

    <div class="ev-info">
      <div class="ev-title"><?= htmlspecialchars($ev['title']) ?></div>
      <div class="ev-meta">
        <span><i class="bi bi-calendar3"></i><?= date('d M Y', $eventDate) ?></span>
        <span><i class="bi bi-clock"></i><?= date('g:i A', $eventDate) ?></span>
        <?php if(!empty($ev['category_name'])): ?>
          <span><i class="bi bi-tag"></i><?= htmlspecialchars($ev['category_name']) ?></span>
        <?php endif; ?>
        <span class="<?= $cdClass ?>"><?= $cdText ?></span>
      </div>
    </div>

    <div class="ev-stats">
      <div class="ev-revenue">$<?= number_format($ev['revenue'],0) ?></div>
      <div class="ev-bookings"><?= $ev['bookings_count'] ?> booking<?= $ev['bookings_count']!=1?'s':'' ?></div>
    </div>

    <div class="ev-status">
      <span class="status-pill sp-<?= $ev['status'] ?>">
        <?php
          $icons=['published'=>'bi-circle-fill','draft'=>'bi-circle','cancelled'=>'bi-x-circle-fill','completed'=>'bi-check-circle-fill'];
          echo '<i class="bi '.$icons[$ev['status']].'"></i>';
        ?>
        <?= ucfirst($ev['status']) ?>
      </span>
    </div>

    <div class="ev-actions">
      <a href="/WebtechProject/public/organiser/events/edit?id=<?= $ev['id'] ?>"
         class="act-btn act-edit">
        <i class="bi bi-pencil"></i> Edit
      </a>
      <?php if($ev['status']==='draft'): ?>
        <form method="POST" action="/WebtechProject/public/organiser/events/publish" style="margin:0;">
          <input type="hidden" name="event_id" value="<?= $ev['id'] ?>"/>
          <button type="submit" class="act-btn act-pub">
            <i class="bi bi-send-fill"></i> Publish
          </button>
        </form>
      <?php elseif($ev['status']==='published'): ?>
        <form method="POST" action="/WebtechProject/public/organiser/events/cancel" style="margin:0;"
              onsubmit="return confirm('Cancel this event?')">
          <input type="hidden" name="event_id" value="<?= $ev['id'] ?>"/>
          <button type="submit" class="act-btn act-cxl">
            <i class="bi bi-x-circle"></i> Cancel
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; endif; ?>

  <div id="noResults">
    <i class="bi bi-search" style="font-size:1.5rem;display:block;margin-bottom:.5rem;"></i>
    No events match your search.
  </div>
</div>

<script>
const liveSearch = document.getElementById('liveSearch');
const noResults  = document.getElementById('noResults');
const countEl    = document.getElementById('evCount');
const total      = <?= count($events) ?>;

liveSearch?.addEventListener('input', function() {
  const term = this.value.toLowerCase().trim();
  const rows = document.querySelectorAll('.ev-row');
  let visible = 0;
  rows.forEach(row => {
    const title = row.querySelector('.ev-title')?.textContent.toLowerCase();
    const show  = !term || title?.includes(term);
    row.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  noResults.style.display = visible===0 && term ? 'block' : 'none';
  countEl.textContent = visible;
});
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>