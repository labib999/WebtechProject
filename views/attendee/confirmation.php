<?php
include("session_check.php");
include("../../config/db.php");

if (!isset($_SESSION["ticket_code"])) {
    header("Location: my-tickets.php");
    exit();
}

$ticket_code = $_SESSION["ticket_code"];

$sql = "select bookings.*, events.title, events.event_datetime, events.venue_name_override, ticket_tiers.name as tier_name
from bookings
join events on bookings.event_id = events.id
join ticket_tiers on bookings.tier_id = ticket_tiers.id
where bookings.ticket_code = ? and bookings.attendee_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $ticket_code, $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: my-tickets.php");
    exit();
}

$booking = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h2>Booking Confirmation</h2>
            <p>Your ticket has been booked successfully</p>
        </div>
        <div class="topbar-right">
            <!-- <div class="search-box">
                <input type="text" placeholder="Search events...">
            </div> -->
            <div class="user-profile">
                <img src="../../public/uploads/user.png" alt="User">
                <div>
                    <h4><?php echo $_SESSION["name"]; ?></h4>
                    <span><?php echo $_SESSION["role"]; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="confirmation-card">
        <div class="success-circle">✓</div>
        <h1>Booking Successful!</h1>
        <p class="confirm-text">Your ticket has been confirmed. Please save your ticket code.</p>

        <div class="ticket-code-box">
            <span>Ticket Code</span>
            <h2><?php echo $booking["ticket_code"]; ?></h2>
        </div>

        <div class="confirmation-details">
            <div class="detail-row">
                <span>Event Name</span>
                <strong><?php echo $booking["title"]; ?></strong>
            </div>
            <div class="detail-row">
                <span>Date</span>
                <strong><?php echo date("d M Y", strtotime($booking["event_datetime"])); ?></strong>
            </div>
            <div class="detail-row">
                <span>Venue</span>
                <strong><?php echo $booking["venue_name_override"]; ?></strong>
            </div>
            <div class="detail-row">
                <span>Ticket Tier</span>
                <strong><?php echo $booking["tier_name"]; ?></strong>
            </div>
            <div class="detail-row">
                <span>Quantity</span>
                <strong><?php echo $booking["quantity"]; ?></strong>
            </div>
            <div class="detail-row total-row">
                <span>Total Paid</span>
                <strong>৳<?php echo $booking["total_price"]; ?></strong>
            </div>
        </div>

        <div class="confirmation-actions">
            <a href="my-tickets.php" class="confirm-action-btn">View My Tickets</a>
            <a href="ticket-print.php?code=<?php echo $booking["ticket_code"]; ?>" class="outline-btn">Print Ticket</a>
            <a href="events.php" class="outline-btn">Browse More Events</a>
        </div>
    </div>
</div>
</body>
</html>