<?php
include("session_check.php");
include("../../controllers/myTicketsController.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Tickets</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h2>My Tickets</h2>
            <p>View your booked tickets</p>
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
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Tickets</h3>
            <h2><?php echo $totalTickets; ?></h2>
            <p>All bookings</p>
        </div>
        <div class="stat-card">
            <h3>Account</h3>
            <h2>Active</h2>
            <p>Your account is active</p>
        </div>
        <div class="stat-card">
            <h3>Role</h3>
            <h2>Attendee</h2>
            <p>Logged in user</p>
        </div>
        <div class="stat-card">
            <h3>Booking Status</h3>
            <h2>Ready</h2>
            <p>Tickets available</p>
        </div>
    </div>
    <div class="content-card">
        <div class="card-header">
            <h2>Ticket List</h2>
            <a href="events.php">Book New Ticket</a>
        </div>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($ticket = $result->fetch_assoc()): ?>
                <div class="ticket-card">
                    <div class="ticket-left">
                        <span class="ticket-label"><?php echo $ticket["status"]; ?></span>
                        <h3><?php echo $ticket["title"]; ?></h3>
                        <p><?php echo $ticket["venue_name_override"]; ?></p>
                        <p><?php echo date("d M Y", strtotime($ticket["event_datetime"])); ?> | <?php echo date("h:i A", strtotime($ticket["event_datetime"])); ?></p>
                    </div>
                    <div class="ticket-middle">
                        <p><strong>Ticket Code:</strong> <?php echo $ticket["ticket_code"]; ?></p>
                        <p><strong>Tier:</strong> <?php echo $ticket["tier_name"]; ?></p>
                        <p><strong>Quantity:</strong> <?php echo $ticket["quantity"]; ?></p>
                        <p><strong>Total Paid:</strong> ৳<?php echo $ticket["total_price"]; ?></p>
                    </div>
                    <div class="ticket-actions">
                        <a href="ticket-print.php?code=<?php echo $ticket["ticket_code"]; ?>" class="ticket-btn">View Ticket</a>
                        <a href="ticket-print.php?code=<?php echo $ticket["ticket_code"]; ?>" class="ticket-btn outline">Print</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No tickets found. Please book an event first.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>