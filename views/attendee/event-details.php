<?php
include("session_check.php");
include("../../controllers/eventDetailsController.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Details</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h2>Event Details</h2>
            <p>View complete information about the event</p>
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
    <div class="event-banner">
        <div class="banner-overlay">
            <span class="event-category"><?php echo $event["category_name"]; ?></span>
            <h1><?php echo $event["title"]; ?></h1>
            <p><?php echo $event["description"]; ?></p>
        </div>
    </div>
    <div class="event-details-grid">
        <div class="event-main-info">
            <div class="content-card">
                <h2>About Event</h2>
                <p class="event-description"><?php echo $event["description"]; ?></p>
            </div>
            <div class="content-card">
                <div class="card-header">
                    <h2>Ticket Tiers</h2>
                </div>
                <table class="data-table">
                    <tr>
                        <th>Tier</th>
                        <th>Price</th>
                        <th>Seats</th>
                        <th>Action</th>
                    </tr>
                    <?php if ($tierResult->num_rows > 0): ?>
                        <?php while ($tier = $tierResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $tier["name"]; ?></td>
                                <td>৳<?php echo $tier["price"]; ?></td>
                                <td><?php echo $tier["total_seats"]; ?></td>
                                <td><a href="checkout.php?event_id=<?php echo $event["id"]; ?>&tier_id=<?php echo $tier["id"]; ?>" class="table-btn">Book</a></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No ticket tiers found</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="content-card">
                <div class="card-header">
                    <h2>Recent Reviews</h2>
                </div>
                <?php if ($reviewResult->num_rows > 0): ?>
                    <?php while ($review = $reviewResult->fetch_assoc()): ?>
                        <div class="review-box">
                            <h4><?php echo $review["attendee_name"]; ?></h4>
                            <span><?php echo str_repeat("★", $review["rating"]); ?></span>
                            <p><?php echo $review["review_text"]; ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No reviews found for this event.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="event-sidebar">
            <div class="content-card">
                <h2>Event Information</h2>
                <div class="info-item">
                    <strong>Date:</strong>
                    <p><?php echo date("d M Y", strtotime($event["event_datetime"])); ?></p>
                </div>
                <div class="info-item">
                    <strong>Time:</strong>
                    <p><?php echo date("h:i A", strtotime($event["event_datetime"])); ?> - <?php echo date("h:i A", strtotime($event["end_datetime"])); ?></p>
                </div>
                <div class="info-item">
                    <strong>Venue:</strong>
                    <p><?php echo $event["venue_name_override"]; ?></p>
                </div>
                <div class="info-item">
                    <strong>Organiser:</strong>
                    <p><?php echo $event["organiser_name"]; ?></p>
                </div>
            </div>
            <div class="content-card">
                <h2>Quick Actions</h2>
                <a href="events.php" class="quick-btn">Back to Events</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>