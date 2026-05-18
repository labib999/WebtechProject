<?php
include("../../config/db.php");
include("../../models/BookingModel.php");

$attendee_id = $_SESSION["user_id"];
$result = getMyTickets($conn, $attendee_id);

$totalData = countTotalTickets($conn, $attendee_id)->fetch_assoc();
$totalTickets = $totalData["total"];
?>