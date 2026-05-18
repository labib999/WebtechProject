<?php
include("../../config/db.php");
include("../../models/UserModel.php");
include("../../models/EventModel.php");
include("../../models/BookingModel.php");

$attendee_id = $_SESSION["user_id"];

$totalData = countTotalTickets($conn, $attendee_id)->fetch_assoc();
$totalTickets = $totalData["total"];

$upcomingData = countUpcomingEvents($conn, $attendee_id)->fetch_assoc();
$upcomingEvents = $upcomingData["total"];

$attendedData = countEventsAttended($conn, $attendee_id)->fetch_assoc();
$eventsAttended = $attendedData["total"];

$userData = getUserById($conn, $attendee_id)->fetch_assoc();
$profileStatus = 0;

if ($userData["name"] != "") {
    $profileStatus += 35;
}

if ($userData["email"] != "") {
    $profileStatus += 35;
}

if ($userData["phone"] != "") {
    $profileStatus += 30;
}

$bookingResult = getRecentBookings($conn, $attendee_id);
$eventResult = getUpcomingEvents($conn);
?>