<?php $activePage = 'venue_report'; ?>
<?php require_once '../../config/db.php'; ?>
<?php require_once '../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once 'navbar.php'; ?>

    <div class="main-content">

        <!-- PAGE TITLE -->
        <div class="mb-4">
            <h1 class="page-title">Venue Report</h1>
            <p class="text-muted">Platform-wide venue utilisation and booking demand overview.</p>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#EFF6FF; color:#3B82F6;">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="stat-label">Total Active Venues</div>
                    <div class="stat-number">18</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">Across 4 cities</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#F0FDF4; color:#10B981;">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-label">Avg Booking Lead Time</div>
                    <div class="stat-number">14 days</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">Before event date</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FFF7ED; color:#F59E0B;">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="stat-label">Highest Demand City</div>
                    <div class="stat-number">Dhaka</div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">65% of all bookings</div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <!-- MOST BOOKED VENUES -->
            <div class="col-md-7">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-trophy me-2"></i>Most Booked Venues</span>
                        <span class="badge bg-secondary">Top 5</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Venue</th>
                                    <th>City</th>
                                    <th>Bookings</th>
                                    <th>Utilisation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>🥇</td>
                                    <td>
                                        <div class="fw-semibold">Bashundhara Convention</div>
                                        <div class="text-muted" style="font-size:0.8rem;">Capacity: 2,000</div>
                                    </td>
                                    <td>Dhaka</td>
                                    <td>18</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-primary" style="width:82%"></div>
                                            </div>
                                            82%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>🥈</td>
                                    <td>
                                        <div class="fw-semibold">BICC</div>
                                        <div class="text-muted" style="font-size:0.8rem;">Capacity: 5,000</div>
                                    </td>
                                    <td>Dhaka</td>
                                    <td>14</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-success" style="width:68%"></div>
                                            </div>
                                            68%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>🥉</td>
                                    <td>
                                        <div class="fw-semibold">Port City Convention</div>
                                        <div class="text-muted" style="font-size:0.8rem;">Capacity: 1,500</div>
                                    </td>
                                    <td>Chittagong</td>
                                    <td>9</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-warning" style="width:54%"></div>
                                            </div>
                                            54%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <div class="fw-semibold">National Museum Hall</div>
                                        <div class="text-muted" style="font-size:0.8rem;">Capacity: 800</div>
                                    </td>
                                    <td>Dhaka</td>
                                    <td>7</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-danger" style="width:42%"></div>
                                            </div>
                                            42%
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>
                                        <div class="fw-semibold">Sylhet Convention</div>
                                        <div class="text-muted" style="font-size:0.8rem;">Capacity: 1,000</div>
                                    </td>
                                    <td>Sylhet</td>
                                    <td>4</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-secondary" style="width:28%"></div>
                                            </div>
                                            28%
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CITIES WITH HIGHEST DEMAND -->
            <div class="col-md-5">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-geo-alt me-2"></i>Cities by Venue Demand
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>City</th>
                                    <th>Venues</th>
                                    <th>Bookings</th>
                                    <th>Avg Lead</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>📍 Dhaka</td>
                                    <td>10</td>
                                    <td>42</td>
                                    <td>12 days</td>
                                </tr>
                                <tr>
                                    <td>📍 Chittagong</td>
                                    <td>4</td>
                                    <td>14</td>
                                    <td>16 days</td>
                                </tr>
                                <tr>
                                    <td>📍 Sylhet</td>
                                    <td>2</td>
                                    <td>6</td>
                                    <td>18 days</td>
                                </tr>
                                <tr>
                                    <td>📍 Rajshahi</td>
                                    <td>2</td>
                                    <td>4</td>
                                    <td>20 days</td>
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