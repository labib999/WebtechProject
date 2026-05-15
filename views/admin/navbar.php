<nav class="sidebar">
    <div class="sidebar-brand">🛡️ EMTS Admin</div>
    <div class="sidebar-nav">
        <div class="sidebar-section-label">Main</div>
        <a href="dashboard.php" class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="sidebar-section-label">Management</div>
        <a href="approvals.php" class="<?= $activePage === 'approvals' ? 'active' : '' ?>">
            <i class="bi bi-person-check"></i> Approvals
        </a>
        <a href="categories.php" class="<?= $activePage === 'categories' ? 'active' : '' ?>">
            <i class="bi bi-tag"></i> Categories
        </a>
        <a href="events.php" class="<?= $activePage === 'events' ? 'active' : '' ?>">
            <i class="bi bi-calendar-event"></i> Events
        </a>
        <a href="users.php" class="<?= $activePage === 'users' ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="announcements.php" class="<?= $activePage === 'announcements' ? 'active' : '' ?>">
            <i class="bi bi-megaphone"></i> Announcements
        </a>

        <div class="sidebar-section-label">Finance & Reports</div>
        <a href="financial_report.php" class="<?= $activePage === 'financial_report' ? 'active' : '' ?>">
            <i class="bi bi-bar-chart"></i> Financial Report
        </a>
        <a href="analytics.php" class="<?= $activePage === 'analytics' ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i> Analytics
        </a>
        <a href="venue_report.php" class="<?= $activePage === 'venue_report' ? 'active' : '' ?>">
            <i class="bi bi-building"></i> Venue Report
        </a>
        <a href="monthly_report.php" class="<?= $activePage === 'monthly_report' ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-text"></i> Monthly Report
        </a>

        <div class="sidebar-section-label">Support</div>
        <a href="complaints.php" class="<?= $activePage === 'complaints' ? 'active' : '' ?>">
            <i class="bi bi-flag"></i> Complaints
        </a>

        <div class="sidebar-footer">
            <a href="../../index.php?action=logout">
                <i class="bi bi-box-arrow-left"></i> Logout
            </a>
        </div>
    </div>
</nav>