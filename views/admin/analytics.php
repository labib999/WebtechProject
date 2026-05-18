<?php $activePage = 'analytics'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- PAGE TITLE -->
        <div class="mb-4">
            <h1 class="page-title">Platform Analytics</h1>
            <p class="text-muted">Platform-wide performance metrics and trends.</p>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#EFF6FF; color:#3B82F6;">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="stat-label">Events This Month</div>
                    <div class="stat-number">34</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">↑ 12% from last month</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#F0FDF4; color:#10B981;">
                        <i class="bi bi-ticket"></i>
                    </div>
                    <div class="stat-label">Tickets Sold This Month</div>
                    <div class="stat-number">1,240</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">↑ 8% from last month</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FFF7ED; color:#F59E0B;">
                        <i class="bi bi-star"></i>
                    </div>
                    <div class="stat-label">Avg Event Rating</div>
                    <div class="stat-number">4.2</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">From 320 reviews</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FDF4FF; color:#A855F7;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-label">New Users This Month</div>
                    <div class="stat-number">48</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">↑ 5% from last month</div>
                </div>
            </div>
        </div>

        <!-- TABLES ROW -->
        <div class="row g-4">

            <!-- EVENT VOLUME PER MONTH -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-graph-up me-2"></i>Event Volume per Month
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Events</th>
                                    <th>Tickets Sold</th>
                                    <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>May 2026</td>
                                    <td>34</td>
                                    <td>1,240</td>
                                    <td class="text-success">↑ 12%</td>
                                </tr>
                                <tr>
                                    <td>Apr 2026</td>
                                    <td>28</td>
                                    <td>1,050</td>
                                    <td class="text-success">↑ 6%</td>
                                </tr>
                                <tr>
                                    <td>Mar 2026</td>
                                    <td>24</td>
                                    <td>890</td>
                                    <td class="text-danger">↓ 3%</td>
                                </tr>
                                <tr>
                                    <td>Feb 2026</td>
                                    <td>26</td>
                                    <td>920</td>
                                    <td class="text-success">↑ 9%</td>
                                </tr>
                                <tr>
                                    <td>Jan 2026</td>
                                    <td>22</td>
                                    <td>780</td>
                                    <td class="text-muted">—</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- MOST POPULAR CATEGORIES -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-tag me-2"></i>Most Popular Categories
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Events</th>
                                    <th>Tickets Sold</th>
                                    <th>Avg Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>🎵 Music</td>
                                    <td>12</td>
                                    <td>890</td>
                                    <td>⭐ 4.5</td>
                                </tr>
                                <tr>
                                    <td>🎤 Conference</td>
                                    <td>8</td>
                                    <td>620</td>
                                    <td>⭐ 4.2</td>
                                </tr>
                                <tr>
                                    <td>🍽️ Food & Drink</td>
                                    <td>6</td>
                                    <td>410</td>
                                    <td>⭐ 4.4</td>
                                </tr>
                                <tr>
                                    <td>🎨 Arts & Culture</td>
                                    <td>5</td>
                                    <td>280</td>
                                    <td>⭐ 4.1</td>
                                </tr>
                                <tr>
                                    <td>🏅 Sports</td>
                                    <td>3</td>
                                    <td>150</td>
                                    <td>⭐ 3.9</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- MOST POPULAR CITIES -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-geo-alt me-2"></i>Most Popular Cities
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>City</th>
                                    <th>Total Events</th>
                                    <th>Tickets Sold</th>
                                    <th>Revenue</th>
                                    <th>% of Platform</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>📍 Dhaka</td>
                                    <td>22</td>
                                    <td>890</td>
                                    <td class="fw-semibold text-success">$32,400</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-primary" style="width:65%"></div>
                                            </div>
                                            65%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>📍 Chittagong</td>
                                    <td>8</td>
                                    <td>320</td>
                                    <td class="fw-semibold text-success">$11,200</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-success" style="width:22%"></div>
                                            </div>
                                            22%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>📍 Sylhet</td>
                                    <td>4</td>
                                    <td>130</td>
                                    <td class="fw-semibold text-success">$4,600</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-warning" style="width:9%"></div>
                                            </div>
                                            9%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>📍 Rajshahi</td>
                                    <td>2</td>
                                    <td>60</td>
                                    <td class="fw-semibold text-success">$2,100</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-danger" style="width:4%"></div>
                                            </div>
                                            4%
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

<?php require_once __DIR__ . '/../../views/shared/footer.php'; ?>