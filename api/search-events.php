<?php
include("../config/db.php");

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
} else {
    $sql = "select events.id, events.title, events.description, events.venue_name_override, events.event_datetime, categories.name as category_name,
            (select min(price) from ticket_tiers where ticket_tiers.event_id = events.id) as min_price
            from events
            left join categories on events.category_id = categories.id
            where events.status = 'published'
            order by events.event_datetime asc";

    $result = $conn->query($sql);
}

$events = [];

while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

header("Content-Type: application/json");
echo json_encode($events);
?>