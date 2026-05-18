<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model     = new VenueModel();
$managerId = $_SESSION['user_id'];
$history   = $model->getBookingHistory($managerId);

$totalRevenue  = array_sum(array_column($history, 'total_price'));
$totalTickets  = array_sum(array_column($history, 'tickets_sold'));
$totalCheckins = count(array_filter($history, fn($r) => $r['checked_in']));

$activePage = 'booking_history';
include '../shared/header.php';
include '../shared/navbar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Booking History</h4>
    <p class="text-muted mb-0" style="font-size:14px;">All past events held at your venues</p>
  </div>
  <span style="background:linear-gradient(135deg,#64748b,#475569); color:#fff;
               font-size:13px; padding:6px 16px; border-radius:20px; font-weight:600;">
    <?= count($history) ?> Past Events
  </span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="rounded-3 p-3" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #3b82f6;">
      <div style="background:#eff6ff; width:44px; height:44px; border-radius:10px;
                  display:flex; align-items:center; justify-content:center;
                  color:#3b82f6; font-size:20px; margin-bottom:12px;">
        <i class="bi bi-calendar-check"></i>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:28px; color:#1e293b;"><?= count($history) ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Total Past Events</p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="rounded-3 p-3" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #10b981;">
      <div style="background:#ecfdf5; width:44px; height:44px; border-radius:10px;
                  display:flex; align-items:center; justify-content:center;
                  color:#10b981; font-size:20px; margin-bottom:12px;">
        <i class="bi bi-ticket-perforated"></i>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:28px; color:#1e293b;"><?= number_format($totalTickets) ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Total Tickets Sold</p>
    </div>
  </div>
  <div class="col-md-4">
    <div class="rounded-3 p-3" style="background:#fff; border:1px solid #e2e8f0; border-top:3px solid #8b5cf6;">
      <div style="background:#f5f3ff; width:44px; height:44px; border-radius:10px;
                  display:flex; align-items:center; justify-content:center;
                  color:#8b5cf6; font-size:20px; margin-bottom:12px;">
        <i class="bi bi-cash-stack"></i>
      </div>
      <h3 class="fw-bold mb-0" style="font-size:22px; color:#1e293b;">৳<?= number_format($totalRevenue) ?></h3>
      <p class="text-muted mb-0" style="font-size:13px;">Total Revenue</p>
    </div>
  </div>
</div>

<?php if (empty($history)): ?>
  <div class="text-center py-5 rounded-3" style="background:#fff; border:2px dashed #e2e8f0;">
    <i class="bi bi-clock-history" style="font-size:52px; color:#e2e8f0;"></i>
    <h5 class="mt-3 fw-semibold">No past events yet</h5>
    <p class="text-muted" style="font-size:14px;">Completed events will appear here</p>
  </div>

<?php else: ?>
  <div class="rounded-3 overflow-hidden" style="background:#fff; border:1px solid #e2e8f0;">
    <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9; background:#fafafa;">
      <h6 class="fw-semibold mb-0" style="font-size:14px;">
        <i class="bi bi-clock-history me-2" style="color:#64748b;"></i>Event History
      </h6>
    </div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr style="background:#f8fafc;">
            <th style="font-size:12px; color:#64748b; font-weight:600;">#</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">EVENT</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">ORGANISER</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">VENUE</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">DATE</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">TICKETS</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">CHECK-IN</th>
            <th style="font-size:12px; color:#64748b; font-weight:600;">REVENUE</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($history as $i => $row): ?>
          <tr>
            <td style="font-size:13px; color:#94a3b8;"><?= $i + 1 ?></td>
            <td>
              <strong style="font-size:13px;"><?= htmlspecialchars($row['title']) ?></strong>
            </td>
            <td style="font-size:13px; color:#64748b;">
              <?= htmlspecialchars($row['organiser_name']) ?>
            </td>
            <td style="font-size:12px; color:#64748b;">
              <?= htmlspecialchars($row['venue_name']) ?>
            </td>
            <td>
              <span style="font-size:12px; background:#f1f5f9; color:#475569;
                           padding:3px 10px; border-radius:20px; font-weight:500;">
                <?= date('M d, Y', strtotime($row['event_datetime'])) ?>
              </span>
            </td>
            <td>
              <?php if ($row['tickets_sold']): ?>
                <span style="background:#eff6ff; color:#3b82f6; font-size:12px;
                             padding:3px 10px; border-radius:20px; font-weight:600;">
                  <?= number_format($row['tickets_sold']) ?>
                </span>
              <?php else: ?>
                <span style="color:#94a3b8; font-size:13px;">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($row['checked_in']): ?>
                <span style="background:#ecfdf5; color:#059669; font-size:12px;
                             padding:3px 10px; border-radius:20px; font-weight:600;">
                  ✓ Checked In
                </span>
              <?php else: ?>
                <span style="background:#f8fafc; color:#94a3b8; font-size:12px;
                             padding:3px 10px; border-radius:20px; font-weight:500;">
                  Not checked
                </span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($row['total_price']): ?>
                <strong style="color:#10b981; font-size:13px;">
                  ৳<?= number_format($row['total_price']) ?>
                </strong>
              <?php else: ?>
                <span style="color:#94a3b8; font-size:13px;">—</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr style="background:#f8fafc; border-top:2px solid #e2e8f0;">
            <td colspan="5">
              <strong style="font-size:13px;">Total</strong>
            </td>
            <td>
              <span style="background:#eff6ff; color:#3b82f6; font-size:12px;
                           padding:3px 10px; border-radius:20px; font-weight:600;">
                <?= number_format($totalTickets) ?>
              </span>
            </td>
            <td>
              <span style="background:#ecfdf5; color:#059669; font-size:12px;
                           padding:3px 10px; border-radius:20px; font-weight:600;">
                <?= $totalCheckins ?> events
              </span>
            </td>
            <td>
              <strong style="color:#10b981; font-size:13px;">
                ৳<?= number_format($totalRevenue) ?>
              </strong>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php include '../shared/footer.php'; ?>