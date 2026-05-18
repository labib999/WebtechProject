<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model     = new VenueModel();
$managerId = $_SESSION['user_id'];
$venues    = $model->getVenuesByManager($managerId);

$selectedVenueId = (int)($_GET['venue_id'] ?? ($venues[0]['id'] ?? 0));
$month           = (int)($_GET['month'] ?? date('n'));
$year            = (int)($_GET['year']  ?? date('Y'));

if ($month < 1)  { $month = 12; $year--; }
if ($month > 12) { $month = 1;  $year++; }

$availability = $selectedVenueId ? $model->getAvailability($selectedVenueId, $year, $month) : [];

$totalDays    = (int)date('t', mktime(0,0,0,$month,1,$year));
$bookedCount  = 0;
$blockedCount = 0;
foreach ($availability as $a) {
    if ($a['status'] === 'booked')   $bookedCount++;
    if ($a['status'] === 'blocked')  $blockedCount++;
}
$availableCount = $totalDays - $bookedCount - $blockedCount;

$activePage = 'calendar';
include '../shared/header.php';
?>
<link rel="stylesheet" href="/webtechproject/WebtechProject/public/css/venue.css">
<?php include '../shared/navbar.php'; ?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Availability Calendar</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      Manage venue availability by month
    </p>
  </div>
  <select class="form-select" style="width:240px;"
          onchange="window.location.href='calendar.php?venue_id='+this.value">
    <?php foreach ($venues as $v): ?>
      <option value="<?= $v['id'] ?>"
        <?= $v['id'] == $selectedVenueId ? 'selected' : '' ?>>
        <?= htmlspecialchars($v['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="rounded-3 p-3 d-flex align-items-center gap-3"
         style="background:#f0fdf4; border:1px solid #bbf7d0;">
      <div style="background:#10b981; width:40px; height:40px; border-radius:10px;
                  display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px;">
        <i class="bi bi-check-circle"></i>
      </div>
      <div>
        <h4 class="fw-bold mb-0" style="color:#065f46;"><?= $availableCount ?></h4>
        <p class="mb-0" style="font-size:12px; color:#059669;">Available Days</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="rounded-3 p-3 d-flex align-items-center gap-3"
         style="background:#dbeafe; border:1px solid #93c5fd;">
      <div style="background:#3b82f6; width:40px; height:40px; border-radius:10px;
                  display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px;">
        <i class="bi bi-calendar-check"></i>
      </div>
      <div>
        <h4 class="fw-bold mb-0" style="color:#1e40af;"><?= $bookedCount ?></h4>
        <p class="mb-0" style="font-size:12px; color:#2563eb;">Booked Days</p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="rounded-3 p-3 d-flex align-items-center gap-3"
         style="background:#fef2f2; border:1px solid #fecaca;">
      <div style="background:#ef4444; width:40px; height:40px; border-radius:10px;
                  display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px;">
        <i class="bi bi-ban"></i>
      </div>
      <div>
        <h4 class="fw-bold mb-0" style="color:#991b1b;"><?= $blockedCount ?></h4>
        <p class="mb-0" style="font-size:12px; color:#dc2626;">Blocked Days</p>
      </div>
    </div>
  </div>
</div>

<!-- Calendar -->
<div class="rounded-3 overflow-hidden mb-4" style="background:#fff; border:1px solid #e2e8f0;">

  <!-- Nav -->
  <div class="d-flex justify-content-between align-items-center px-4 py-3"
       style="background: linear-gradient(135deg, #0f172a, #1e3a5f); color:#fff;">
    <a href="calendar.php?venue_id=<?= $selectedVenueId ?>&month=<?= $month-1 ?>&year=<?= $year ?>"
       class="btn btn-sm" style="background:rgba(255,255,255,0.1); color:#fff; border:none;">
      <i class="bi bi-chevron-left"></i> Prev
    </a>
    <div class="text-center">
      <h5 class="fw-bold mb-0"><?= date('F Y', mktime(0,0,0,$month,1,$year)) ?></h5>
      <small style="opacity:0.7; font-size:11px;"><?= $totalDays ?> days total</small>
    </div>
    <a href="calendar.php?venue_id=<?= $selectedVenueId ?>&month=<?= $month+1 ?>&year=<?= $year ?>"
       class="btn btn-sm" style="background:rgba(255,255,255,0.1); color:#fff; border:none;">
      Next <i class="bi bi-chevron-right"></i>
    </a>
  </div>

  <!-- Day Labels -->
  <div class="calendar-grid">
    <?php foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d): ?>
      <div class="calendar-day-label"><?= $d ?></div>
    <?php endforeach; ?>
  </div>

  <!-- Days -->
  <div class="calendar-grid" id="calendarGrid">
    <?php
    $firstDay = (int)date('w', mktime(0,0,0,$month,1,$year));
    $today    = date('Y-m-d');

    for ($i = 0; $i < $firstDay; $i++):
    ?>
      <div class="calendar-day empty"></div>
    <?php endfor; ?>

    <?php for ($d = 1; $d <= $totalDays; $d++):
      $dateStr = $year.'-'.str_pad($month,2,'0',STR_PAD_LEFT).'-'.str_pad($d,2,'0',STR_PAD_LEFT);
      $status  = $availability[$dateStr]['status'] ?? 'available';
      $note    = $availability[$dateStr]['note']   ?? '';
      $isToday = $dateStr === $today;
    ?>
      <div class="calendar-day <?= $status ?> <?= $isToday ? 'today' : '' ?>"
           onclick="showDayModal('<?= $dateStr ?>','<?= $status ?>','<?= htmlspecialchars($note,ENT_QUOTES) ?>')">
        <div class="day-num"><?= $d ?></div>
        <?php if ($note): ?>
          <div style="font-size:10px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; margin-top:2px;">
            <?= htmlspecialchars($note) ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endfor; ?>
  </div>

  <!-- Legend -->
  <div class="px-4 py-3 d-flex gap-4" style="border-top:1px solid #f1f5f9; font-size:13px; background:#fafafa;">
    <span class="d-flex align-items-center gap-2">
      <span style="width:12px; height:12px; background:#10b981; border-radius:3px; display:inline-block;"></span>
      Available
    </span>
    <span class="d-flex align-items-center gap-2">
      <span style="width:12px; height:12px; background:#3b82f6; border-radius:3px; display:inline-block;"></span>
      Booked
    </span>
    <span class="d-flex align-items-center gap-2">
      <span style="width:12px; height:12px; background:#ef4444; border-radius:3px; display:inline-block;"></span>
      Blocked
    </span>
  </div>
</div>

<!-- Block Date -->
<div class="rounded-3 overflow-hidden mb-4" style="background:#fff; border:1px solid #e2e8f0;">
  <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9; background:#fafafa;">
    <h6 class="fw-semibold mb-0" style="font-size:14px;">
      <i class="bi bi-ban me-2" style="color:#ef4444;"></i>Block a Date
    </h6>
  </div>
  <div class="p-4">
    <div class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label" style="font-size:13px;">Select Date</label>
        <input type="date" id="blockDate" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label" style="font-size:13px;">Reason (optional)</label>
        <input type="text" id="blockNote" class="form-control"
               placeholder="e.g. Private booking, Maintenance, Renovation">
      </div>
      <div class="col-md-3">
        <button class="btn btn-danger w-100" onclick="blockDateFn()">
          <i class="bi bi-ban me-1"></i> Block Date
        </button>
      </div>
    </div>
    <div id="blockMsg" class="mt-3" style="display:none;"></div>
  </div>
</div>

<!-- Day Modal -->
<div class="modal fade" id="dayModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content" style="border-radius:12px; overflow:hidden;">
      <div class="modal-header" style="background:linear-gradient(135deg, #0f172a, #1e3a5f); color:#fff; border:none;">
        <h6 class="modal-title fw-semibold" id="modalDate" style="font-size:14px;"></h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding:20px;">
        <div id="modalStatus"></div>
      </div>
      <div class="modal-footer" style="border:none; padding-top:0;">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
const venueId = <?= $selectedVenueId ?>;

function showDayModal(date, status, note) {
    document.getElementById('modalDate').textContent = '📅 ' + date;
    const colors = { available: '#10b981', booked: '#3b82f6', blocked: '#ef4444' };
    const labels = { available: 'AVAILABLE', booked: 'BOOKED', blocked: 'BLOCKED' };
    document.getElementById('modalStatus').innerHTML =
        '<div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">' +
        '<span style="width:10px; height:10px; background:' + (colors[status]||'#64748b') + '; border-radius:50%; display:inline-block;"></span>' +
        '<strong>' + (labels[status]||status.toUpperCase()) + '</strong></div>' +
        (note ? '<p style="font-size:13px; color:#64748b; margin:0;"><i class="bi bi-info-circle me-1"></i>' + note + '</p>' : '');
    new bootstrap.Modal(document.getElementById('dayModal')).show();
}

function blockDateFn() {
    const date = document.getElementById('blockDate').value;
    const note = document.getElementById('blockNote').value;
    const msg  = document.getElementById('blockMsg');

    if (!date) {
        msg.style.display = 'block';
        msg.innerHTML = '<div class="alert alert-danger py-2" style="font-size:13px;">Please select a date.</div>';
        return;
    }

    const formData = new FormData();
    formData.append('action',   'block_date');
    formData.append('venue_id', venueId);
    formData.append('date',     date);
    formData.append('note',     note);

    fetch('/webtechproject/WebtechProject/controllers/VenueController.php', {
        method: 'POST',
        body:   formData
    })
    .then(res => res.json())
    .then(data => {
        msg.style.display = 'block';
        msg.innerHTML = '<div class="alert alert-' + (data.success ? 'success' : 'danger') + ' py-2" style="font-size:13px;">' + data.message + '</div>';
        if (data.success) setTimeout(() => location.reload(), 1000);
    });
}
</script>

<?php include '../shared/footer.php'; ?>