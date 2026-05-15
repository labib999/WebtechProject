<?php
require_once '../../config/db.php';
require_once '../shared/auth_guard.php';
require_once '../../models/VenueModel.php';

$model     = new VenueModel();
$managerId = $_SESSION['user_id'];
$venues    = $model->getVenuesByManager($managerId);

$activePage = 'manage_venues';
include '../shared/header.php';
include '../shared/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">My Venues</h4>
    <p class="text-muted mb-0" style="font-size:14px;">Manage all your venue properties</p>
  </div>
  <a href="create_venue.php" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-circle me-1"></i> Add Venue
  </a>
</div>

<?php if (empty($venues)): ?>
  <div class="card p-5 text-center">
    <i class="bi bi-building" style="font-size:48px; color:#e2e8f0;"></i>
    <h5 class="mt-3 text-muted">No venues yet</h5>
    <p class="text-muted" style="font-size:14px;">Create your first venue to get started</p>
    <a href="create_venue.php" class="btn btn-primary mx-auto" style="width:fit-content;">
      <i class="bi bi-plus-circle me-2"></i> Create Venue
    </a>
  </div>

<?php else: ?>
  <div class="row g-4">
    <?php foreach ($venues as $venue):
      $photos     = json_decode($venue['photos'] ?? '[]', true);
      $facilities = json_decode($venue['facilities'] ?? '[]', true);
      $photo      = !empty($photos) ? '/WebtechProject/public/uploads/venues/' . $photos[0] : null;
    ?>
    <div class="col-md-4">
      <div class="venue-card">

        <?php if ($photo): ?>
          <img src="<?= htmlspecialchars($photo) ?>" class="venue-card-img" alt="<?= htmlspecialchars($venue['name']) ?>">
        <?php else: ?>
          <div class="venue-card-img d-flex align-items-center justify-content-center bg-light">
            <i class="bi bi-building" style="font-size:40px; color:#94a3b8;"></i>
          </div>
        <?php endif; ?>

        <div class="venue-card-body">
          <h5><?= htmlspecialchars($venue['name']) ?></h5>
          <p class="text-muted" style="font-size:13px; margin-bottom:10px;">
            <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($venue['city']) ?> &nbsp;|&nbsp;
            <i class="bi bi-people me-1"></i><?= number_format($venue['capacity']) ?> capacity
          </p>
          <div>
            <?php foreach (array_slice($facilities, 0, 4) as $f): ?>
              <span class="facility-tag"><?= htmlspecialchars($f) ?></span>
            <?php endforeach; ?>
            <?php if (count($facilities) > 4): ?>
              <span class="facility-tag">+<?= count($facilities) - 4 ?> more</span>
            <?php endif; ?>
          </div>
        </div>

        <div class="venue-card-footer">
          <a href="calendar.php?venue_id=<?= $venue['id'] ?>"
             class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-calendar3"></i> Calendar
          </a>
          <a href="edit_venue.php?id=<?= $venue['id'] ?>"
             class="btn btn-primary btn-sm">
            <i class="bi bi-pen"></i> Edit
          </a>
          <form method="POST" action="/WebtechProject/controllers/VenueController.php"
                style="display:inline;"
                onsubmit="return confirm('Delete this venue? This cannot be undone.')">
            <input type="hidden" name="action" value="delete_venue">
            <input type="hidden" name="venue_id" value="<?= $venue['id'] ?>">
            <button type="submit" class="btn btn-danger btn-sm">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>

      </div>
    </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include '../shared/footer.php'; ?>