<?php
include("session_check.php");
include("../../config/db.php");

$attendee_id = $_SESSION["user_id"];

$eventSql = "select bookings.id as booking_id, events.id as event_id, events.title
from bookings
join events on bookings.event_id = events.id
where bookings.attendee_id = ? and bookings.checked_in = 1
and bookings.id not in (select booking_id from event_reviews)";

$eventStmt = $conn->prepare($eventSql);
$eventStmt->bind_param("i", $attendee_id);
$eventStmt->execute();
$eventResult = $eventStmt->get_result();

$reviewSql = "select event_reviews.*, events.title
from event_reviews
join events on event_reviews.event_id = events.id
where event_reviews.attendee_id = ?
order by event_reviews.id desc";

$reviewStmt = $conn->prepare($reviewSql);
$reviewStmt->bind_param("i", $attendee_id);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Reviews</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h2>My Reviews</h2>
            <p>Write and view your event reviews</p>
        </div>
        <div class="topbar-right">
            <div class="search-box">
                <input type="text" placeholder="Search reviews...">
            </div>
            <div class="user-profile">
                <img src="../../public/uploads/user.png" alt="User">
                <div>
                    <h4><?php echo $_SESSION["name"]; ?></h4>
                    <span><?php echo $_SESSION["role"]; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card">
        <h2>Write Review</h2>

        <?php if (isset($_SESSION["success"])) { ?>
            <p class="success-msg"><?php echo $_SESSION["success"]; ?></p>
            <?php unset($_SESSION["success"]); ?>
        <?php } ?>

        <?php if (isset($_SESSION["error"])) { ?>
            <p class="error-msg"><?php echo $_SESSION["error"]; ?></p>
            <?php unset($_SESSION["error"]); ?>
        <?php } ?>

        <form method="post" action="../../controllers/reviewController.php">
            <div class="form-group">
                <label>Select Event</label>
                <select name="booking_event">
                    <?php if ($eventResult->num_rows > 0) { ?>
                        <?php while ($event = $eventResult->fetch_assoc()) { ?>
                            <option value="<?php echo $event["booking_id"] . "-" . $event["event_id"]; ?>">
                                <?php echo $event["title"]; ?>
                            </option>
                        <?php } ?>
                    <?php } else { ?>
                        <option value="">No attended event available</option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Rating</label>
                <select name="rating">
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Bad</option>
                </select>
            </div>

            <div class="form-group">
                <label>Review</label>
                <textarea name="review_text" placeholder="Write your review"></textarea>
            </div>

            <button type="submit" class="confirm-btn">Submit Review</button>
        </form>
    </div>

    <div class="content-card review-list-card">
        <div class="card-header">
            <h2>Previous Reviews</h2>
        </div>

        <?php if ($reviewResult->num_rows > 0) { ?>
            <?php while ($review = $reviewResult->fetch_assoc()) { ?>
                <div class="review-box">
                    <h4><?php echo $review["title"]; ?></h4>
                    <span><?php echo str_repeat("★", $review["rating"]); ?></span>
                    <p><?php echo $review["review_text"]; ?></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No reviews found.</p>
        <?php } ?>
    </div>
</div>
</body>
</html>