<?php $activePage = 'financial_report'; ?>
<?php require_once '../../config/db.php'; ?>
<?php require_once '../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- PAGE TITLE -->
        <div class="mb-4">
            <h1 class="page-title">Financial Report</h1>
            <p class="text-muted">Platform revenue overview for the current month.</p>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#F0FDF4; color:#10B981;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-label">Gross Sales This Month</div>
                    <div class="stat-number">$48,200</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">From 342 active bookings</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FDF4FF; color:#A855F7;">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="stat-label">Commission Earned</div>
                    <div class="stat-number">$4,820</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">10% platform commission rate</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#EFF6FF; color:#3B82F6;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="stat-label">Total Transactions</div>
                    <div class="stat-number">342</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">Active bookings this month</div>
                </div>
            </div>
        </div>

        <!-- BOTTOM TABLES ROW -->
        <div class="row g-4">

            <!-- TOP 5 EVENTS -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-trophy me-2"></i>Top 5 Events by Revenue
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event</th>
                                    <th>Organiser</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>🥇</td>
                                    <td>Dhaka Music Festival</td>
                                    <td>Rahman Events</td>
                                    <td class="fw-semibold text-success">$12,400</td>
                                </tr>
                                <tr>
                                    <td>🥈</td>
                                    <td>Tech Conference 2026</td>
                                    <td>Star Concerts</td>
                                    <td class="fw-semibold text-success">$9,800</td>
                                </tr>
                                <tr>
                                    <td>🥉</td>
                                    <td>Food Fest Dhaka</td>
                                    <td>Rahman Events</td>
                                    <td class="fw-semibold text-success">$7,200</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Art Exhibition BD</td>
                                    <td>Mahinul Events</td>
                                    <td class="fw-semibold text-success">$5,600</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Sports Day 2026</td>
                                    <td>City Sports</td>
                                    <td class="fw-semibold text-success">$4,100</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TOP 5 ORGANISERS -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-people me-2"></i>Top 5 Organisers by Revenue
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Organiser</th>
                                    <th>Events</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>🥇</td>
                                    <td>Rahman Events</td>
                                    <td>8</td>
                                    <td class="fw-semibold text-success">$19,600</td>
                                </tr>
                                <tr>
                                    <td>🥈</td>
                                    <td>Star Concerts</td>
                                    <td>5</td>
                                    <td class="fw-semibold text-success">$12,400</td>
                                </tr>
                                <tr>
                                    <td>🥉</td>
                                    <td>Mahinul Events</td>
                                    <td>4</td>
                                    <td class="fw-semibold text-success">$8,200</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>City Sports</td>
                                    <td>3</td>
                                    <td class="fw-semibold text-success">$5,400</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>BD Expo</td>
                                    <td>2</td>
                                    <td class="fw-semibold text-success">$2,600</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- REVENUE BY CATEGORY -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bar-chart me-2"></i>Revenue by Category
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Total Events</th>
                                    <th>Tickets Sold</th>
                                    <th>Revenue</th>
                                    <th>% of Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>🎵 Music</td>
                                    <td>12</td>
                                    <td>890</td>
                                    <td class="fw-semibold text-success">$18,400</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-primary" style="width:38%"></div>
                                            </div>
                                            38%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>🎤 Conference</td>
                                    <td>8</td>
                                    <td>620</td>
                                    <td class="fw-semibold text-success">$12,800</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-success" style="width:27%"></div>
                                            </div>
                                            27%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>🍽️ Food & Drink</td>
                                    <td>6</td>
                                    <td>410</td>
                                    <td class="fw-semibold text-success">$8,600</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-warning" style="width:18%"></div>
                                            </div>
                                            18%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>🎨 Arts & Culture</td>
                                    <td>5</td>
                                    <td>280</td>
                                    <td class="fw-semibold text-success">$5,200</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-danger" style="width:11%"></div>
                                            </div>
                                            11%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>🏅 Sports</td>
                                    <td>3</td>
                                    <td>150</td>
                                    <td class="fw-semibold text-success">$3,200</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-secondary" style="width:6%"></div>
                                            </div>
                                            6%
                                        </div>
                                    </td>
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