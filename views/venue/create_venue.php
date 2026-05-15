<?php
require_once '../shared/auth_guard.php';
$activePage = 'create_venue';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">Create Venue</h4>
    <p class="text-muted mb-0" style="font-size:14px;">Add a new venue to your portfolio</p>
  </div>
  <a href="manage_venues.php" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left me-1"></i> Back
  </a>
</div>

<form action="/WebtechProject/controllers/VenueController.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="action" value="create_venue">

  <div class="card mb-4">
    <div class="card-header">
      <i class="bi bi-info-circle me-2"></i> Basic Information
    </div>
    <div class="card-body">

      <div class="mb-3">
        <label class="form-label">Venue Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control"
               placeholder="e.g. Bashundhara Convention City"
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">City <span class="text-danger">*</span></label>
          <select name="city" class="form-select" required>
            <option value="">Select city</option>
            <?php
            $cities = ['Dhaka','Chittagong','Cumilla','Cox\'s Bazar'];
            foreach ($cities as $city):
            ?>
            <option value="<?= $city ?>"
              <?= ($_POST['city'] ?? '') === $city ? 'selected' : '' ?>>
              <?= $city ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Total Capacity <span class="text-danger">*</span></label>
          <input type="number" name="capacity" class="form-control"
                 placeholder="e.g. 500" min="1"
                 value="<?= htmlspecialchars($_POST['capacity'] ?? '') ?>" required>
        </div>
      </div>

      <div class="mt-3">
        <label class="form-label">Full Address <span class="text-danger">*</span></label>
        <input type="text" name="address" class="form-control"
               placeholder="e.g. Bashundhara, Dhaka 1229"
               value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" required>
      </div>

      <div class="mt-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"
                  placeholder="Describe your venue..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
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
        $facilities = ['AV Equipment','Parking','Catering','WiFi',
                       'Air Conditioning','Stage','Green Room',
                       'Projector','Lighting Rig','Security'];
        foreach ($facilities as $f):
        ?>
        <div class="col-md-3 col-6">
          <div class="form-check">
            <input class="form-check-input" type="checkbox"
                   name="facilities[]" value="<?= $f ?>" id="f_<?= str_replace(' ','_',$f) ?>"
                   <?= in_array($f, $_POST['facilities'] ?? []) ? 'checked' : '' ?>>
            <label class="form-check-label" for="f_<?= str_replace(' ','_',$f) ?>">
              <?= $f ?>
            </label>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header">
      <i class="bi bi-images me-2"></i> Venue Photo
    </div>
    <div class="card-body">
      <label class="form-label">Upload Photo</label>
      <input type="file" name="photos[]" class="form-control" accept="image/*"
             onchange="previewPhoto(this)">
      <div class="mt-3" id="previewBox" style="display:none;">
        <img id="previewImg" src="" class="rounded"
             style="height:200px; object-fit:cover; width:100%;">
      </div>
      <small class="text-muted">Supported: JPG, PNG, WEBP — Max 2MB</small>
    </div>
  </div>

  <div class="d-flex justify-content-end gap-2 mb-4">
    <a href="manage_venues.php" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-floppy me-2"></i> Save Venue
    </button>
  </div>

</form>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewBox').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../shared/footer.php'; ?>