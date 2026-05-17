<?php
$pageTitle  = 'My Events';
$activePage = 'events';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .filter-tab { padding:.4rem 1.1rem; border-radius:20px; font-size:.82rem; font-weight:600;
    text-decoration:none; border:1.5px solid #e5e7eb; color:#6b7280; transition:all .15s; }
  .filter-tab:hover { border-color:#0F6E56; color:#0F6E56; }
  .filter-tab.active { background:#0F6E56; color:#fff; border-color:#0F6E56; }
  .filter-badge { background:rgba(255,255,255,.25); border-radius:10px; font-size:.68rem; padding:1px 6px; margin-left:3px; }
  .filter-tab:not(.active) .filter-badge { background:#f3f4f6; color:#6b7280; }

  .ev-row { display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem;
    border-bottom:1px solid #f3f4f6; transition:background .12s; }
  .ev-row:last-child { border-bottom:none; }
  .ev-row:hover { background:#f8fffd; }
  .ev-thumb { width:72px; height:50px; border-radius:8px; flex-shrink:0; overflow:hidden;
    background:#E1F5EE; display:flex; align-items:center; justify-content:center;
    color:#0F6E56; font-size:1.3rem; }
  .ev-thumb img { width:100%; height:100%; object-fit:cover; }
  .ev-title { font-size:.9rem; font-weight:700; color:#111; margin-bottom:.15rem; }
  .ev-meta  { font-size:.75rem; color:#9ca3af; display:flex; flex-wrap:wrap; gap:.6rem; }
  .st-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
  .st-published { background:#ECFDF5; color:#065f46; }
  .st-draft      { background:#F3F4F6; color:#374151; }
  .st-cancelled  { background:#FEF2F2; color:#991b1b; }
  .st-completed  { background:#EFF6FF; color:#1e40af; }
  .btn-ev { padding:.3rem .75rem; border-radius:7px; font-size:.78rem; font-weight:600;
    text-decoration:none; border:1.5px solid; cursor:pointer; background:none;
    transition:all .15s; display:inline-flex; align-items:center; gap:.3rem; }
  .btn-ev-edit  { color:#374151; border-color:#e5e7eb; }
  .btn-ev-edit:hover  { background:#f3f4f6; }
  .btn-ev-pub   { color:#065f46; border-color:#6ee7b7; }
  .btn-ev-pub:hover   { background:#ECFDF5; }
  .btn-ev-cxl   { color:#991b1b; border-color:#fecaca; }
  .btn-ev-cxl:hover   { background:#FEF2F2; }
  .qa-btn { display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1.1rem;
    border-radius:10px; font-size:.84rem; font-weight:600; text-decoration:none;
    border:1.5px solid transparent; transition:all .16s; }
  .qa-primary { background:#0F6E56; color:#fff; border-color:#0F6E56; }
  .qa-primary:hover { background:#063D30; color:#fff; }
  .empty-state { text-align:center; padding:4rem 2rem; color:#9ca3af; }
  .empty-state i { font-size:3rem; display:block; margin-bottom:1rem; color:#d1d5db; }
  .empty-state h5 { font-size:1rem; font-weight:700; color:#374151; }

  [data-bs-theme="dark"] .ev-row         { border-color:#253245; }
  [data-bs-theme="dark"] .ev-row:hover   { background:#1c2e3a; }
  [data-bs-theme="dark"] .ev-title       { color:#f3f4f6; }
  [data-bs-theme="dark"] .ev-meta        { color:#6b7280; }
  [data-bs-theme="dark"] .filter-tab:not(.active) { border-color:#374151; color:#9ca3af; }
  [data-bs-theme="dark"] .btn-ev-edit    { border-color:#374151; color:#d1d5db; }
  [data-bs-theme="dark"] .st-draft       { background:#1f2937; color:#9ca3af; }
</style>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1.1rem;">My Events</h4>
    <p class="text-muted mb-0" style="font-size:.81rem;">
      <span id="evCount"><?= $statusCounts['all'] ?></span>
      event<?= $statusCounts['all'] != 1 ? 's' : '' ?> total
    </p>
  </div>
  <a href="/WebtechProject/public/organiser/events/create" class="qa-btn qa-primary">
    <i class="bi bi-plus-circle-fill"></i> Create New Event
  </a>
</div>

<!-- Flash messages -->
<?php if (!empty($success)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#ECFDF5;border:1px solid #6ee7b7;color:#065f46;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-check-circle-fill"></i><span><?= htmlspecialchars($success) ?></span>
  </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#FEF2F2;border:1px solid #fecaca;color:#991b1b;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-exclamation-circle-fill"></i><span><?= htmlspecialchars($error) ?></span>
  </div>
<?php endif; ?>

<!-- Live search bar -->
<div class="mb-3" style="max-width:400px;">
  <div class="input-group">
    <span class="input-group-text" style="background:#f8fafc;border-color:#e5e7eb;color:#9ca3af;">
      <i class="bi bi-search"></i>
    </span>
    <input type="text" id="liveSearch" class="form-control"
           placeholder="Search events by title..."
           style="border-color:#e5e7eb;"/>
    <button class="btn btn-outline-secondary" type="button" id="clearSearch"
            style="display:none;" onclick="clearSearch()">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
</div>

<!-- Filter tabs -->
<div class="d-flex flex-wrap gap-2 mb-4">
  <?php
  $tabs = ['all'=>'All','published'=>'Published','draft'=>'Draft','cancelled'=>'Cancelled','completed'=>'Completed'];
  foreach ($tabs as $key => $label):
    $cnt = $statusCounts[$key] ?? 0;
    if ($key !== 'all' && $cnt === 0) continue;
  ?>
  <a href="/WebtechProject/public/organiser/events<?= $key !== 'all' ? '?status='.$key : '' ?>"
     class="filter-tab <?= $filter === $key ? 'active' : '' ?>">
    <?= $label ?><span class="filter-badge"><?= $cnt ?></span>
  </a>
  <?php endforeach; ?>
</div>

<!-- Events list -->
<div class="section-card">
  <?php if (empty($events)): ?>
    <div class="empty-state">
      <i class="bi bi-calendar-x"></i>
      <h5>No <?= $filter !== 'all' ? $filter . ' ' : '' ?>events yet</h5>
      <p style="font-size:.85rem;margin-bottom:1.5rem;">
        <?= $filter === 'all' ? 'Create your first event to start selling tickets.' : 'No events with this status.' ?>
      </p>
      <?php if ($filter === 'all'): ?>
        <a href="/WebtechProject/public/organiser/events/create" class="qa-btn qa-primary">
          <i class="bi bi-plus-circle-fill"></i> Create First Event
        </a>
      <?php endif; ?>
    </div>
  <?php else: foreach ($events as $ev): ?>
  <div class="ev-row">
    <div class="ev-thumb">
      <?php if (!empty($ev['banner_image_path'])): ?>
        <img src="/WebtechProject/public/<?= htmlspecialchars($ev['banner_image_path']) ?>" alt=""/>
      <?php else: ?>
        <i class="bi bi-calendar-event"></i>
      <?php endif; ?>
    </div>

    <div style="flex:1;min-width:0;">
      <div class="ev-title text-truncate"><?= htmlspecialchars($ev['title']) ?></div>
      <div class="ev-meta">
        <?php
$eventDate = strtotime($ev['event_datetime']);
$today     = strtotime('today');
$diff      = (int)(($eventDate - $today) / 86400);
if ($diff > 0)      $countdown = "in {$diff} day" . ($diff!=1?'s':'');
elseif ($diff === 0) $countdown = "Today!";
else                 $countdown = abs($diff) . " days ago";
?>
<span><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($ev['event_datetime'])) ?></span>
<span style="background:#f0faf5;color:#0F6E56;padding:1px 7px;border-radius:10px;font-size:.72rem;font-weight:600;">
  <?= $countdown ?>
</span>

        <span><i class="bi bi-clock me-1"></i><?= date('g:i A', strtotime($ev['event_datetime'])) ?></span>
        <?php if (!empty($ev['venue_name_override'])): ?>
          <span><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($ev['venue_name_override']) ?></span>
        <?php endif; ?>
        <?php if (!empty($ev['category_name'])): ?>
          <span><i class="bi bi-tag me-1"></i><?= htmlspecialchars($ev['category_name']) ?></span>
        <?php endif; ?>
      </div>
    </div>

    <div style="text-align:right;min-width:90px;flex-shrink:0;">
      <div style="font-size:.9rem;font-weight:700;">$<?= number_format($ev['revenue'],0) ?></div>
      <div style="font-size:.74rem;color:#9ca3af;"><?= $ev['bookings_count'] ?> booking<?= $ev['bookings_count']!=1?'s':'' ?></div>
    </div>

    <div style="min-width:90px;text-align:center;flex-shrink:0;">
      <span class="st-badge st-<?= $ev['status'] ?>"><?= ucfirst($ev['status']) ?></span>
    </div>

    <div class="d-flex gap-2" style="flex-shrink:0;">
      <a href="/WebtechProject/public/organiser/events/edit?id=<?= $ev['id'] ?>"
         class="btn-ev btn-ev-edit"><i class="bi bi-pencil"></i> Edit</a>

      <?php if ($ev['status'] === 'draft'): ?>
        <form method="POST" action="/WebtechProject/public/organiser/events/publish" style="margin:0;">
          <input type="hidden" name="event_id" value="<?= $ev['id'] ?>"/>
          <button type="submit" class="btn-ev btn-ev-pub">
            <i class="bi bi-send-fill"></i> Publish
          </button>
        </form>
      <?php elseif ($ev['status'] === 'published'): ?>
        <form method="POST" action="/WebtechProject/public/organiser/events/cancel" style="margin:0;"
              onsubmit="return confirm('Cancel this event?')">
          <input type="hidden" name="event_id" value="<?= $ev['id'] ?>"/>
          <button type="submit" class="btn-ev btn-ev-cxl">
            <i class="bi bi-x-circle"></i> Cancel
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; endif; ?>

  <!-- No results message -->
  <div id="noResults" style="display:none;text-align:center;padding:3rem;color:#9ca3af;">
    <i class="bi bi-search" style="font-size:2rem;display:block;margin-bottom:.5rem;color:#d1d5db;"></i>
    <p style="font-size:.88rem;">No events match your search.</p>
  </div>
</div>

<script>
const searchInput  = document.getElementById('liveSearch');
const clearBtn     = document.getElementById('clearSearch');
const noResults    = document.getElementById('noResults');
const countEl      = document.getElementById('evCount');
const totalEvents  = <?= count($events) ?>;

searchInput.addEventListener('input', function () {
  const term = this.value.toLowerCase().trim();
  const rows = document.querySelectorAll('.ev-row');
  let visible = 0;

  rows.forEach(row => {
    const title = row.querySelector('.ev-title').textContent.toLowerCase();
    const show  = term === '' || title.includes(term);
    row.style.display = show ? '' : 'none';
    if (show) visible++;
  });

  // Show/hide "no results"
  noResults.style.display = visible === 0 ? 'block' : 'none';

  // Update count
  countEl.textContent = visible;

  // Show/hide clear button
  clearBtn.style.display = term ? 'block' : 'none';
});

function clearSearch() {
  searchInput.value = '';
  searchInput.dispatchEvent(new Event('input'));
  searchInput.focus();
}
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>