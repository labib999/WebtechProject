<?php include("session_check.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <title>Event Details</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>

<body>
    <?php include("sidebar.php"); ?>
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar"><?php
include("session_check.php");
include("../../config/db.php");

if (!isset($_GET["id"])) {
    header("Location: events.php");
    exit();
}

$event_id = $_GET["id"];

$sql = "select events.*, categories.name as category_name, users.name as organiser_name
from events
left join categories on events.category_id = categories.id
left join users on events.organiser_id = users.id
where events.id = ? and events.status = 'published'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: events.php");
    exit();
}

$event = $result->fetch_assoc();

$tierSql = "select * from ticket_tiers where event_id = ?";
$tierStmt = $conn->prepare($tierSql);
$tierStmt->bind_param("i", $event_id);
$tierStmt->execute();
$tierResult = $tierStmt->get_result();

$reviewSql = "select event_reviews.*, users.name as attendee_name
from event_reviews
left join users on event_reviews.attendee_id = users.id
where event_reviews.event_id = ?";
$reviewStmt = $conn->prepare($reviewSql);
$reviewStmt->bind_param("i", $event_id);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();
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
            <div class="search-box">
                <input type="text" placeholder="Search events...">
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
                    <a href="checkout.php?id=<?php echo $event["id"]; ?>">Book Ticket</a>
                </div>
                <table class="data-table">
                    <tr>
                        <th>Tier</th>
                        <th>Price</th>
                        <th>Seats</th>
                        <th>Action</th>
                    </tr>
                    <?php if ($tierResult->num_rows > 0) { ?>
                        <?php while ($tier = $tierResult->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $tier["name"]; ?></td>
                                <td>৳<?php echo $tier["price"]; ?></td>
                                <td><?php echo $tier["total_seats"]; ?></td>
                                <td><a href="checkout.php?event_id=<?php echo $event["id"]; ?>&tier_id=<?php echo $tier["id"]; ?>" class="table-btn">Book</a></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">No ticket tiers found</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <h2>Recent Reviews</h2>
                </div>
                <?php if ($reviewResult->num_rows > 0) { ?>
                    <?php while ($review = $reviewResult->fetch_assoc()) { ?>
                        <div class="review-box">
                            <h4><?php echo $review["attendee_name"]; ?></h4>
                            <span><?php echo str_repeat("★", $review["rating"]); ?></span>
                            <p><?php echo $review["review_text"]; ?></p>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No reviews found for this event.</p>
                <?php } ?>
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
                <a href="checkout.php?id=<?php echo $event["id"]; ?>" class="quick-btn">Book Ticket</a>
                <a href="events.php" class="quick-btn">Back to Events</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
            <div class="topbar-left">
                <h2>Event Details</h2>
                <p>View complete information about the event</p>
            </div>
            <div class="topbar-right">
                <div class="search-box">
                    <input type="text" placeholder="Search events...">
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

        <!-- Event Banner -->
        <div class="event-banner">
            <div class="banner-overlay">
                <span class="event-category">Technology</span>
                <h1>AI Conference 2026</h1>
                <p>Join the biggest AI conference in Bangladesh with top speakers, startup founders, and technology experts.</p>
            </div>
        </div>

        <!-- Event Details -->
        <div class="event-details-grid">
            <div class="event-main-info">
                <div class="content-card">
                    <h2>About Event</h2>
                    <p class="event-description">AI Conference 2026 brings together developers, entrepreneurs, students, and researchers to explore the future of Artificial Intelligence. The event includes keynote sessions, workshops, networking opportunities, startup showcases, and live demos.</p>
                </div>

                <!-- Ticket Section -->
                <div class="content-card">
                    <div class="card-header">
                        <h2>Ticket Tiers</h2>
                        <a href="checkout.php">Book Ticket</a>
                    </div>
                    <table class="data-table">
                        <tr>
                            <th>Tier</th>
                            <th>Price</th>
                            <th>Seats</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>General</td>
                            <td>৳500</td>
                            <td>120 Left</td>
                            <td><a href="checkout.php" class="table-btn">Book</a></td>
                        </tr>
                        <tr>
                            <td>VIP</td>
                            <td>৳1500</td>
                            <td>40 Left</td>
                            <td><a href="checkout.php" class="table-btn">Book</a></td>
                        </tr>
                        <tr>
                            <td>Early Bird</td>
                            <td>৳300</td>
                            <td>Sold Out</td>
                            <td><button class="disabled-btn">Unavailable</button></td>
                        </tr>
                    </table>
                </div>

                <!-- Reviews Display Only -->
                <div class="content-card">
                    <div class="card-header">
                        <h2>Recent Reviews</h2>
                    </div>
                    <div class="review-box">
                        <h4>Rahim Ahmed</h4>
                        <span>★★★★★</span>
                        <p>Excellent conference with great networking opportunities.</p>
                    </div>
                    <div class="review-box">
                        <h4>Nusrat Jahan</h4>
                        <span>★★★★☆</span>
                        <p>Very informative sessions and amazing speakers.</p>
                    </div>
                </div>
            </div>

            <div class="event-sidebar">
                <div class="content-card">
                    <h2>Event Information</h2>
                    <div class="info-item">
                        <strong>Date:</strong>
                        <p>28 May 2026</p>
                    </div>
                    <div class="info-item">
                        <strong>Time:</strong>
                        <p>10:00 AM - 6:00 PM</p>
                    </div>
                    <div class="info-item">
                        <strong>Venue:</strong>
                        <p>Dhaka Convention Center</p>
                    </div>
                    <div class="info-item">
                        <strong>Location:</strong>
                        <p>Dhaka, Bangladesh</p>
                    </div>
                    <div class="info-item">
                        <strong>Organiser:</strong>
                        <p>Tech Future Bangladesh</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="content-card">
                    <h2>Quick Actions</h2>
                    <a href="checkout.php" class="quick-btn">Book Ticket</a>
                    <a href="events.php" class="quick-btn">Back to Events</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>