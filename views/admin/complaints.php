<?php $activePage = 'complaints'; ?>
<?php require_once 'views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- PAGE TITLE -->
        <div class="mb-4">
            <h1 class="page-title">Complaints</h1>
            <p class="text-muted">Review and resolve complaints submitted by platform users.</p>
        </div>

        <!-- OPEN COMPLAINTS -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-flag me-2"></i>Open Complaints</span>
                <span class="badge bg-danger">3 Open</span>
            </div>
            <div class="card-body p-0">
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
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">SA</div>
                                    Sarah Ahmed
                                </div>
                            </td>
                            <td>Rahman Events</td>
                            <td class="text-muted" style="max-width:250px;">
                                Event was cancelled without notice and refund not processed.
                            </td>
                            <td>14 May 2026</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="openResolveModal()">
                                    <i class="bi bi-check-circle"></i> Resolve
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">MR</div>
                                    Maruf Rahman
                                </div>
                            </td>
                            <td>Star Concerts</td>
                            <td class="text-muted" style="max-width:250px;">
                                Ticket tier was changed after purchase without notification.
                            </td>
                            <td>13 May 2026</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="openResolveModal()">
                                    <i class="bi bi-check-circle"></i> Resolve
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">JK</div>
                                    Jannat Khan
                                </div>
                            </td>
                            <td>City Halls Ltd</td>
                            <td class="text-muted" style="max-width:250px;">
                                Venue was not as described — no parking available on event day.
                            </td>
                            <td>12 May 2026</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="openResolveModal()">
                                    <i class="bi bi-check-circle"></i> Resolve
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RESOLVED COMPLAINTS -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-check-circle me-2"></i>Resolved Complaints</span>
                <span class="badge bg-success">12 Resolved</span>
            </div>
            <div class="card-body p-0">
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
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">AK</div>
                                    Arif Khan
                                </div>
                            </td>
                            <td>Mahinul Events</td>
                            <td class="text-muted" style="max-width:200px;">
                                Double charged for one booking.
                            </td>
                            <td class="text-muted" style="max-width:200px;">
                                Refund issued and organiser warned.
                            </td>
                            <td>10 May 2026</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">RI</div>
                                    Rina Islam
                                </div>
                            </td>
                            <td>BD Expo</td>
                            <td class="text-muted" style="max-width:200px;">
                                Check-in staff was rude and unprofessional.
                            </td>
                            <td class="text-muted" style="max-width:200px;">
                                Organiser contacted and issue acknowledged.
                            </td>
                            <td>08 May 2026</td>
                        </tr>
                    </tbody>
                </table>
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
            <div class="modal-body">
                <label class="form-label fw-semibold">Resolution Note <span class="text-danger">*</span></label>
                <textarea class="form-control" rows="4" placeholder="Describe how this complaint was resolved..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i> Mark as Resolved
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openResolveModal() {
    var modal = new bootstrap.Modal(document.getElementById('resolveModal'));
    modal.show();
}
</script>

<?php require_once '../../views/shared/footer.php'; ?>