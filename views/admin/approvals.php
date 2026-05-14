<?php $activePage = 'approvals'; ?>
<?php require_once '../../config/db.php'; ?>
<?php require_once '../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- PAGE TITLE -->
        <div class="mb-4">
            <h1 class="page-title">Approvals</h1>
            <p class="text-muted">Review and approve pending organiser and venue manager registrations.</p>
        </div>

        <!-- ORGANISER APPROVALS -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-check me-2"></i>Pending Organisers</span>
                <span class="badge bg-warning text-dark">3 Pending</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Organisation</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">RA</div>
                                    Rahman Events
                                </div>
                            </td>
                            <td>rahman@events.com</td>
                            <td>Rahman Events Ltd</td>
                            <td>14 May 2026</td>
                            <td>
                                <button class="btn btn-sm btn-success me-1">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">SC</div>
                                    Star Concerts
                                </div>
                            </td>
                            <td>star@concerts.com</td>
                            <td>Star Concerts BD</td>
                            <td>13 May 2026</td>
                            <td>
                                <button class="btn btn-sm btn-success me-1">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- VENUE MANAGER APPROVALS -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-building-check me-2"></i>Pending Venue Managers</span>
                <span class="badge bg-warning text-dark">1 Pending</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">CH</div>
                                    City Halls Ltd
                                </div>
                            </td>
                            <td>city@halls.com</td>
                            <td>+880 1700 000000</td>
                            <td>12 May 2026</td>
                            <td>
                                <button class="btn btn-sm btn-success me-1">
                                    <i class="bi bi-check-lg"></i> Approve
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- APPROVED ORGANISERS -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>Approved Organisers</span>
                <span class="badge bg-success">40 Active</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Organisation</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar">ME</div>
                                    Mahinul Events
                                </div>
                            </td>
                            <td>mahinul@events.com</td>
                            <td>Mahinul Events BD</td>
                            <td><span class="badge-active px-2 py-1 rounded">Active</span></td>
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