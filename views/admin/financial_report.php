<?php $activePage = 'financial_report'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="main-content">

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
                    <div class="stat-number">$<?= number_format($summary['gross_sales'] ?? 0, 2) ?></div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">From <?= $summary['total_transactions'] ?? 0 ?> active bookings</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FDF4FF; color:#A855F7;">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="stat-label">Commission Earned</div>
                    <div class="stat-number">$<?= number_format(($summary['gross_sales'] ?? 0) * ($commission / 100), 2) ?></div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;"><?= $commission ?>% platform commission rate</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#EFF6FF; color:#3B82F6;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="stat-label">Total Transactions</div>
                    <div class="stat-number"><?= $summary['total_transactions'] ?? 0 ?></div>
                    <div class="text-muted mt-1" style="font-size:0.8rem;">Active bookings this month</div>
                </div>
            </div>
        </div>

        <!-- TABLES ROW -->
        <div class="row g-4">

            <!-- TOP 5 EVENTS -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-trophy me-2"></i>Top 5 Events by Revenue
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($topEvents)): ?>
                            <div class="p-4 text-muted">No booking data yet.</div>
                        <?php else: ?>
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
                                <?php $medals = ['🥇','🥈','🥉','4','5']; ?>
                                <?php foreach ($topEvents as $i => $event): ?>
                                <tr>
                                    <td><?= $medals[$i] ?></td>
                                    <td><?= htmlspecialchars($event['title']) ?></td>
                                    <td><?= htmlspecialchars($event['organiser_name']) ?></td>
                                    <td class="fw-semibold text-success">$<?= number_format($event['revenue'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
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
                        <?php if (empty($topOrganisers)): ?>
                            <div class="p-4 text-muted">No booking data yet.</div>
                        <?php else: ?>
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
                                <?php $medals = ['🥇','🥈','🥉','4','5']; ?>
                                <?php foreach ($topOrganisers as $i => $org): ?>
                                <tr>
                                    <td><?= $medals[$i] ?></td>
                                    <td><?= htmlspecialchars($org['organiser_name']) ?></td>
                                    <td><?= $org['event_count'] ?></td>
                                    <td class="fw-semibold text-success">$<?= number_format($org['revenue'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
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
                        <?php if (empty($byCategory)): ?>
                            <div class="p-4 text-muted">No booking data yet.</div>
                        <?php else: ?>
                        <?php $totalRevenue = array_sum(array_column($byCategory, 'revenue')); ?>
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
                                <?php foreach ($byCategory as $cat): ?>
                                <?php $pct = $totalRevenue > 0 ? round(($cat['revenue'] / $totalRevenue) * 100) : 0; ?>
                                <tr>
                                    <td><?= htmlspecialchars($cat['icon'] . ' ' . $cat['name']) ?></td>
                                    <td><?= $cat['event_count'] ?></td>
                                    <td><?= $cat['tickets_sold'] ?></td>
                                    <td class="fw-semibold text-success">$<?= number_format($cat['revenue'], 2) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:6px;">
                                                <div class="progress-bar bg-primary" style="width:<?= $pct ?>%"></div>
                                            </div>
                                            <?= $pct ?>%
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- PLATFORM SETTINGS -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-gear me-2"></i>Platform Commission Settings
                    </div>
                    <div class="card-body">
                        <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=update_commission">
                            <div class="row align-items-end g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Default Commission Rate (%)</label>
                                    <input type="number" name="commission_rate" class="form-control" 
                                        value="<?= $commission ?>" min="0" max="100">
                                    <div class="text-muted mt-1" style="font-size:0.8rem;">Applied to all ticket sales platform-wide</div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-save me-1"></i> Save Settings
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/shared/footer.php'; ?>