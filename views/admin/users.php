<?php $activePage = 'users'; ?>
<?php require_once __DIR__ . '/../../views/shared/header.php'; ?>
<link rel="stylesheet" href="/WebtechProject/public/css/admin.css">

<div class="wrapper">

    <?php require_once __DIR__ . '/navbar.php'; ?>

    <div class="main-content">

        <div class="mb-4">
            <h1 class="page-title">Users</h1>
            <p class="text-muted">View and manage all registered users on the platform.</p>
        </div>

        <!-- SEARCH BAR -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="http://localhost/WebtechProject/index.php">
                    <input type="hidden" name="page" value="admin">
                    <input type="hidden" name="action" value="users">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="keyword" class="form-control" 
                                placeholder="Search by name or email..."
                                value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="http://localhost/WebtechProject/index.php?page=admin&action=users" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-x me-1"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>All Users</span>
                <span class="badge bg-secondary"><?= count($users) ?> Total</span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($users)): ?>
                    <div class="p-4 text-muted">No users found.</div>
                <?php else: ?>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <?php if ($user['role'] === 'admin') continue; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar"><?= strtoupper(substr($user['name'], 0, 2)) ?></div>
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($user['name']) ?></div>
                                        <div class="text-muted" style="font-size:0.8rem;"><?= htmlspecialchars($user['phone'] ?? '') ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <?php
                                $roleColor = match($user['role']) {
                                    'attendee'        => 'bg-primary',
                                    'organiser'       => 'bg-warning text-dark',
                                    'venue_manager'   => 'bg-success',
                                    default           => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $roleColor ?>"><?= ucfirst(str_replace('_', ' ', $user['role'])) ?></span>
                            </td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge-active px-2 py-1 rounded">Active</span>
                                <?php else: ?>
                                    <span class="badge-suspended px-2 py-1 rounded">Suspended</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=suspend_user">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <button class="btn btn-sm btn-warning">
                                            <i class="bi bi-pause-circle"></i> Suspend
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="http://localhost/WebtechProject/index.php?page=admin&action=reactivate_user">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
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