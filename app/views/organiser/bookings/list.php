<?php
$pageTitle  = 'Bookings';
$activePage = 'bookings';
include __DIR__ . '/../../layouts/organiser-header.php';
?>
<style>
  .filter-bar { background:#fff; border-radius:13px; padding:1rem 1.25rem; margin-bottom:1.25rem;
    box-shadow:0 1px 3px rgba(0,0,0,0.06); border:1px solid rgba(0,0,0,0.05); }
  .bk-table { width:100%; border-collapse:collapse; font-size:.845rem; }
  .bk-table th { padding:.65rem 1rem; font-size:.7rem; font-weight:700; letter-spacing:.5px;
    text-transform:uppercase; color:#9ca3af; border-bottom:1px solid #f3f4f6; background:#fafafa; }
  .bk-table td { padding:.75rem 1rem; border-bottom:1px solid #f9fafb; vertical-align:middle; }
  .bk-table tr:last-child td { border-bottom:none; }
  .bk-table tr:hover td { background:#fafffe; }
  .pill { display:inline-block; padding:2px 9px; border-radius:20px; font-size:.72rem; font-weight:600; }
  .pill-active   { background:#ECFDF5; color:#065f46; }
  .pill-refunded { background:#FEF2F2; color:#991b1b; }
  .pill-cancelled{ background:#F3F4F6; color:#374151; }
  .pill-checked  { background:#EFF6FF; color:#1e40af; }
  .pill-pending  { background:#FFFBEB; color:#92400e; }
  .summary-card { background:#fff; border-radius:12px; padding:.9rem 1.1rem;
    border:1px solid rgba(0,0,0,0.05); box-shadow:0 1px 3px rgba(0,0,0,0.05); }
  [data-bs-theme="dark"] .filter-bar,
  [data-bs-theme="dark"] .summary-card { background:#1f2937; border-color:#374151; }
  [data-bs-theme="dark"] .bk-table th   { background:#253245; color:#6b7280; }
  [data-bs-theme="dark"] .bk-table td   { border-color:#253245; color:#d1d5db; }
  [data-bs-theme="dark"] .bk-table tr:hover td { background:#1c2e3a; }
</style>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-0" style="font-size:1.05rem;">Bookings</h4>
    <p class="text-muted mb-0" style="font-size:.8rem;"><?= count($bookings) ?> result<?= count($bookings)!=1?'s':'' ?></p>
  </div>
</div>

<!-- Flash -->
<?php if (!empty($error)): ?>
  <div class="d-flex align-items-center gap-2 mb-3 p-3"
       style="background:#FEF2F2;border:1px solid #fecaca;color:#991b1b;border-radius:10px;font-size:.88rem;">
    <i class="bi bi-exclamation-circle-fill"></i><span><?= htmlspecialchars($error) ?></span>
  </div>
<?php endif; ?>

<!-- Summary -->
<div class="row g-3 mb-3">
  <div class="col-4">
    <div class="summary-card">
      <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;">Total</div>
      <div style="font-size:1.4rem;font-weight:800;"><?= count($bookings) ?></div>
    </div>
  </div>
  <div class="col-4">
    <div class="summary-card">
      <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;">Revenue</div>
      <div style="font-size:1.4rem;font-weight:800;color:#0F6E56;">$<?= number_format($totalRevenue,0) ?></div>
    </div>
  </div>
  <div class="col-4">
    <div class="summary-card">
      <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#9ca3af;">Checked In</div>
      <div style="font-size:1.4rem;font-weight:800;color:#4f46e5;">
        <?= count(array_filter($bookings, fn($b)=>$b['checked_in'])) ?>
      </div>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="filter-bar">
  <form method="GET" action="/WebtechProject/public/organiser/bookings" class="row g-2 align-items-end">
    <div class="col-md-3">
      <label style="font-size:.75rem;font-weight:600;color:#6b7280;display:block;margin-bottom:.25rem;">Event</label>
      <select name="event_id" class="form-select form-select-sm">
        <option value="0">All events</option>
        <?php foreach ($myEvents as $ev): ?>
          <option value="<?= $ev['id'] ?>" <?= $eventFilter==$ev['id']?'selected':'' ?>>
            <?= htmlspecialchars($ev['title']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label style="font-size:.75rem;font-weight:600;color:#6b7280;display:block;margin-bottom:.25rem;">Status</label>
      <select name="status" class="form-select form-select-sm">
        <option value="all"      <?= $statusFilter==='all'      ?'selected':'' ?>>All</option>
        <option value="active"   <?= $statusFilter==='active'   ?'selected':'' ?>>Active</option>
        <option value="refunded" <?= $statusFilter==='refunded' ?'selected':'' ?>>Refunded</option>
        <option value="cancelled"<?= $statusFilter==='cancelled'?'selected':'' ?>>Cancelled</option>
      </select>
    </div>
    <div class="col-md-2">
      <label style="font-size:.75rem;font-weight:600;color:#6b7280;display:block;margin-bottom:.25rem;">Check-in</label>
      <select name="checked_in" class="form-select form-select-sm">
        <option value="all"<?= $checkinFilter==='all'?'selected':'' ?>>All</option>
        <option value="yes"<?= $checkinFilter==='yes'?'selected':'' ?>>Checked in</option>
        <option value="no" <?= $checkinFilter==='no' ?'selected':'' ?>>Pending</option>
      </select>
    </div>
    <div class="col-md-3">
      <label style="font-size:.75rem;font-weight:600;color:#6b7280;display:block;margin-bottom:.25rem;">Search</label>
      <input type="text" name="q" class="form-control form-control-sm"
             placeholder="Name or ticket code" value="<?= htmlspecialchars($search) ?>"/>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-sm w-100" style="background:#0F6E56;color:#fff;border:none;padding:.42rem;">
        <i class="bi bi-search me-1"></i>Filter
      </button>
    </div>
  </form>
</div>

<!-- Table -->
<div class="section-card">
  <?php if (empty($bookings)): ?>
    <div style="text-align:center;padding:3rem;color:#9ca3af;">
      <i class="bi bi-ticket-perforated" style="font-size:2.5rem;display:block;margin-bottom:.75rem;color:#d1d5db;"></i>
      <p style="font-size:.88rem;">No bookings found for the selected filters.</p>
    </div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="bk-table">
      <thead>
        <tr>
          <th class="ps-3">Attendee</th>
          <th>Event</th>
          <th>Tier</th>
          <th>Ticket Code</th>
          <th>Paid</th>
          <th>Booking Status</th>
          <th>Check-in</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
          <td class="ps-3">
            <div style="font-weight:600;"><?= htmlspecialchars($b['attendee_name']) ?></div>
            <div style="font-size:.73rem;color:#9ca3af;"><?= htmlspecialchars($b['attendee_email']) ?></div>
          </td>
          <td style="font-size:.82rem;"><?= htmlspecialchars(mb_strimwidth($b['event_title'],0,30,'…')) ?></td>
          <td>
            <span class="pill" style="<?=
              str_contains(strtolower($b['tier_name']),'vip')
                ? 'background:#FFF7ED;color:#c2410c;'
                : (str_contains(strtolower($b['tier_name']),'early')
                  ? 'background:#F5F3FF;color:#6d28d9;'
                  : 'background:#EFF6FF;color:#1d4ed8;')
            ?>"><?= htmlspecialchars($b['tier_name']) ?></span>
          </td>
          <td><code style="font-size:.78rem;color:#0F6E56;"><?= htmlspecialchars($b['ticket_code']) ?></code></td>
          <td style="font-weight:600;">$<?= number_format($b['total_price'],2) ?></td>
          <td>
            <span class="pill pill-<?= $b['status'] ?>">
              <?= ucfirst($b['status']) ?>
            </span>
          </td>
          <td>
            <?php if ($b['checked_in']): ?>
              <span class="pill pill-checked"><i class="bi bi-check-circle-fill me-1"></i>
                <?= date('g:i A', strtotime($b['checked_in_at'])) ?>
              </span>
            <?php else: ?>
              <span class="pill pill-pending"><i class="bi bi-clock me-1"></i>Pending</span>
            <?php endif; ?>
          </td>
          <td style="font-size:.78rem;color:#9ca3af;"><?= date('d M', strtotime($b['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/organiser-footer.php'; ?>