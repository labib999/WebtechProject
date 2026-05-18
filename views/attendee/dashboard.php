<?php
include("session_check.php");
include("../../controllers/dashboardController.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h2>Attendee Dashboard</h2>
            <p>Manage your events and tickets easily</p>
        </div>
        <div class="topbar-right">
            <div class="user-profile">
                <img src="../../public/uploads/user.png" alt="User">
                <div>
                    <h4><?php echo $_SESSION["name"]; ?></h4>
                    <span><?php echo $_SESSION["role"]; ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome to attendee panel</p>
        </div>
        <a href="events.php" class="primary-btn">Browse Events</a>
    </div>
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Tickets</h3>
            <h2><?php echo $totalTickets; ?></h2>
            <p>Tickets purchased</p>
        </div>
        <div class="stat-card">
            <h3>Upcoming Events</h3>
            <h2><?php echo $upcomingEvents; ?></h2>
            <p>Events waiting for you</p>
        </div>
        <div class="stat-card">
            <h3>Events Attended</h3>
            <h2><?php echo $eventsAttended; ?></h2>
            <p>Checked-in events</p>
        </div>
        <div class="stat-card">
            <h3>Profile Status</h3>
            <h2><?php echo $profileStatus; ?>%</h2>
            <p>Profile completed</p>
        </div>
    </div>
    <div class="dashboard-row">
        <div class="content-card large-card">
            <div class="card-header">
                <h2>Recent Bookings</h2>
                <a href="my-tickets.php">View All</a>
            </div>
            <table class="data-table">
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Ticket</th>
                    <th>Status</th>
                </tr>
                <?php if ($bookingResult->num_rows > 0): ?>
                    <?php while ($booking = $bookingResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $booking["title"]; ?></td>
                            <td><?php echo date("d M Y", strtotime($booking["event_datetime"])); ?></td>
                            <td><?php echo $booking["tier_name"]; ?></td>
                            <td><span class="badge active"><?php echo $booking["status"]; ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No recent bookings found</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="content-card small-card">
            <h2>Quick Actions</h2>
            <a href="events.php" class="quick-btn">Browse Events</a>
            <a href="my-tickets.php" class="quick-btn">My Tickets</a>
            <a href="profile.php" class="quick-btn">Update Profile</a>
        </div>
    </div>
    <div class="content-card">
        <div class="card-header">
            <h2>Upcoming Events</h2>
            <a href="events.php">Explore More</a>
        </div>
        <div class="event-grid">
            <?php if ($eventResult->num_rows > 0): ?>
                <?php while ($event = $eventResult->fetch_assoc()): ?>
                    <div class="event-card">
                        <div class="event-image"></div>
                        <h3><?php echo $event["title"]; ?></h3>
                        <p><?php echo $event["venue_name_override"]; ?></p>
                        <span><?php echo date("d M Y", strtotime($event["event_datetime"])); ?></span>
                        <a href="event-details.php?id=<?php echo $event["id"]; ?>" class="event-btn">View Details</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No upcoming events found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>