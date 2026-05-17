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

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1">My Venues</h4>
    <p class="text-muted mb-0" style="font-size:14px;">
      Manage all your venue properties
      <span class="badge ms-2" style="background:#eff6ff; color:#3b82f6; font-size:12px;">
        <?= count($venues) ?> venues
      </span>
    </p>
  </div>
  <a href="create_venue.php" class="btn btn-primary">
    <i class="bi bi-plus-circle me-2"></i> Add New Venue
  </a>
</div>

<?php if (empty($venues)): ?>
  <div class="text-center py-5 rounded-3" style="background:#fff; border:2px dashed #e2e8f0;">
    <i class="bi bi-building" style="font-size:52px; color:#e2e8f0;"></i>
    <h5 class="mt-3 fw-semibold">No venues yet</h5>
    <p class="text-muted" style="font-size:14px;">Create your first venue to get started</p>
    <a href="create_venue.php" class="btn btn-primary">
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
      <div class="h-100 rounded-3 overflow-hidden position-relative"
           style="background:#fff; border:1px solid #e2e8f0;
                  transition: box-shadow 0.2s, transform 0.2s;"
           onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.1)'; this.style.transform='translateY(-2px)'"
           onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'">

        <!-- Photo -->
        <?php if ($photo): ?>
          <div style="position:relative; overflow:hidden; height:190px;">
            <img src="<?= htmlspecialchars($photo) ?>"
                 alt="<?= htmlspecialchars($venue['name']) ?>"
                 style="width:100%; height:190px; object-fit:cover; object-position:center; display:block; transition:transform 0.3s;"
                 onmouseover="this.style.transform='scale(1.05)'"
                 onmouseout="this.style.transform='scale(1)'">
            <div style="position:absolute; top:10px; right:10px;">
              <span style="background:rgba(255,255,255,0.95); color:#10b981; font-size:11px;
                           font-weight:600; padding:3px 10px; border-radius:20px;">
                <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i> Active
              </span>
            </div>
          </div>
        <?php else: ?>
          <div style="height:190px; background:linear-gradient(135deg, #f1f5f9, #e2e8f0);
                      display:flex; align-items:center; justify-content:center; position:relative;">
            <i class="bi bi-building" style="font-size:48px; color:#94a3b8;"></i>
            <div style="position:absolute; top:10px; right:10px;">
              <span style="background:rgba(255,255,255,0.95); color:#10b981; font-size:11px;
                           font-weight:600; padding:3px 10px; border-radius:20px;">
                <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i> Active
              </span>
            </div>
          </div>
        <?php endif; ?>

        <!-- Body -->
        <div style="padding:16px 18px;">
          <h6 class="fw-bold mb-1"
              style="font-size:15px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            <?= htmlspecialchars($venue['name']) ?>
          </h6>

          <div class="d-flex gap-3 mb-3" style="font-size:12px; color:#64748b;">
            <span>
              <i class="bi bi-geo-alt me-1" style="color:#3b82f6;"></i>
              <?= htmlspecialchars($venue['city']) ?>
            </span>
            <span>
              <i class="bi bi-people me-1" style="color:#10b981;"></i>
              <?= number_format($venue['capacity']) ?> capacity
            </span>
          </div>

          <div>
            <?php foreach (array_slice($facilities, 0, 3) as $f): ?>
              <span style="display:inline-block; padding:3px 9px; background:#f8fafc;
                           color:#475569; border:1px solid #e2e8f0; border-radius:20px;
                           font-size:11px; font-weight:500; margin:2px;">
                <?= htmlspecialchars($f) ?>
              </span>
            <?php endforeach; ?>
            <?php if (count($facilities) > 3): ?>
              <span style="display:inline-block; padding:3px 9px; background:#eff6ff;
                           color:#3b82f6; border-radius:20px; font-size:11px; font-weight:500; margin:2px;">
                +<?= count($facilities) - 3 ?> more
              </span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Footer -->
        <div style="padding:12px 18px; border-top:1px solid #f1f5f9; background:#fafafa;
                    display:flex; gap:8px;">
          <a href="calendar.php?venue_id=<?= $venue['id'] ?>"
             class="btn btn-sm flex-fill"
             style="background:#f1f5f9; color:#475569; border:none; font-size:12px;">
            <i class="bi bi-calendar3 me-1"></i> Calendar
          </a>
          <a href="edit_venue.php?id=<?= $venue['id'] ?>"
             class="btn btn-sm flex-fill"
             style="background:#eff6ff; color:#3b82f6; border:none; font-size:12px;">
            <i class="bi bi-pen me-1"></i> Edit
          </a>
          <form method="POST"
                action="/webtechproject/WebtechProject/controllers/VenueController.php"
                style="display:inline;"
                onsubmit="return confirm('Delete this venue? This cannot be undone.')">
            <input type="hidden" name="action" value="delete_venue">
            <input type="hidden" name="venue_id" value="<?= $venue['id'] ?>">
            <button type="submit" class="btn btn-sm"
                    style="background:#fef2f2; color:#ef4444; border:none; font-size:12px;">
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