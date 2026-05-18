<?php $activePage = 'complaints'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="main-content">

        <div class="mb-4">
            <h1 class="page-title">Complaints</h1>
            <p class="text-muted">Review and resolve complaints submitted by platform users.</p>
        </div>

        <!-- OPEN COMPLAINTS -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-flag me-2"></i>Open Complaints</span>
                <span class="badge bg-danger"><?= count($openComplaints) ?> Open</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($openComplaints)): ?>
                    <div class="p-4 text-muted">No open complaints. All clear!</div>
                <?php else: ?>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Submitted By</th>
                            <th>Against</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($openComplaints as $complaint): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar"><?= strtoupper(substr($complaint['submitter_name'], 0, 2)) ?></div>
                                    <?= htmlspecialchars($complaint['submitter_name']) ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($complaint['against_name']) ?></td>
                            <td class="text-muted" style="max-width:250px;">
                                <?= htmlspecialchars($complaint['description']) ?>
                            </td>
                            <td><?= date('d M Y', strtotime($complaint['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary" 
                                    onclick="openResolveModal(<?= $complaint['id'] ?>)">
                                    <i class="bi bi-check-circle"></i> Resolve
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- RESOLVED COMPLAINTS -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-check-circle me-2"></i>Resolved Complaints</span>
                <span class="badge bg-success"><?= count($resolvedComplaints) ?> Resolved</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($resolvedComplaints)): ?>
                    <div class="p-4 text-muted">No resolved complaints yet.</div>
                <?php else: ?>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Submitted By</th>
                            <th>Against</th>
                            <th>Description</th>
                            <th>Resolution Note</th>
                            <th>Resolved On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resolvedComplaints as $complaint): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar"><?= strtoupper(substr($complaint['submitter_name'], 0, 2)) ?></div>
                                    <?= htmlspecialchars($complaint['submitter_name']) ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($complaint['against_name']) ?></td>
                            <td class="text-muted" style="max-width:200px;">
                                <?= htmlspecialchars($complaint['description']) ?>
                            </td>
                            <td class="text-muted" style="max-width:200px;">
                                <?= htmlspecialchars($complaint['admin_note']) ?>
                            </td>
                            <td><?= date('d M Y', strtotime($complaint['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- RESOLVE MODAL -->
<div class="modal fade" id="resolveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resolve Complaint</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=resolve_complaint">
                <input type="hidden" name="complaint_id" id="complaint_id">
                <div class="modal-body">
                    <label class="form-label fw-semibold">Resolution Note <span class="text-danger">*</span></label>
                    <textarea name="resolution_note" class="form-control" rows="4" 
                        placeholder="Describe how this complaint was resolved..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Mark as Resolved
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openResolveModal(id) {
    document.getElementById('complaint_id').value = id;
    var modal = new bootstrap.Modal(document.getElementById('resolveModal'));
    modal.show();
}
</script>

<?php require_once __DIR__ . '/../../views/shared/footer.php'; ?>