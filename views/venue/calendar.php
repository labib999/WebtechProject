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

$activePage = 'calendar';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Availability Calendar</h4>
    <p class="text-muted mb-0" style="font-size:14px;">Manage venue availability by month</p>
  </div>
  <select class="form-select" style="width:220px;"
          onchange="window.location.href='calendar.php?venue_id='+this.value">
    <?php foreach ($venues as $v): ?>
      <option value="<?= $v['id'] ?>"
        <?= $v['id'] == $selectedVenueId ? 'selected' : '' ?>>
        <?= htmlspecialchars($v['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <a href="calendar.php?venue_id=<?= $selectedVenueId ?>&month=<?= $month-1 ?>&year=<?= $year ?>"
       class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-chevron-left"></i> Prev
    </a>
    <span class="fw-semibold">
      <?= date('F Y', mktime(0,0,0,$month,1,$year)) ?>
    </span>
    <a href="calendar.php?venue_id=<?= $selectedVenueId ?>&month=<?= $month+1 ?>&year=<?= $year ?>"
       class="btn btn-outline-secondary btn-sm">
      Next <i class="bi bi-chevron-right"></i>
    </a>
  </div>

  <div class="calendar-grid">
    <?php
    $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    foreach ($days as $d):
    ?>
      <div class="calendar-day-label"><?= $d ?></div>
    <?php endforeach; ?>
  </div>

  <div class="calendar-grid" id="calendarGrid">
    <?php
    $firstDay  = (int)date('w', mktime(0,0,0,$month,1,$year));
    $totalDays = (int)date('t', mktime(0,0,0,$month,1,$year));
    $today     = date('Y-m-d');

    for ($i = 0; $i < $firstDay; $i++):
    ?>
      <div class="calendar-day empty"></div>
    <?php endfor; ?>

    <?php for ($d = 1; $d <= $totalDays; $d++):
      $dateStr = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($d, 2, '0', STR_PAD_LEFT);
      $status  = $availability[$dateStr]['status'] ?? 'available';
      $note    = $availability[$dateStr]['note']   ?? '';
      $isToday = $dateStr === $today;
    ?>
      <div class="calendar-day <?= $status ?> <?= $isToday ? 'today' : '' ?>"
           onclick="showDayModal('<?= $dateStr ?>', '<?= $status ?>', '<?= htmlspecialchars($note, ENT_QUOTES) ?>')">
        <div class="day-num"><?= $d ?></div>
        <?php if ($note): ?>
          <div style="font-size:11px; color:#64748b; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">
            <?= htmlspecialchars($note) ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endfor; ?>
  </div>

  <div class="p-3 d-flex gap-4" style="border-top:1px solid #e2e8f0; font-size:13px;">
    <span><span class="legend-dot available"></span> Available</span>
    <span><span class="legend-dot booked"></span> Booked</span>
    <span><span class="legend-dot blocked"></span> Blocked</span>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header"><i class="bi bi-ban me-2"></i> Block a Date</div>
  <div class="card-body">
    <div class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Date</label>
        <input type="date" id="blockDate" class="form-control">
      </div>
      <div class="col-md-5">
        <label class="form-label">Reason (optional)</label>
        <input type="text" id="blockNote" class="form-control"
               placeholder="e.g. Private booking, Maintenance">
      </div>
      <div class="col-md-2">
        <button class="btn btn-danger w-100" onclick="blockDate()">
          <i class="bi bi-ban me-1"></i> Block
        </button>
      </div>
    </div>
    <div id="blockMsg" class="mt-3" style="display:none;"></div>
  </div>
</div>

<div class="modal fade" id="dayModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-semibold" id="modalDate"></h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="modalStatus" class="mb-0"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
const venueId = <?= $selectedVenueId ?>;

function showDayModal(date, status, note) {
    document.getElementById('modalDate').textContent = date;
    document.getElementById('modalStatus').innerHTML =
        '<strong>Status:</strong> ' + status.toUpperCase() +
        (note ? '<br><strong>Note:</strong> ' + note : '');
    new bootstrap.Modal(document.getElementById('dayModal')).show();
}

function blockDate() {
    const date = document.getElementById('blockDate').value;
    const note = document.getElementById('blockNote').value;
    const msg  = document.getElementById('blockMsg');

    if (!date) {
        msg.style.display = 'block';
        msg.innerHTML = '<div class="alert alert-danger">Please select a date.</div>';
        return;
    }

    const formData = new FormData();
    formData.append('action',   'block_date');
    formData.append('venue_id', venueId);
    formData.append('date',     date);
    formData.append('note',     note);

    fetch('/WebtechProject/controllers/VenueController.php', {
        method: 'POST',
        body:   formData
    })
    .then(res => res.json())
    .then(data => {
        msg.style.display = 'block';
        msg.innerHTML = '<div class="alert alert-' + (data.success ? 'success' : 'danger') + '">' + data.message + '</div>';
        if (data.success) setTimeout(() => location.reload(), 1000);
    });
}
</script>

<?php include '../shared/footer.php'; ?>