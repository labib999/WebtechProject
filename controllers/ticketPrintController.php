<?php
include("../../config/db.php");
include("../../models/BookingModel.php");

if (!isset($_GET["code"])) {
    header("Location: my-tickets.php");
    exit();
}

$ticket_code = $_GET["code"];
$attendee_id = $_SESSION["user_id"];
$result = getBookingByTicketCode($conn, $ticket_code, $attendee_id);

if ($result->num_rows != 1) {
    header("Location: my-tickets.php");
    exit();
}

$ticket = $result->fetch_assoc();
?>