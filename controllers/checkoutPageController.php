<?php
include("../../config/db.php");
include("../../models/EventModel.php");

if (!isset($_GET["event_id"]) || !isset($_GET["tier_id"])) {
    header("Location: events.php");
    exit();
}

$event_id = $_GET["event_id"];
$tier_id = $_GET["tier_id"];
$result = getCheckoutEvent($conn, $event_id, $tier_id);

if ($result->num_rows != 1) {
    header("Location: events.php");
    exit();
}

$data = $result->fetch_assoc();
$service_charge = 50;
$total = $data["price"] + $service_charge;
?>