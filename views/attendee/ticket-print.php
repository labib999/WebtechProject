<?php
include("session_check.php");
include("../../config/db.php");

if (!isset($_GET["code"])) {
    header("Location: my-tickets.php");
    exit();
}

$ticket_code = $_GET["code"];
$attendee_id = $_SESSION["user_id"];

$sql = "select bookings.*, events.title, events.event_datetime, events.venue_name_override, ticket_tiers.name as tier_name
from bookings
join events on bookings.event_id = events.id
join ticket_tiers on bookings.tier_id = ticket_tiers.id
where bookings.ticket_code = ? and bookings.attendee_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $ticket_code, $attendee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: my-tickets.php");
    exit();
}

$ticket = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Ticket</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h2>Print Ticket</h2>
            <p>View your ticket information</p>
        </div>
        <div class="topbar-right">
            <!-- <div class="search-box">
                <input type="text" placeholder="Search tickets...">
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

    <div class="print-ticket-card">
        <div class="ticket-header">
            <h1>Event Platform</h1>
            <p>Official Event Ticket</p>
        </div>

        <div class="ticket-code-print">
            <span>Ticket Code</span>
            <h2><?php echo $ticket["ticket_code"]; ?></h2>
        </div>

        <div class="ticket-info-print">
            <div class="detail-row">
                <span>Event</span>
                <strong><?php echo $ticket["title"]; ?></strong>
            </div>
            <div class="detail-row">
                <span>Date</span>
                <strong><?php echo date("d M Y", strtotime($ticket["event_datetime"])); ?></strong>
            </div>
            <div class="detail-row">
                <span>Time</span>
                <strong><?php echo date("h:i A", strtotime($ticket["event_datetime"])); ?></strong>
            </div>
            <div class="detail-row">
                <span>Venue</span>
                <strong><?php echo $ticket["venue_name_override"]; ?></strong>
            </div>
            <div class="detail-row">
                <span>Ticket Tier</span>
                <strong><?php echo $ticket["tier_name"]; ?></strong>
            </div>
            <div class="detail-row">
                <span>Quantity</span>
                <strong><?php echo $ticket["quantity"]; ?></strong>
            </div>
            <div class="detail-row">
                <span>Total Paid</span>
                <strong>৳<?php echo $ticket["total_price"]; ?></strong>
            </div>
        </div>

        <p id="printMsg" class="print-msg"></p>

        <div class="print-actions">
            <button onclick="showPrintMessage()" class="confirm-action-btn">Print Ticket</button>
            <a href="my-tickets.php" class="outline-btn">Back to My Tickets</a>
        </div>
    </div>
</div>

<script>
function showPrintMessage(){
    document.getElementById("printMsg").innerHTML = "Printed successfully";
}
</script>
</body>
</html>