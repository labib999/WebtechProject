<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <div class="sidebar-logo">
        <img src="../../public/uploads/Logo.png" alt="Logo">
        <h2>Event Platform</h2>
        <p>Attendee Panel</p>
    </div>

    <div class="menu-section">
        <span class="menu-title">MAIN</span>
        <a href="dashboard.php" class="<?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
        <a href="events.php" class="<?php echo ($currentPage == 'events.php') ? 'active' : ''; ?>">Browse Events</a>
        <a href="my-tickets.php" class="<?php echo ($currentPage == 'my-tickets.php') ? 'active' : ''; ?>">My Tickets</a>
    </div>

    <div class="menu-section">
        <span class="menu-title">BOOKING</span>
        <a href="ticket-print.php" class="<?php echo ($currentPage == 'ticket-print.php') ? 'active' : ''; ?>">Print Ticket</a>
    </div>

    <div class="menu-section">
        <span class="menu-title">ACCOUNT</span>
        <a href="profile.php" class="<?php echo ($currentPage == 'profile.php') ? 'active' : ''; ?>">My Profile</a>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>
</div>