<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model   = new VenueModel();
$venueId = (int)($_GET['id'] ?? 0);
$venue   = $model->getVenueById($venueId);

if (!$venue || $venue['manager_id'] != $_SESSION['user_id']) {
    header('Location: /WebtechProject/views/venue/manage_venues.php');
    exit;
}

$facilities = json_decode($venue['facilities'] ?? '[]', true);
$photos     = json_decode($venue['photos'] ?? '[]', true);

$activePage = 'manage_venues';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Edit Venue</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      Update details for <?= htmlspecialchars($venue['name']) ?>
    </p>
  </div>
  <a href="manage_venues.php" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left me-1"></i> Back
  </a>
</div>

<form action="/WebtechProject/controllers/VenueController.php" method="POST">
  <input type="hidden" name="action" value="update_venue">
  <input type="hidden" name="venue_id" value="<?= $venue['id'] ?>">

  <div class="card mb-4">
    <div class="card-header">
      <i class="bi bi-info-circle me-2"></i> Basic Information
    </div>
    <div class="card-body">

      <div class="mb-3">
        <label class="form-label">Venue Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control"
               value="<?= htmlspecialchars($venue['name']) ?>" required>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">City <span class="text-danger">*</span></label>
          <select name="city" class="form-select" required>
            <?php
            $cities = ['Dhaka','Chittagong','Cumilla','Cox\'s Bazar'];
            foreach ($cities as $city):
            ?>
            <option value="<?= $city ?>"
              <?= $venue['city'] === $city ? 'selected' : '' ?>>
              <?= $city ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Total Capacity <span class="text-danger">*</span></label>
          <input type="number" name="capacity" class="form-control"
                 value="<?= $venue['capacity'] ?>" min="1" required>
        </div>
      </div>

      <div class="mt-3">
        <label class="form-label">Full Address <span class="text-danger">*</span></label>
        <input type="text" name="address" class="form-control"
               value="<?= htmlspecialchars($venue['address']) ?>" required>
      </div>

      <div class="mt-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"
                  rows="3"><?= htmlspecialchars($venue['description']) ?></textarea>
      </div>

    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header">
      <i class="bi bi-list-check me-2"></i> Facilities
    </div>
    <div class="card-body">
      <div class="row g-2">
        <?php
        $allFacilities = ['AV Equipment','Parking','Catering','WiFi',
                          'Air Conditioning','Stage','Green Room',
                          'Projector','Lighting Rig','Security'];
        foreach ($allFacilities as $f):
        ?>
        <div class="col-md-3 col-6">
          <div class="form-check">
            <input class="form-check-input" type="checkbox"
                   name="facilities[]" value="<?= $f ?>"
                   id="f_<?= str_replace(' ','_',$f) ?>"
                   <?= in_array($f, $facilities) ? 'checked' : '' ?>>
            <label class="form-check-label" for="f_<?= str_replace(' ','_',$f) ?>">
              <?= $f ?>
            </label>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <?php if (!empty($photos)): ?>
  <div class="card mb-4">
    <div class="card-header">
      <i class="bi bi-images me-2"></i> Current Photo
    </div>
    <div class="card-body">
      <?php foreach ($photos as $photo): ?>
        <img src="/WebtechProject/public/uploads/venues/<?= htmlspecialchars($photo) ?>"
             class="rounded" style="height:180px; object-fit:cover; width:100%;">
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="d-flex justify-content-end gap-2 mb-4">
    <a href="manage_venues.php" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-floppy me-2"></i> Save Changes
    </button>
  </div>

</form>

<?php include '../shared/footer.php'; ?>