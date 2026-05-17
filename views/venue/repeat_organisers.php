<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model      = new VenueModel();
$managerId  = $_SESSION['user_id'];
$organisers = $model->getRepeatOrganisers($managerId);

$activePage = 'repeat_organisers';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Organiser List</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      Organisers who have used your venues
    </p>
  </div>
  <span class="badge bg-primary" style="font-size:13px; padding:8px 14px;">
    <?= count($organisers) ?> Organisers
  </span>
</div>

<?php if (empty($organisers)): ?>
  <div class="card p-5 text-center">
    <i class="bi bi-people" style="font-size:48px; color:#e2e8f0;"></i>
    <h5 class="mt-3 text-muted">No organisers yet</h5>
    <p class="text-muted" style="font-size:14px;">Approved booking organisers will appear here</p>
  </div>

<?php else: ?>
  <div class="card">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Organiser</th>
            <th>Email</th>
            <th>Total Bookings</th>
            <th>Last Booking</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($organisers as $i => $org): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div style="width:36px; height:36px; background:#eff6ff; border-radius:50%;
                            display:flex; align-items:center; justify-content:center;
                            color:#3b82f6; font-weight:600; font-size:14px;">
                  <?= strtoupper(substr($org['organiser_name'], 0, 1)) ?>
                </div>
                <strong><?= htmlspecialchars($org['organiser_name']) ?></strong>
              </div>
            </td>
            <td style="font-size:13px; color:#64748b;">
              <?= htmlspecialchars($org['email']) ?>
            </td>
            <td>
              <span class="badge bg-primary"><?= $org['booking_count'] ?> bookings</span>
            </td>
            <td style="font-size:13px;">
              <?= date('M d, Y', strtotime($org['last_booking'])) ?>
            </td>
            <td>
              <?php if ($org['booking_count'] >= 2): ?>
                <span class="badge" style="background:#ecfdf5; color:#10b981;">
                  ⭐ Repeat Customer
                </span>
              <?php else: ?>
                <span class="badge" style="background:#f8fafc; color:#64748b;">
                  New Customer
                </span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<?php include '../shared/footer.php'; ?>