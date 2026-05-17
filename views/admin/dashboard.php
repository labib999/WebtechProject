<?php $activePage = 'dashboard'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="main-content">

        <!-- WELCOME BANNER -->
        <div class="welcome-banner mb-4">
            <div class="welcome-left">
                <div class="welcome-date"><?= date('l, d F Y') ?></div>
                <h2 class="welcome-title">Good <?= (date('H') < 12) ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') ?>, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h2>
                <p class="welcome-sub">Here is your platform overview for today.</p>
            </div>
            <div class="welcome-right">
                <div class="welcome-stat">
                    <div class="welcome-stat-number"><?= $totalUsers ?></div>
                    <div class="welcome-stat-label">Total Users</div>
                </div>
                <div class="welcome-stat">
                    <div class="welcome-stat-number"><?= $upcomingEvents ?></div>
                    <div class="welcome-stat-label">Live Events</div>
                </div>
                <div class="welcome-stat">
                    <div class="welcome-stat-number"><?= $pendingTotal ?></div>
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
                    <div class="stat-number"><?= $totalUsers ?></div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#F0FDF4; color:#10B981;">
                        <i class="bi bi-calendar-event-fill"></i>
                    </div>
                    <div class="stat-label">Upcoming Events</div>
                    <div class="stat-number"><?= $upcomingEvents ?></div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FFF7ED; color:#F59E0B;">
                        <i class="bi bi-ticket-fill"></i>
                    </div>
                    <div class="stat-label">Tickets Sold Today</div>
                    <div class="stat-number"><?= $ticketsToday ?></div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FDF4FF; color:#A855F7;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-label">Revenue This Month</div>
                    <div class="stat-number">$<?= number_format($revenueThisMonth, 2) ?></div>
                </div>
            </div>
            <div class="col-md-4 col-lg">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FFF1F2; color:#EF4444;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stat-label">Pending Approvals</div>
                    <div class="stat-number"><?= $pendingTotal ?></div>
                </div>
            </div>
        </div>

        <!-- PENDING APPROVALS ALERT -->
        <?php if ($pendingTotal > 0): ?>
        <div class="alert alert-warning d-flex justify-content-between align-items-center mb-4">
            <span><i class="bi bi-exclamation-triangle-fill me-2"></i> You have <strong><?= $pendingTotal ?> pending approvals</strong> waiting for your review.</span>
            <a href="/WebtechProject/index.php?page=admin&action=approvals" class="btn btn-sm btn-warning">Review Now</a>
        </div>
        <?php endif; ?>

        <!-- BOTTOM ROW -->
        <div class="row g-3">

            <!-- USERS BY ROLE -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">Users by Role</div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usersByRole as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(ucfirst($row['role'])) ?></td>
                                    <td><?= $row['total'] ?></td>
                                </tr>
                                <?php endforeach; ?>
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
                        <a href="/WebtechProject/index.php?page=admin&action=users" class="btn btn-sm btn-outline-secondary">View All</a>
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
                                <?php foreach (array_slice($recentUsers, 0, 5) as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><span class="badge bg-secondary"><?= ucfirst($user['role']) ?></span></td>
                                    <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/shared/footer.php'; ?>