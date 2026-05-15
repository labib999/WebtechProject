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
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($venues as $venue):
      $photos     = json_decode($venue['photos'] ?? '[]', true);
      $facilities = json_decode($venue['facilities'] ?? '[]', true);
      $photo      = !empty($photos)
          ? '/webtechproject/WebtechProject/public/uploads/venues/' . $photos[0]
          : null;
    ?>
    <div class="col">
      <div class="card h-100 border" style="border-radius:12px; overflow:hidden;">

        <!-- Photo — fixed height same for all -->
        <?php if ($photo): ?>
          <img src="<?= htmlspecialchars($photo) ?>"
               alt="<?= htmlspecialchars($venue['name']) ?>"
               style="width:100%; height:200px; object-fit:cover; object-position:center; display:block;">
        <?php else: ?>
          <div style="width:100%; height:200px; background:#e2e8f0; display:flex; align-items:center; justify-content:center;">
            <i class="bi bi-building" style="font-size:40px; color:#94a3b8;"></i>
          </div>
        <?php endif; ?>

        <!-- Body -->
        <div class="card-body" style="padding:16px;">
          <h6 class="fw-bold mb-1" style="font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            <?= htmlspecialchars($venue['name']) ?>
          </h6>
          <p class="text-muted mb-2" style="font-size:12px;">
            <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($venue['city']) ?>
            &nbsp;|&nbsp;
            <i class="bi bi-people me-1"></i><?= number_format($venue['capacity']) ?> capacity
          </p>
          <div>
            <?php foreach (array_slice($facilities, 0, 3) as $f): ?>
              <span style="display:inline-block; padding:2px 8px; background:#eff6ff; color:#3b82f6; border-radius:20px; font-size:11px; font-weight:500; margin:2px;">
                <?= htmlspecialchars($f) ?>
              </span>
            <?php endforeach; ?>
            <?php if (count($facilities) > 3): ?>
              <span style="display:inline-block; padding:2px 8px; background:#eff6ff; color:#3b82f6; border-radius:20px; font-size:11px; font-weight:500; margin:2px;">
                +<?= count($facilities) - 3 ?> more
              </span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Footer -->
        <div class="card-footer bg-white" style="padding:12px 16px; border-top:1px solid #e2e8f0;">
          <div class="d-flex gap-2">
            <a href="calendar.php?venue_id=<?= $venue['id'] ?>"
               class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-calendar3"></i> Calendar
            </a>
            <a href="edit_venue.php?id=<?= $venue['id'] ?>"
               class="btn btn-primary btn-sm">
              <i class="bi bi-pen"></i> Edit
            </a>
            <form method="POST"
                  action="/webtechproject/WebtechProject/controllers/VenueController.php"
                  style="display:inline;"
                  onsubmit="return confirm('Delete this venue?')">
              <input type="hidden" name="action" value="delete_venue">
              <input type="hidden" name="venue_id" value="<?= $venue['id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </div>

      </div>
    </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include '../shared/footer.php'; ?>