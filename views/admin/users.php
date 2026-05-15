<?php $activePage = 'users'; ?>
<?php require_once '../../config/db.php'; ?>
<?php require_once '../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- page title -->
        <div class="mb-4">
            <h1 class="page-title">Users</h1>
            <p class="text-muted">View and manage all registered users on the platform.</p>
        </div>

        <!-- search bar -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" class="form-control" placeholder="Search by name or email...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select class="form-select">
                            <option value="">All Roles</option>
                            <option>Attendee</option>
                            <option>Organiser</option>
                            <option>Venue Manager</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select">
                            <option value="">All Statuses</option>
                            <option>Active</option>
                            <option>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- user table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>All Users</span>
                <span class="badge bg-secondary">248 Total</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">SA</div>
                                    <div>
                                        <div class="fw-semibold">Sarah Ahmed</div>
                                        <div class="text-muted" style="font-size:0.8rem;">+880 1700 111111</div>
                                    </div>
                                </div>
                            </td>
                            <td>sarah@email.com</td>
                            <td><span class="badge bg-primary">Attendee</span></td>
                            <td><span class="badge-active px-2 py-1 rounded">Active</span></td>
                            <td>01 May 2026</td>
                            <td>
                                <button class="btn btn-sm btn-warning">
                                    <i class="bi bi-pause-circle"></i> Suspend
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">RE</div>
                                    <div>
                                        <div class="fw-semibold">Rahman Events</div>
                                        <div class="text-muted" style="font-size:0.8rem;">+880 1700 222222</div>
                                    </div>
                                </div>
                            </td>
                            <td>rahman@events.com</td>
                            <td><span class="badge bg-warning text-dark">Organiser</span></td>
                            <td><span class="badge-active px-2 py-1 rounded">Active</span></td>
                            <td>10 Apr 2026</td>
                            <td>
                                <button class="btn btn-sm btn-warning">
                                    <i class="bi bi-pause-circle"></i> Suspend
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">CH</div>
                                    <div>
                                        <div class="fw-semibold">City Halls Ltd</div>
                                        <div class="text-muted" style="font-size:0.8rem;">+880 1700 333333</div>
                                    </div>
                                </div>
                            </td>
                            <td>city@halls.com</td>
                            <td><span class="badge bg-success">Venue Mgr</span></td>
                            <td><span class="badge-suspended px-2 py-1 rounded">Suspended</span></td>
                            <td>05 Mar 2026</td>
                            <td>
                                <button class="btn btn-sm btn-success">
                                    <i class="bi bi-play-circle"></i> Reactivate
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">MR</div>
                                    <div>
                                        <div class="fw-semibold">Maruf Rahman</div>
                                        <div class="text-muted" style="font-size:0.8rem;">+880 1700 444444</div>
                                    </div>
                                </div>
                            </td>
                            <td>maruf@email.com</td>
                            <td><span class="badge bg-primary">Attendee</span></td>
                            <td><span class="badge-active px-2 py-1 rounded">Active</span></td>
                            <td>12 May 2026</td>
                            <td>
                                <button class="btn btn-sm btn-warning">
                                    <i class="bi bi-pause-circle"></i> Suspend
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php require_once '../../views/shared/footer.php'; ?>