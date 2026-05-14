<?php $activePage = 'dashboard'; ?>
<?php require_once '../../config/db.php'; ?>
<?php require_once '../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- WELCOME BANNER -->
        <div class="welcome-banner mb-4">
            <div class="welcome-left">
                <div class="welcome-date"><?= date('l, d F Y') ?></div>
                <h2 class="welcome-title">Good <?= (date('H') < 12) ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') ?>, Admin 👋</h2>
                <p class="welcome-sub">Here is your platform overview for today.</p>
            </div>
            <div class="welcome-right">
                <div class="welcome-stat">
                    <div class="welcome-stat-number">248</div>
                    <div class="welcome-stat-label">Total Users</div>
                </div>
                <div class="welcome-stat">
                    <div class="welcome-stat-number">34</div>
                    <div class="welcome-stat-label">Live Events</div>
                </div>
                <div class="welcome-stat">
                    <div class="welcome-stat-number">3</div>
                    <div class="welcome-stat-label">Pending</div>
                </div>
            </div>
        </div>

        <!-- KPI CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#EFF6FF; color:#3B82F6;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-number">248</div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#F0FDF4; color:#10B981;">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <div class="stat-label">Upcoming Events</div>
                    <div class="stat-number">34</div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FFF7ED; color:#F59E0B;">
                        <i class="bi bi-ticket-fill"></i>
                    </div>
                    <div class="stat-label">Tickets Sold Today</div>
                    <div class="stat-number">12</div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FDF4FF; color:#A855F7;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-label">Revenue This Month</div>
                    <div class="stat-number">$4,820</div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FFF1F2; color:#EF4444;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stat-label">Pending Approvals</div>
                    <div class="stat-number">3</div>
                </div>
            </div>
        </div>

        <!-- PENDING APPROVALS ALERT -->
        <div class="alert alert-warning d-flex justify-content-between align-items-center mb-4">
            <span><i class="bi bi-exclamation-triangle-fill me-2"></i> You have <strong>3 pending approvals</strong> waiting for your review.</span>
            <a href="approvals.php" class="btn btn-sm btn-warning">Review Now</a>
        </div>

        <!-- BOTTOM ROW -->
        <div class="row g-3">

            <!-- USERS BY ROLE -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Users by Role</span>
                    </div>
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
                                    <td><span class="badge-active px-2 py-1 rounded"> Attendee</span></td>
                                    <td>180</td>
                                    <td>175</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td><span class="badge-pending px-2 py-1 rounded"> Organiser</span></td>
                                    <td>45</td>
                                    <td>40</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td><span class="badge-active px-2 py-1 rounded"> Venue Manager</span></td>
                                    <td>23</td>
                                    <td>20</td>
                                    <td>3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RECENT REGISTRATIONS -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Recent Registrations</span>
                        <a href="users.php" class="btn btn-sm btn-outline-secondary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sarah Ahmed</td>
                                    <td><span class="badge bg-primary">Attendee</span></td>
                                    <td>Today</td>
                                </tr>
                                <tr>
                                    <td>Rahman Events</td>
                                    <td><span class="badge bg-warning text-dark">Organiser</span></td>
                                    <td>Yesterday</td>
                                </tr>
                                <tr>
                                    <td>City Halls Ltd</td>
                                    <td><span class="badge bg-success">Venue Mgr</span></td>
                                    <td>2 days ago</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once '../../views/shared/footer.php'; ?>