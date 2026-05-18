<?php
include("session_check.php");
include("../../controllers/reviewPageController.php");
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
        <?php if (isset($_SESSION["success"])): ?>
            <p class="success-msg"><?php echo $_SESSION["success"]; ?></p>
            <?php unset($_SESSION["success"]); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION["error"])): ?>
            <p class="error-msg"><?php echo $_SESSION["error"]; ?></p>
            <?php unset($_SESSION["error"]); ?>
        <?php endif; ?>
        <form method="post" action="../../controllers/reviewController.php">
            <div class="form-group">
                <label>Select Event</label>
                <select name="booking_event">
                    <?php if ($eventResult->num_rows > 0): ?>
                        <?php while ($event = $eventResult->fetch_assoc()): ?>
                            <option value="<?php echo $event["booking_id"] . "-" . $event["event_id"]; ?>">
                                <?php echo $event["title"]; ?>
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No attended event available</option>
                    <?php endif; ?>
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
        <?php if ($reviewResult->num_rows > 0): ?>
            <?php while ($review = $reviewResult->fetch_assoc()): ?>
                <div class="review-box">
                    <h4><?php echo $review["title"]; ?></h4>
                    <span><?php echo str_repeat("★", $review["rating"]); ?></span>
                    <p><?php echo $review["review_text"]; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>