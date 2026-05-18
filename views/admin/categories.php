<?php $activePage = 'categories'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="main-content">

        <div class="mb-4">
            <h1 class="page-title">Event Categories</h1>
            <p class="text-muted">Manage the categories used to classify events on the platform.</p>
        </div>

        <div class="row g-4">

            <!-- ADD CATEGORY FORM -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-plus-circle me-2"></i>Add New Category
                    </div>
                    <div class="card-body">
                        <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=add_category">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Icon <span class="text-muted">(one emoji)</span></label>
                                <input type="text" name="icon" class="form-control" placeholder="🎵" maxlength="2" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Music" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Brief description..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg me-1"></i> Add Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- CATEGORIES TABLE -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-tag me-2"></i>All Categories</span>
                        <span class="badge bg-secondary"><?= count($categories) ?> Total</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($categories)): ?>
                            <div class="p-4 text-muted">No categories yet. Add one!</div>
                        <?php else: ?>
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Events</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><?= htmlspecialchars($cat['icon']) ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($cat['name']) ?></td>
                                    <td class="text-muted"><?= htmlspecialchars($cat['description']) ?></td>
                                    <td><?= $cat['event_count'] ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                            onclick="openEditModal(<?= $cat['id'] ?>, '<?= addslashes($cat['name']) ?>', '<?= addslashes($cat['description']) ?>', '<?= addslashes($cat['icon']) ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=delete_category" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete this category?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=edit_category">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon</label>
                        <input type="text" name="icon" id="edit_icon" class="form-control" maxlength="2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openEditModal(id, name, description, icon) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_icon').value = icon;
    var modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}
</script>

<?php require_once __DIR__ . '/../../views/shared/footer.php'; ?>