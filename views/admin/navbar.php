<!-- SIDEBAR -->
    <nav class="sidebar">
        <div class="sidebar-brand">🛡️ EMTS Admin</div>
        <div class="sidebar-nav">
            <a href="dashboard.php" class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
            <a href="approvals.php" class="<?= $activePage === 'approvals' ? 'active' : '' ?>">✅ Approvals</a>
            <a href="categories.php" class="<?= $activePage === 'categories' ? 'active' : '' ?>">🏷️ Categories</a>
            <a href="events.php" class="<?= $activePage === 'events' ? 'active' : '' ?>">🎪 Events</a>
            <a href="users.php" class="<?= $activePage === 'users' ? 'active' : '' ?>">👥 Users</a>
            <a href="financial_report.php" class="<?= $activePage === 'financial_report' ? 'active' : '' ?>">💰 Financial Report</a>
            <a href="complaints.php" class="<?= $activePage === 'complaints' ? 'active' : '' ?>">📢 Complaints</a>
            <a href="../../index.php?action=logout" class="btn btn-outline-danger">🚪 Logout</a>
        </div>
    </nav>