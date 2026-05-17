<?php $activePage = 'events'; ?>
<?php require_once 'views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- PAGE TITLE -->
        <div class="mb-4">
            <h1 class="page-title">All Events</h1>
            <p class="text-muted">View and manage all events across the platform.</p>
        </div>

        <!-- FILTER BAR -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select">
                            <option value="">All Statuses</option>
                            <option>Published</option>
                            <option>Draft</option>
                            <option>Cancelled</option>
                            <option>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Organiser</label>
                        <select class="form-select">
                            <option value="">All Organisers</option>
                            <option>Rahman Events</option>
                            <option>Star Concerts</option>
                            <option>Mahinul Events</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select class="form-select">
                            <option value="">All Categories</option>
                            <option>Music</option>
                            <option>Conference</option>
                            <option>Sports</option>
                            <option>Arts & Culture</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- EVENTS TABLE -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event me-2"></i>Events</span>
                <span class="badge bg-secondary">34 Total</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Organiser</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Tickets Sold</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="fw-semibold">Dhaka Music Festival</div>
                                <div class="text-muted" style="font-size:0.8rem;">Bashundhara Convention</div>
                            </td>
                            <td>Rahman Events</td>
                            <td>🎵 Music</td>
                            <td>20 May 2026</td>
                            <td>142</td>
                            <td><span class="badge-active px-2 py-1 rounded">Published</span></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch">
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmCancel()">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="fw-semibold">Tech Conference 2026</div>
                                <div class="text-muted" style="font-size:0.8rem;">BICC, Dhaka</div>
                            </td>
                            <td>Star Concerts</td>
                            <td>🎤 Conference</td>
                            <td>25 May 2026</td>
                            <td>89</td>
                            <td><span class="badge-active px-2 py-1 rounded">Published</span></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch">
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmCancel()">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="fw-semibold">Art Exhibition BD</div>
                                <div class="text-muted" style="font-size:0.8rem;">National Museum</div>
                            </td>
                            <td>Mahinul Events</td>
                            <td>🎨 Arts & Culture</td>
                            <td>01 Jun 2026</td>
                            <td>0</td>
                            <td><span class="badge-pending px-2 py-1 rounded">Draft</span></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch">
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmCancel()">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="fw-semibold">Food Fest Dhaka</div>
                                <div class="text-muted" style="font-size:0.8rem;">Hatirjheel</div>
                            </td>
                            <td>Rahman Events</td>
                            <td>🍽️ Food & Drink</td>
                            <td>10 Apr 2026</td>
                            <td>210</td>
                            <td><span class="badge-suspended px-2 py-1 rounded">Completed</span></td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" checked disabled>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger" disabled>
                                    <i class="bi bi-x-circle"></i> Cancel
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- CANCEL CONFIRMATION MODAL -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this event? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
                <button type="button" class="btn btn-danger">Yes, Cancel Event</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmCancel() {
    var modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}
</script>

<?php require_once '../../views/shared/footer.php'; ?>