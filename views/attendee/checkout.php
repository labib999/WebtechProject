<?php
include("session_check.php");
include("../../config/db.php");

if (!isset($_GET["event_id"]) || !isset($_GET["tier_id"])) {
    header("Location: events.php");
    exit();
}

$event_id = $_GET["event_id"];
$tier_id = $_GET["tier_id"];

$sql = "select events.title, events.event_datetime, events.venue_name_override, ticket_tiers.id as tier_id, ticket_tiers.name as tier_name, ticket_tiers.price
from events
join ticket_tiers on events.id = ticket_tiers.event_id
where events.id = ? and ticket_tiers.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $event_id, $tier_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: events.php");
    exit();
}

$data = $result->fetch_assoc();
$service_charge = 50;
$total = $data["price"] + $service_charge;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h2>Checkout</h2>
            <p>Confirm your ticket booking</p>
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

    <div class="checkout-grid">
        <div class="content-card">
            <h2>Booking Information</h2>
            <form method="post" action="../../controllers/bookingController.php">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <input type="hidden" name="tier_id" value="<?php echo $tier_id; ?>">
                <input type="hidden" name="ticket_price" value="<?php echo $data["price"]; ?>">
                <input type="hidden" name="service_charge" value="<?php echo $service_charge; ?>">

                <div class="form-group">
                    <label>Event</label>
                    <input type="text" value="<?php echo $data["title"]; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Ticket Tier</label>
                    <input type="text" value="<?php echo $data["tier_name"]; ?> - ৳<?php echo $data["price"]; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" value="1" min="1">
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method">
                        <option>bKash</option>
                        <option>Nagad</option>
                        <option>Rocket</option>
                        <option>Card Payment</option>
                    </select>
                </div>

                <button type="submit" class="confirm-btn">Confirm Booking</button>
            </form>
        </div>

        <div class="content-card order-summary">
            <h2>Order Summary</h2>
            <div class="summary-item">
                <span>Ticket Price</span>
                <strong>৳<?php echo $data["price"]; ?></strong>
            </div>
            <div class="summary-item">
                <span>Quantity</span>
                <strong>1</strong>
            </div>
            <div class="summary-item">
                <span>Service Charge</span>
                <strong>৳<?php echo $service_charge; ?></strong>
            </div>
            <div class="summary-total">
                <span>Total</span>
                <strong>৳<?php echo $total; ?></strong>
            </div>
        </div>
    </div>
</div>
</body>
</html>