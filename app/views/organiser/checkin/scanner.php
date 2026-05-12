<?php
$pageTitle  = 'Live Check-in';
$activePage = 'checkin';
include __DIR__ . '/../../layouts/organiser-header.php';
$totalSold      = (int)($stats['total_sold']       ?? 0);
$checkedInCount = (int)($stats['checked_in_count'] ?? 0);
$checkinRate    = $totalSold > 0 ? round(($checkedInCount / $totalSold) * 100, 1) : 0;
$remaining      = $totalSold - $checkedInCount;
?>
<style>
  @keyframes slideDown {
    from { opacity:0; transform:translateY(-8px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .stat-ci { background:#fff; border-radius:13px; padding:1rem 1.25rem;
    box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 14px rgba(0,0,0,0.04);
    border:1px solid rgba(0,0,0,0.05); }
  .stat-lbl { font-size:.68rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.5px; color:#9ca3af; margin-bottom:.3rem; }
  .stat-val { font-size:1.6rem; font-weight:800; color:#111; line-height:1; }
  .stat-sub { font-size:.75rem; color:#9ca3af; margin-top:.2rem; }

  .scanner-wrap {
    background:linear-gradient(160deg,#042C53 0%,#063D30 100%);
    border-radius:16px; padding:2rem; color:#fff;
    box-shadow:0 8px 30px rgba(0,0,0,0.15);
  }
  .scanner-title { font-size:.78rem; font-weight:700; text-transform:uppercase;
    letter-spacing:.8px; color:rgba(255,255,255,0.5); margin-bottom:1.25rem;
    display:flex; align-items:center; gap:.5rem; }
  .scanner-live { width:8px; height:8px; background:#5DCAA5; border-radius:50%;
    animation:pulse 2s infinite; display:inline-block; }
  @keyframes pulse {
    0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(93,202,165,0.4)}
    50%{opacity:.8;box-shadow:0 0 0 6px rgba(93,202,165,0);} }

  .ticket-input {
    width:100%; font-family:monospace; font-size:1.2rem; font-weight:700;
    letter-spacing:2px; background:rgba(255,255,255,0.1);
    border:2px solid rgba(255,255,255,0.2); color:#fff; border-radius:10px;
    padding:.9rem 1.1rem; outline:none; transition:all .2s; }
  .ticket-input::placeholder { color:rgba(255,255,255,0.3); letter-spacing:1px; font-weight:400; }
  .ticket-input:focus { border-color:#5DCAA5; background:rgba(255,255,255,0.15); box-shadow:0 0 0 3px rgba(93,202,165,0.2); }
  .ticket-input.input-ok    { border-color:#5DCAA5; }
  .ticket-input.input-error { border-color:#f87171; }

  .btn-ci { width:100%; padding:.85rem; border-radius:10px; border:none;
    background:#5DCAA5; color:#042C53; font-size:.95rem; font-weight:800;
    cursor:pointer; letter-spacing:.3px; margin-top:.75rem;
    transition:all .15s; box-shadow:0 4px 14px rgba(93,202,165,0.35); }
  .btn-ci:hover:not(:disabled) { background:#4ab896; transform:translateY(-1px); }
  .btn-ci:disabled { opacity:.65; cursor:not-allowed; transform:none; }

  /* QR button */
  .btn-qr { width:100%; padding:.6rem; border-radius:10px;
    border:1.5px solid rgba(93,202,165,0.35);
    background:rgba(93,202,165,0.08); color:#5DCAA5;
    font-size:.85rem; font-weight:600; cursor:pointer; margin-top:.5rem;
    transition:all .15s; display:flex; align-items:center; justify-content:center; gap:.5rem; }
  .btn-qr:hover { background:rgba(93,202,165,0.18); border-color:rgba(93,202,165,0.6); }
  .btn-qr.scanning { background:rgba(248,113,113,0.15); border-color:rgba(248,113,113,0.5); color:#fca5a5; }

  /* QR reader container */
  #qrContainer {
    margin-top:.75rem; border-radius:10px; overflow:hidden;
    border:1.5px solid rgba(93,202,165,0.3); display:none; }
  #qrReader { width:100%; }
  #qrReader video { border-radius:8px; }

  .result-box { margin-top:.9rem; padding:.9rem 1.1rem; border-radius:10px;
    border:1.5px solid transparent; font-size:.9rem; font-weight:500;
    display:flex; align-items:center; gap:.75rem; animation:slideDown .3s ease; }
  .result-idle    { background:rgba(255,255,255,0.07); border-color:rgba(255,255,255,0.12); color:rgba(255,255,255,0.45); }
  .result-ok      { background:rgba(93,202,165,0.18);  border-color:rgba(93,202,165,0.5);  color:#9FECCE; }
  .result-used    { background:rgba(251,191,36,0.15);  border-color:rgba(251,191,36,0.5);  color:#fde68a; }
  .result-error   { background:rgba(248,113,113,0.15); border-color:rgba(248,113,113,0.5); color:#fca5a5; }
  .result-loading { background:rgba(255,255,255,0.08); border-color:rgba(255,255,255,0.2); color:rgba(255,255,255,0.6); }

  .prog-bar  { height:5px; background:rgba(255,255,255,0.1); border-radius:3px; margin-top:1rem; overflow:hidden; }
  .prog-fill { height:100%; background:#5DCAA5; border-radius:3px; transition:width .6s ease; }

  .feed-card { background:#fff; border-radius:14px; padding:0;
    box-shadow:0 1px 3px rgba(0,0,0,0.06),0 4px 14px rgba(0,0,0,0.04);
    border:1px solid rgba(0,0,0,0.05); overflow:hidden; }
  .feed-hdr  { padding:.9rem 1.1rem; border-bottom:1px solid #f3f4f6;
    display:flex; align-items:center; gap:.5rem; }
  .feed-hdr h6 { font-size:.85rem; font-weight:700; margin:0; }
  .feed-item { display:flex; align-items:center; gap:.75rem; padding:.75rem 1.1rem;
    border-bottom:1px solid #f9fafb; animation:slideDown .3s ease; }
  .feed-item:last-child { border-bottom:none; }
  .feed-avatar { width:34px; height:34px; border-radius:50%; background:#E1F5EE;
    color:#0F6E56; display:flex; align-items:center; justify-content:center;
    font-weight:800; font-size:.85rem; flex-shrink:0; }
  .feed-name  { font-size:.85rem; font-weight:600; color:#111; }
  .feed-meta  { font-size:.73rem; color:#9ca3af; margin-top:.1rem; }
  .feed-time  { font-size:.72rem; color:#9ca3af; white-space:nowrap; margin-left:auto; flex-shrink:0; }
  .feed-empty { padding:2rem; text-align:center; color:#d1d5db; font-size:.85rem; }

  [data-bs-theme="dark"] .stat-ci   { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .stat-val  { color:#f3f4f6; }
  [data-bs-theme="dark"] .feed-card { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .feed-hdr  { border-color:#374151; }
  [data-bs-theme="dark"] .feed-item { border-color:#253245; }
  [data-bs-theme="dark"] .feed-name { color:#f3f4f6; }
  [data-bs-theme="dark"] .feed-avatar { background:#1a3a2e; }
</style>

<?php if (empty($myEvents)): ?>
  <div style="text-align:center;padding:4rem 2rem;">
    <i class="bi bi-qr-code-scan" style="font-size:3rem;color:#d1d5db;display:block;margin-bottom:1rem;"></i>
    <h5 style="font-weight:700;color:#374151;">No published events</h5>
    <p style="color:#9ca3af;font-size:.88rem;margin-bottom:1.5rem;">
      Publish an event first before using the check-in scanner.
    </p>
    <a href="/WebtechProject/public/organiser/events" class="qa-btn qa-primary" style="display:inline-flex;">
      <i class="bi bi-calendar-event me-1"></i> Go to My Events
    </a>
  </div>
<?php else: ?>

<!-- Event selector -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1rem;">Live Check-in Scanner</h4>
    <p class="text-muted mb-0" style="font-size:.8rem;">Type a ticket code or scan a QR code to check in an attendee</p>
  </div>
  <select class="form-select form-select-sm" style="min-width:260px;"
          onchange="window.location='/WebtechProject/public/organiser/checkin?event_id='+this.value">
    <?php foreach ($myEvents as $ev): ?>
      <option value="<?= $ev['id'] ?>" <?= $ev['id'] == $eventId ? 'selected' : '' ?>>
        <?= htmlspecialchars($ev['title']) ?> — <?= date('d M Y', strtotime($ev['event_datetime'])) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="stat-ci">
      <div class="stat-lbl"><i class="bi bi-ticket-perforated me-1"></i>Sold</div>
      <div class="stat-val" id="stat-sold"><?= $totalSold ?></div>
      <div class="stat-sub">total bookings</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-ci">
      <div class="stat-lbl"><i class="bi bi-check2-circle me-1"></i>Checked In</div>
      <div class="stat-val" id="stat-checked" style="color:#0F6E56;"><?= $checkedInCount ?></div>
      <div class="stat-sub">of <?= $totalSold ?> attendees</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-ci">
      <div class="stat-lbl"><i class="bi bi-percent me-1"></i>Rate</div>
      <div class="stat-val" id="stat-rate" style="color:#4f46e5;"><?= $checkinRate ?>%</div>
      <div class="stat-sub">check-in rate</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-ci">
      <div class="stat-lbl"><i class="bi bi-hourglass me-1"></i>Remaining</div>
      <div class="stat-val" id="stat-remaining" style="color:#d97706;"><?= $remaining ?></div>
      <div class="stat-sub">yet to arrive</div>
    </div>
  </div>
</div>

<!-- Scanner + Feed -->
<div class="row g-3">
  <div class="col-lg-7">
    <div class="scanner-wrap">
      <div class="scanner-title">
        <span class="scanner-live"></span> Live Check-in Terminal
      </div>

      <input type="text" id="ticketCode" class="ticket-input"
             placeholder="TIK-2026-XXXX" autocomplete="off" autocapitalize="characters"
             oninput="this.value=this.value.toUpperCase()"/>

      <button class="btn-ci" id="checkInBtn" onclick="checkIn()">
        <i class="bi bi-check2-circle me-2"></i>Check In
      </button>

      <!-- QR scanner button -->
      <button class="btn-qr" id="qrToggleBtn" onclick="toggleQrScanner()">
        <i class="bi bi-qr-code-scan"></i> Scan QR Code
      </button>

      <!-- Camera feed for QR scanning -->
      <div id="qrContainer">
        <div id="qrReader"></div>
        <p style="font-size:.73rem;color:rgba(255,255,255,.4);text-align:center;padding:.4rem;">
          <i class="bi bi-camera me-1"></i>Point camera at the ticket QR code
        </p>
      </div>

      <div class="result-box result-idle" id="result">
        <i class="bi bi-keyboard fs-5" style="flex-shrink:0;"></i>
        <span>Type a ticket code or scan a QR code to check in</span>
      </div>

      <div class="prog-bar">
        <div class="prog-fill" id="stat-bar" style="width:<?= $checkinRate ?>%;"></div>
      </div>
      <div style="display:flex;justify-content:space-between;margin-top:.4rem;font-size:.72rem;color:rgba(255,255,255,0.35);">
        <span>0%</span>
        <span><?= $checkinRate ?>% checked in</span>
        <span>100%</span>
      </div>

      <div style="margin-top:1.25rem;padding:.75rem;background:rgba(255,255,255,0.06);border-radius:8px;font-size:.75rem;color:rgba(255,255,255,0.4);">
        <i class="bi bi-info-circle me-1"></i>
        <strong style="color:rgba(255,255,255,.55);">QR demo:</strong>
        go to <span style="color:#5DCAA5;">qr-code-generator.com</span>, type
        <code style="color:#5DCAA5;">TIK-2026-A013</code> and scan the generated code.
        Or type codes A011–A020 manually.
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="feed-card h-100">
      <div class="feed-hdr">
        <i class="bi bi-activity" style="color:#0F6E56;"></i>
        <h6>Live Activity</h6>
        <span style="margin-left:auto;font-size:.72rem;color:#9ca3af;" id="feedCount">
          <?= count($recentCheckins) ?> recent
        </span>
      </div>
      <div id="recentFeed">
        <?php if (empty($recentCheckins)): ?>
          <div class="feed-empty" id="feedEmpty">
            <i class="bi bi-clock" style="font-size:1.5rem;display:block;margin-bottom:.5rem;"></i>
            No check-ins yet
          </div>
        <?php else: foreach ($recentCheckins as $ci): ?>
          <div class="feed-item">
            <div class="feed-avatar"><?= strtoupper(substr($ci['attendee_name'],0,1)) ?></div>
            <div style="flex:1;min-width:0;">
              <div class="feed-name"><?= htmlspecialchars($ci['attendee_name']) ?></div>
              <div class="feed-meta"><?= htmlspecialchars($ci['tier_name']) ?></div>
            </div>
            <span class="feed-time"><?= $ci['checked_in_at'] ? date('H:i',strtotime($ci['checked_in_at'])) : '—' ?></span>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
const eventId       = <?= (int)$eventId ?>;
let checkedInCount  = <?= $checkedInCount ?>;
let totalSold       = <?= $totalSold ?>;
let qrScanner       = null;

// ── QR Scanner ──────────────────────────────────────────────
function toggleQrScanner() {
  const container = document.getElementById('qrContainer');
  const btn       = document.getElementById('qrToggleBtn');
  const isOpen    = container.style.display === 'block';

  if (isOpen) {
    stopQr();
  } else {
    container.style.display = 'block';
    btn.classList.add('scanning');
    btn.innerHTML = '<i class="bi bi-x-circle"></i> Close Camera';

    qrScanner = new Html5Qrcode('qrReader');
    qrScanner.start(
      { facingMode: 'environment' },
      { fps: 10, qrbox: { width: 240, height: 240 } },
      decodedText => {
        const code = decodedText.trim().toUpperCase();
        document.getElementById('ticketCode').value = code;
        stopQr();
        checkIn();
      },
      () => {}
    ).catch(err => {
      showResult('error', 'Camera error: ' + err);
      stopQr();
    });
  }
}

function stopQr() {
  if (qrScanner) {
    qrScanner.stop().catch(() => {}).finally(() => { qrScanner = null; });
  }
  document.getElementById('qrContainer').style.display = 'none';
  const btn = document.getElementById('qrToggleBtn');
  btn.classList.remove('scanning');
  btn.innerHTML = '<i class="bi bi-qr-code-scan"></i> Scan QR Code';
}

// ── Manual check-in ─────────────────────────────────────────
function checkIn() {
  const input = document.getElementById('ticketCode');
  const code  = input.value.trim().toUpperCase();
  if (!code) { showResult('warn','Please enter a ticket code.'); return; }

  const btn = document.getElementById('checkInBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Validating...';
  showResult('loading','Checking ticket...');
  input.classList.remove('input-ok','input-error');

  fetch('/WebtechProject/public/organiser/checkin/process', {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify({ ticket_code: code, event_id: eventId })
  })
  .then(r => r.json())
  .then(data => {
    if (data.status === 'ok') {
      showResult('ok', '✓ ' + data.message + ' · ' + data.tier + ' tier');
      input.classList.add('input-ok');
      checkedInCount = data.checked_in_count;
      totalSold      = data.total_sold;
      updateStats();
      prependFeed(data.attendee, data.tier, data.checked_in_at);
      input.value = '';
    } else if (data.status === 'used') {
      showResult('used','⚠ ' + data.message);
      input.classList.add('input-error');
    } else {
      showResult('error','✕ ' + data.message);
      input.classList.add('input-error');
    }
  })
  .catch(() => showResult('error','✕ Network error.'))
  .finally(() => {
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check2-circle me-2"></i>Check In';
    input.focus();
  });
}

function showResult(type, msg) {
  const el = document.getElementById('result');
  el.style.animation = 'none'; void el.offsetHeight; el.style.animation = 'slideDown .3s ease';
  const map = {
    ok:      ['result-ok',      'bi-check-circle-fill'],
    used:    ['result-used',    'bi-exclamation-triangle-fill'],
    error:   ['result-error',   'bi-x-circle-fill'],
    warn:    ['result-used',    'bi-exclamation-circle'],
    loading: ['result-loading', 'bi-arrow-repeat'],
  };
  const [cls, icon] = map[type] || map.loading;
  el.className = 'result-box ' + cls;
  el.innerHTML = `<i class="bi ${icon} fs-5" style="flex-shrink:0;"></i><span>${msg}</span>`;
}

function updateStats() {
  const rate = totalSold > 0 ? ((checkedInCount/totalSold)*100).toFixed(1) : '0';
  document.getElementById('stat-checked').textContent   = checkedInCount;
  document.getElementById('stat-rate').textContent      = rate + '%';
  document.getElementById('stat-remaining').textContent = Math.max(0, totalSold - checkedInCount);
  document.getElementById('stat-bar').style.width       = rate + '%';
}

function prependFeed(name, tier, time) {
  const feed  = document.getElementById('recentFeed');
  const empty = document.getElementById('feedEmpty');
  if (empty) empty.remove();
  const div = document.createElement('div');
  div.className = 'feed-item';
  div.style.background = 'rgba(93,202,165,0.12)';
  div.innerHTML = `
    <div class="feed-avatar" style="background:#ECFDF5;color:#065f46;">${name.charAt(0).toUpperCase()}</div>
    <div style="flex:1;min-width:0;">
      <div class="feed-name">${name}</div>
      <div class="feed-meta">${tier} · just now</div>
    </div>
    <span class="feed-time">${time}</span>`;
  feed.insertBefore(div, feed.firstChild);
  setTimeout(() => div.style.background = '', 1500);
  const cnt = document.getElementById('feedCount');
  cnt.textContent = (parseInt(cnt.textContent) + 1) + ' recent';
  while (feed.children.length > 8) feed.removeChild(feed.lastChild);
}

document.getElementById('ticketCode').addEventListener('keydown', e => {
  if (e.key === 'Enter') checkIn();
});
window.addEventListener('load', () => {
  const inp = document.getElementById('ticketCode');
  if (inp) inp.focus();
});
</script>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>