<?php
include("session_check.php");
include("../../controllers/eventListController.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Browse Events</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h2>Browse Events</h2>
            <p>Search and find your favorite upcoming events</p>
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
    <div class="content-card filter-card">
        <h2>Find Events</h2>
        <form method="get" action="events.php">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Event Name</label>
                    <input type="text" id="eventSearch" name="search" value="<?php echo $search; ?>" placeholder="Search by event name" onkeyup="searchEvents()">
                </div>
            </div>
            <button type="submit" class="search-event-btn">Search Events</button>
            <a href="events.php" class="search-event-btn">Reset</a>
        </form>
    </div>
    <div class="events-page-grid" id="eventsBox">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($event = $result->fetch_assoc()): ?>
                <div class="event-list-card">
                    <div class="event-card-image"></div>
                    <div class="event-card-body">
                        <span class="event-tag"><?php echo $event["category_name"]; ?></span>
                        <h3><?php echo $event["title"]; ?></h3>
                        <p><?php echo $event["description"]; ?></p>
                        <div class="event-meta">
                            <span><?php echo date("d M Y", strtotime($event["event_datetime"])); ?></span>
                            <span><?php echo $event["venue_name_override"]; ?></span>
                        </div>
                        <div class="event-price">
                            From ৳<?php echo $event["min_price"]; ?>
                        </div>
                        <div class="event-actions">
                            <a href="event-details.php?id=<?php echo $event["id"]; ?>" class="event-btn">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="content-card">
                <h2>No events found</h2>
                <p>Try searching with another event name.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<script src="../../public/js/attendee.js"></script>
</body>
</html>