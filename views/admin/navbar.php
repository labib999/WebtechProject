<nav class="sidebar">
    <div class="sidebar-brand">🛡️ EMTS Admin</div>
    <div class="sidebar-nav">
        <div class="sidebar-section-label">Main</div>
        <a href="/WebtechProject/index.php?page=admin&action=dashboard" class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="sidebar-section-label">Management</div>
        <a href="/WebtechProject/index.php?page=admin&action=approvals" class="<?= $activePage === 'approvals' ? 'active' : '' ?>">
            <i class="bi bi-person-check"></i> Approvals
        </a>
        <a href="/WebtechProject/index.php?page=admin&action=categories" class="<?= $activePage === 'categories' ? 'active' : '' ?>">
            <i class="bi bi-tag"></i> Categories
        </a>
        <a href="/WebtechProject/index.php?page=admin&action=events" class="<?= $activePage === 'events' ? 'active' : '' ?>">
            <i class="bi bi-calendar-event"></i> Events
        </a>
        <a href="/WebtechProject/index.php?page=admin&action=users" class="<?= $activePage === 'users' ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="/WebtechProject/index.php?page=admin&action=announcements" class="<?= $activePage === 'announcements' ? 'active' : '' ?>">
            <i class="bi bi-megaphone"></i> Announcements
        </a>

        <div class="sidebar-section-label">Finance & Reports</div>
        <a href="/WebtechProject/index.php?page=admin&action=financial_report" class="<?= $activePage === 'financial_report' ? 'active' : '' ?>">
            <i class="bi bi-bar-chart"></i> Financial Report
        </a>
        <a href="/WebtechProject/index.php?page=admin&action=analytics" class="<?= $activePage === 'analytics' ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i> Analytics
        </a>
        <a href="/WebtechProject/index.php?page=admin&action=venue_report" class="<?= $activePage === 'venue_report' ? 'active' : '' ?>">
            <i class="bi bi-building"></i> Venue Report
        </a>
        <a href="/WebtechProject/index.php?page=admin&action=monthly_report" class="<?= $activePage === 'monthly_report' ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-text"></i> Monthly Report
        </a>

        <div class="sidebar-section-label">Support</div>
        <a href="/WebtechProject/index.php?page=admin&action=complaints" class="<?= $activePage === 'complaints' ? 'active' : '' ?>">
            <i class="bi bi-flag"></i> Complaints
        </a>

        <div class="sidebar-footer">
            <a href="/WebtechProject/index.php?action=logout">
                <i class="bi bi-box-arrow-left"></i> Logout
            </a>
        </div>
    </div>
</nav>