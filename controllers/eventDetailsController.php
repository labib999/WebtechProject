<?php
include("../../config/db.php");
include("../../models/EventModel.php");

if (!isset($_GET["id"])) {
    header("Location: events.php");
    exit();
}

$event_id = $_GET["id"];
$result = getEventDetails($conn, $event_id);

if ($result->num_rows != 1) {
    header("Location: events.php");
    exit();
}

$event = $result->fetch_assoc();
$tierResult = getTicketTiers($conn, $event_id);
$reviewResult = getEventReviews($conn, $event_id);
?>