<?php require_once '../../config/db.php'; ?>
<?php require_once '../../views/shared/header.php'; ?>


<div class="wrapper">
    <?php $activePage = 'dashboard'; ?>
    <?php require_once '../../views/admin/navbar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- PAGE TITLE -->
        <div class="mb-4">
            <h1 class="page-title">Dashboard</h1>
            <p class="text-muted">Welcome back, Admin. Here's what's happening today.</p>
        </div>

        <!-- KPI CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-label">👥 Total Users</div>
                    <div class="stat-number">248</div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-label">🎪 Upcoming Events</div>
                    <div class="stat-number">34</div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-label">🎟️ Tickets Sold Today</div>
                    <div class="stat-number">12</div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-label">💰 Revenue This Month</div>
                    <div class="stat-number">$4,820</div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-label">⏳ Pending Approvals</div>
                    <div class="stat-number">3</div>
                </div>
            </div>
        </div>

        <!-- PENDING APPROVALS ALERT -->
        <div class="alert alert-warning d-flex justify-content-between align-items-center mb-4">
            <span>⚠️ You have <strong>3 pending approvals</strong> waiting for your review.</span>
            <a href="approvals.php" class="btn btn-sm btn-warning">Review Now</a>
        </div>

        <!-- USERS BY ROLE TABLE -->
        <div class="card">
            <div class="card-header">Users by Role</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Total</th>
                            <th>Active</th>
                            <th>Suspended</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>🎟️ Attendee</td>
                            <td>180</td>
                            <td>175</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>🎪 Organiser</td>
                            <td>45</td>
                            <td>40</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>🏟️ Venue Manager</td>
                            <td>23</td>
                            <td>20</td>
                            <td>3</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php require_once '../../views/shared/footer.php'; ?>