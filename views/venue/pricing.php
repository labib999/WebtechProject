<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model     = new VenueModel();
$managerId = $_SESSION['user_id'];
$venues    = $model->getVenuesByManager($managerId);

$selectedVenueId = (int)($_GET['venue_id'] ?? ($venues[0]['id'] ?? 0));
$pricing         = $selectedVenueId ? $model->getPricing($selectedVenueId) : [];

$activePage = 'pricing';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Venue Pricing</h4>
    <p class="text-muted mb-0" style="font-size:14px;">Set per-day rates for each venue</p>
  </div>
  <select class="form-select" style="width:220px;"
          onchange="window.location.href='pricing.php?venue_id='+this.value">
    <?php foreach ($venues as $v): ?>
      <option value="<?= $v['id'] ?>"
        <?= $v['id'] == $selectedVenueId ? 'selected' : '' ?>>
        <?= htmlspecialchars($v['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

<form action="/webtechproject/WebtechProject/controllers/VenueController.php" method="POST">
  <input type="hidden" name="action" value="save_pricing">
  <input type="hidden" name="venue_id" value="<?= $selectedVenueId ?>">

  <div class="row g-4 mb-4">

    <div class="col-md-4">
      <div class="pricing-card">
        <div class="pricing-card-header">
          <div>
            <h6 class="fw-semibold mb-0">Weekday Rate</h6>
            <small class="text-muted">Monday – Thursday</small>
          </div>
          <i class="bi bi-sun fs-4 text-primary"></i>
        </div>
        <div class="p-4">
          <label class="form-label">Price per Day (৳)</label>
          <input type="number" name="weekday_price" class="form-control"
                 value="<?= $pricing['weekday'] ?? '' ?>"
                 placeholder="e.g. 15000" min="0" step="100" required>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="pricing-card">
        <div class="pricing-card-header">
          <div>
            <h6 class="fw-semibold mb-0">Weekend Rate</h6>
            <small class="text-muted">Friday – Saturday</small>
          </div>
          <i class="bi bi-star fs-4 text-warning"></i>
        </div>
        <div class="p-4">
          <label class="form-label">Price per Day (৳)</label>
          <input type="number" name="weekend_price" class="form-control"
                 value="<?= $pricing['weekend'] ?? '' ?>"
                 placeholder="e.g. 22000" min="0" step="100" required>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="pricing-card">
        <div class="pricing-card-header">
          <div>
            <h6 class="fw-semibold mb-0">Holiday Rate</h6>
            <small class="text-muted">Public holidays</small>
          </div>
          <i class="bi bi-gift fs-4 text-danger"></i>
        </div>
        <div class="p-4">
          <label class="form-label">Price per Day (৳)</label>
          <input type="number" name="holiday_price" class="form-control"
                 value="<?= $pricing['holiday'] ?? '' ?>"
                 placeholder="e.g. 28000" min="0" step="100" required>
        </div>
      </div>
    </div>

  </div>

  <div class="d-flex justify-content-end mb-4">
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-floppy me-2"></i> Save Pricing
    </button>
  </div>

</form>

<div class="card">
  <div class="card-header">All Venues Pricing Summary</div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Venue</th>
          <th>Weekday (৳)</th>
          <th>Weekend (৳)</th>
          <th>Holiday (৳)</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($venues as $v):
          $p = $model->getPricing($v['id']);
        ?>
        <tr>
          <td><strong><?= htmlspecialchars($v['name']) ?></strong></td>
          <td><?= isset($p['weekday']) ? number_format($p['weekday']) : '<span class="text-muted">Not set</span>' ?></td>
          <td><?= isset($p['weekend']) ? number_format($p['weekend']) : '<span class="text-muted">Not set</span>' ?></td>
          <td><?= isset($p['holiday']) ? number_format($p['holiday']) : '<span class="text-muted">Not set</span>' ?></td>
          <td>
            <a href="pricing.php?venue_id=<?= $v['id'] ?>"
               class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-pen"></i> Edit
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../shared/footer.php'; ?>