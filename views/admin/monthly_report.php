<?php $activePage = 'monthly_report'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- PAGE TITLE -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">Monthly Report</h1>
                <p class="text-muted">Comprehensive platform report covering users, events, revenue and top performers.</p>
            </div>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-download me-1"></i> Export / Print
            </button>
        </div>

        <!-- MONTH SELECTOR -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-end g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Select Month</label>
                        <input type="month" class="form-control" value="2026-05">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Generate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- REPORT HEADER -->
        <div class="card mb-4" style="background: linear-gradient(135deg, #1E293B 0%, #0F6E56 100%); color:#fff;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div style="font-size:0.85rem; opacity:0.7;">EMTS Platform Report</div>
                        <h3 class="fw-bold mt-1">May 2026 — Monthly Summary</h3>
                        <div style="font-size:0.9rem; opacity:0.7;">Generated on <?= date('d M Y') ?></div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div style="font-size:0.85rem; opacity:0.7;">Platform Commission Rate</div>
                        <div style="font-size:2rem; font-weight:700;">10%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#EFF6FF; color:#3B82F6;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-label">New Users</div>
                    <div class="stat-number">48</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">Total registered: 248</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#F0FDF4; color:#10B981;">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="stat-label">Events Published</div>
                    <div class="stat-number">34</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">8 completed this month</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FFF7ED; color:#F59E0B;">
                        <i class="bi bi-ticket"></i>
                    </div>
                    <div class="stat-label">Tickets Sold</div>
                    <div class="stat-number">1,240</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">342 active bookings</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#F0FDF4; color:#10B981;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-label">Gross Revenue</div>
                    <div class="stat-number">$48,200</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">Commission: $4,820</div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <!-- TOP EVENTS -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-trophy me-2"></i>Top Events This Month
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event</th>
                                    <th>Tickets</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>🥇</td>
                                    <td>Dhaka Music Festival</td>
                                    <td>420</td>
                                    <td class="text-success fw-semibold">$12,400</td>
                                </tr>
                                <tr>
                                    <td>🥈</td>
                                    <td>Tech Conference 2026</td>
                                    <td>310</td>
                                    <td class="text-success fw-semibold">$9,800</td>
                                </tr>
                                <tr>
                                    <td>🥉</td>
                                    <td>Food Fest Dhaka</td>
                                    <td>280</td>
                                    <td class="text-success fw-semibold">$7,200</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Art Exhibition BD</td>
                                    <td>140</td>
                                    <td class="text-success fw-semibold">$5,600</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Sports Day 2026</td>
                                    <td>90</td>
                                    <td class="text-success fw-semibold">$4,100</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TOP ORGANISERS -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-people me-2"></i>Top Organisers This Month
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
                                    <td class="text-success fw-semibold">$19,600</td>
                                </tr>
                                <tr>
                                    <td>🥈</td>
                                    <td>Star Concerts</td>
                                    <td>5</td>
                                    <td class="text-success fw-semibold">$12,400</td>
                                </tr>
                                <tr>
                                    <td>🥉</td>
                                    <td>Mahinul Events</td>
                                    <td>4</td>
                                    <td class="text-success fw-semibold">$8,200</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>City Sports</td>
                                    <td>3</td>
                                    <td class="text-success fw-semibold">$5,400</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>BD Expo</td>
                                    <td>2</td>
                                    <td class="text-success fw-semibold">$2,600</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- USER BREAKDOWN -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-people me-2"></i>User Growth This Month
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>New This Month</th>
                                    <th>Total</th>
                                    <th>Active</th>
                                    <th>Suspended</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary">Attendee</span></td>
                                    <td>38</td>
                                    <td>180</td>
                                    <td>175</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning text-dark">Organiser</span></td>
                                    <td>6</td>
                                    <td>45</td>
                                    <td>40</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">Venue Manager</span></td>
                                    <td>4</td>
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
    </div>
</div>

<?php require_once __DIR__ . '/../../views/shared/footer.php'; ?>