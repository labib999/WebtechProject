<?php include("session_check.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <title>My Tickets</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>

<body>
    <?php include("sidebar.php"); ?>
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <h2>My Tickets</h2>
                <p>View your booked tickets</p>
            </div>
            <div class="topbar-right">
                <div class="search-box">
                    <input type="text" placeholder="Search tickets...">
                </div>
                <div class="user-profile">
                    <img src="../../public/uploads/user.png" alt="User">
                    <div>
                        <h4><?php echo $_SESSION["name"]; ?></h4>
                        <span><?php echo $_SESSION["role"]; ?></span>
                        <span>Attendee</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Summary -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Active Tickets</h3>
                <h2>2</h2>
                <p>Upcoming event tickets</p>
            </div>
            <div class="stat-card">
                <h3>Past Tickets</h3>
                <h2>1</h2>
                <p>Completed events</p>
            </div>
            <div class="stat-card">
                <h3>Total Tickets</h3>
                <h2>3</h2>
                <p>All bookings</p>
            </div>
            <div class="stat-card">
                <h3>Status</h3>
                <h2>Active</h2>
                <p>Your account is active</p>
            </div>
        </div>

        <!-- Ticket List -->
        <div class="content-card">
            <div class="card-header">
                <h2>Ticket List</h2>
                <a href="events.php">Book New Ticket</a>
            </div>

            <div class="ticket-card">
                <div class="ticket-left">
                    <span class="ticket-label">ACTIVE</span>
                    <h3>AI Conference 2026</h3>
                    <p>Dhaka Convention Center</p>
                    <p>28 May 2026 | 10:00 AM</p>
                </div>
                <div class="ticket-middle">
                    <p><strong>Ticket Code:</strong> AI2026-4589</p>
                    <p><strong>Tier:</strong> General</p>
                    <p><strong>Quantity:</strong> 1</p>
                    <p><strong>Total Paid:</strong> ৳550</p>
                </div>
                <div class="ticket-actions">
                    <a href="ticket-print.php" class="ticket-btn">View Ticket</a>
                    <a href="ticket-print.php" class="ticket-btn outline">Print</a>
                </div>
            </div>

            <div class="ticket-card">
                <div class="ticket-left">
                    <span class="ticket-label">ACTIVE</span>
                    <h3>Music Night Dhaka</h3>
                    <p>Gulshan Club</p>
                    <p>10 June 2026 | 6:00 PM</p>
                </div>
                <div class="ticket-middle">
                    <p><strong>Ticket Code:</strong> MUS2026-9021</p>
                    <p><strong>Tier:</strong> VIP</p>
                    <p><strong>Quantity:</strong> 2</p>
                    <p><strong>Total Paid:</strong> ৳3000</p>
                </div>
                <div class="ticket-actions">
                    <a href="ticket-print.php" class="ticket-btn">View Ticket</a>
                    <a href="ticket-print.php" class="ticket-btn outline">Print</a>
                </div>
            </div>

            <div class="ticket-card past-ticket">
                <div class="ticket-left">
                    <span class="ticket-label completed">COMPLETED</span>
                    <h3>Business Expo</h3>
                    <p>International Convention City</p>
                    <p>10 April 2026 | 9:00 AM</p>
                </div>
                <div class="ticket-middle">
                    <p><strong>Ticket Code:</strong> BUS2026-7712</p>
                    <p><strong>Tier:</strong> Early Bird</p>
                    <p><strong>Quantity:</strong> 1</p>
                    <p><strong>Total Paid:</strong> ৳350</p>
                </div>
                <div class="ticket-actions">
                    <a href="ticket-print.php" class="ticket-btn outline">View Ticket</a>
                    <a href="ticket-print.php" class="ticket-btn outline">Print</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>