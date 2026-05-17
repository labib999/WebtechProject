<?php $activePage = 'approvals'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="main-content">

        <div class="mb-4">
            <h1 class="page-title">Approvals</h1>
            <p class="text-muted">Review and approve pending organiser and venue manager registrations.</p>
        </div>

        <!-- PENDING ORGANISERS -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-check me-2"></i>Pending Organisers</span>
                <span class="badge bg-warning text-dark"><?= count($pendingOrganisers) ?> Pending</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pendingOrganisers)): ?>
                    <div class="p-4 text-muted">No pending organiser registrations.</div>
                <?php else: ?>
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
                        <?php foreach ($pendingOrganisers as $org): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar"><?= strtoupper(substr($org['name'], 0, 2)) ?></div>
                                    <?= htmlspecialchars($org['name']) ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($org['email']) ?></td>
                            <td><?= htmlspecialchars($org['org_name']) ?></td>
                            <td><?= date('d M Y', strtotime($org['created_at'])) ?></td>
                            <td>
                                <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=approve_organiser" style="display:inline;">
                                    <input type="hidden" name="profile_id" value="<?= $org['profile_id'] ?>">
                                    <button class="btn btn-sm btn-success me-1">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>
                                <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=reject_organiser" style="display:inline;">
                                    <input type="hidden" name="profile_id" value="<?= $org['profile_id'] ?>">
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- PENDING VENUE MANAGERS -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-building-check me-2"></i>Pending Venue Managers</span>
                <span class="badge bg-warning text-dark"><?= count($pendingVenues) ?> Pending</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($pendingVenues)): ?>
                    <div class="p-4 text-muted">No pending venue manager registrations.</div>
                <?php else: ?>
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
                        <?php foreach ($pendingVenues as $venue): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar"><?= strtoupper(substr($venue['name'], 0, 2)) ?></div>
                                    <?= htmlspecialchars($venue['name']) ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($venue['email']) ?></td>
                            <td><?= htmlspecialchars($venue['phone']) ?></td>
                            <td><?= date('d M Y', strtotime($venue['created_at'])) ?></td>
                            <td>
                                <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=approve_venue" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $venue['id'] ?>">
                                    <button class="btn btn-sm btn-success me-1">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>
                                <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=reject_venue" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $venue['id'] ?>">
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- APPROVED ORGANISERS -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>Approved Organisers</span>
                <span class="badge bg-success"><?= count($approvedOrganisers) ?> Total</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($approvedOrganisers)): ?>
                    <div class="p-4 text-muted">No approved organisers yet.</div>
                <?php else: ?>
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
                        <?php foreach ($approvedOrganisers as $org): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar"><?= strtoupper(substr($org['name'], 0, 2)) ?></div>
                                    <?= htmlspecialchars($org['name']) ?>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($org['email']) ?></td>
                            <td><?= htmlspecialchars($org['org_name']) ?></td>
                            <td>
                                <?php if ($org['status'] === 'approved'): ?>
                                    <span class="badge-active px-2 py-1 rounded">Active</span>
                                <?php else: ?>
                                    <span class="badge-suspended px-2 py-1 rounded">Suspended</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($org['status'] === 'approved'): ?>
                                    <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=suspend_organiser">
                                        <input type="hidden" name="profile_id" value="<?= $org['profile_id'] ?>">
                                        <button class="btn btn-sm btn-warning">
                                            <i class="bi bi-pause-circle"></i> Suspend
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=reactivate_organiser">
                                        <input type="hidden" name="profile_id" value="<?= $org['profile_id'] ?>">
                                        <button class="btn btn-sm btn-success">
                                            <i class="bi bi-play-circle"></i> Reactivate
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../views/shared/footer.php'; ?>