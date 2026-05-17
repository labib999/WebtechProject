<?php
include("session_check.php");
include("../../config/db.php");

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

if ($search != "") {
    $sql = "select events.id, events.title, events.description, events.venue_name_override, events.event_datetime, categories.name as category_name,
            (select min(price) from ticket_tiers where ticket_tiers.event_id = events.id) as min_price
            from events
            left join categories on events.category_id = categories.id
            where events.status = 'published' and events.title like ?
            order by events.event_datetime asc";

    $stmt = $conn->prepare($sql);
    $searchText = "%" . $search . "%";
    $stmt->bind_param("s", $searchText);
    $stmt->execute();
    $result = $stmt->get_result();
} 
else {
    $sql = "select events.id, events.title, events.description, events.venue_name_override, events.event_datetime, categories.name as category_name,
            (select min(price) from ticket_tiers where ticket_tiers.event_id = events.id) as min_price
            from events
            left join categories on events.category_id = categories.id
            where events.status = 'published'
            order by events.event_datetime asc";

    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Browse Events</title>
    <link rel="stylesheet" href="../../public/css/attendee.css">
</head>

<script>
function searchEvents(){
    var search = document.getElementById("eventSearch").value;
    var xhr = new XMLHttpRequest();

    xhr.open("GET", "../../api/search-events.php?search=" + search, true);

    xhr.onload = function(){
        if (xhr.status == 200) {
            var events = JSON.parse(xhr.responseText);
            var output = "";

            if (events.length > 0) {
                for (var i = 0; i < events.length; i++) {
                    output += '<div class="event-list-card">';
                    output += '<div class="event-card-image"></div>';
                    output += '<div class="event-card-body">';
                    output += '<span class="event-tag">' + events[i].category_name + '</span>';
                    output += '<h3>' + events[i].title + '</h3>';
                    output += '<p>' + events[i].description + '</p>';
                    output += '<div class="event-meta">';
                    output += '<span>' + events[i].event_datetime + '</span>';
                    output += '<span>' + events[i].venue_name_override + '</span>';
                    output += '</div>';
                    output += '<div class="event-price">From ৳' + events[i].min_price + '</div>';
                    output += '<div class="event-actions">';
                    output += '<a href="event-details.php?id=' + events[i].id + '" class="event-btn">View Details</a>';
                    output += '</div>';
                    output += '</div>';
                    output += '</div>';
                }
            } else {
                output = '<div class="content-card"><h2>No events found</h2><p>Try another event name.</p></div>';
            }
            document.getElementById("eventsBox").innerHTML = output;
        }
    };

    xhr.send();
}
</script>

<body>
    <?php include("sidebar.php"); ?>
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <h2>Browse Events</h2>
                <p>Search and find your favorite upcoming events</p>
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

        <!-- Search -->
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

        <!-- Events -->
        <div class="events-page-grid" id="eventsBox">
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($event = $result->fetch_assoc()) { ?>
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
                                <a href="checkout.php?id=<?php echo $event["id"]; ?>" class="event-btn outline">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="content-card">
                    <h2>No events found</h2>
                    <p>Try searching with another event name.</p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>