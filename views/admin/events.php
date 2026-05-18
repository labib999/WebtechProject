<?php $activePage = 'events'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="main-content">

        <div class="mb-4">
            <h1 class="page-title">All Events</h1>
            <p class="text-muted">View and manage all events across the platform.</p>
        </div>

        <!-- FILTER BAR -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="http://localhost/WebtechProject/index.php">
                    <input type="hidden" name="page" value="admin">
                    <input type="hidden" name="action" value="events">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="published" <?= isset($_GET['status']) && $_GET['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                                <option value="draft" <?= isset($_GET['status']) && $_GET['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="cancelled" <?= isset($_GET['status']) && $_GET['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                <option value="completed" <?= isset($_GET['status']) && $_GET['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= isset($_GET['category_id']) && $_GET['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['icon'] . ' ' . $cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel me-1"></i> Apply Filters
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="http://localhost/WebtechProject/index.php?page=admin&action=events" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x me-1"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- EVENTS TABLE -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event me-2"></i>Events</span>
                <span class="badge bg-secondary"><?= count($events) ?> Total</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($events)): ?>
                    <div class="p-4 text-muted">No events found.</div>
                <?php else: ?>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Organiser</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Tickets</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?= htmlspecialchars($event['title']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($event['organiser_name']) ?></td>
                            <td><?= htmlspecialchars($event['category_icon'] . ' ' . $event['category_name']) ?></td>
                            <td><?= date('d M Y', strtotime($event['event_datetime'])) ?></td>
                            <td><?= $event['tickets_sold'] ?></td>
                            <td>
                                <?php
                                $badgeClass = match($event['status']) {
                                    'published' => 'badge-active',
                                    'draft'     => 'badge-pending',
                                    'cancelled' => 'badge-cancelled',
                                    'completed' => 'badge-suspended',
                                    default     => 'badge-pending'
                                };
                                ?>
                                <span class="<?= $badgeClass ?> px-2 py-1 rounded">
                                    <?= ucfirst($event['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        <?= $event['is_featured'] ? 'checked' : '' ?>
                                        <?= $event['status'] === 'completed' || $event['status'] === 'cancelled' ? 'disabled' : '' ?>
                                        onchange="toggleFeatured(this, <?= $event['id'] ?>)">
                                </div>
                            </td>
                            <td>
                                <?php if ($event['status'] !== 'completed' && $event['status'] !== 'cancelled'): ?>
                                <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=cancel_event">
                                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Cancel this event? This cannot be undone.')">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                </form>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size:0.8rem;">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleFeatured(checkbox, eventId) {
    var isFeatured = checkbox.checked ? 1 : 0;
    checkbox.disabled = true;

    fetch('http://localhost/WebtechProject/api/toggle_featured.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'event_id=' + eventId + '&is_featured=' + isFeatured
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            checkbox.checked = !checkbox.checked;
            alert(data.message);
        }
        checkbox.disabled = false;
    })
    .catch(function() {
        checkbox.checked = !checkbox.checked;
        checkbox.disabled = false;
    });
}
</script>
<?php require_once __DIR__ . '/../../views/shared/footer.php'; ?>