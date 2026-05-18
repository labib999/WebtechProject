<?php
session_start();
include("../config/db.php");
include("../models/BookingModel.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "attendee") {
    header("Location: ../views/attendee/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendee_id = $_SESSION["user_id"];
    $event_id = $_POST["event_id"];
    $tier_id = $_POST["tier_id"];
    $quantity = $_POST["quantity"];
    $ticket_price = $_POST["ticket_price"];
    $service_charge = $_POST["service_charge"];
    $total_price = ($ticket_price * $quantity) + $service_charge;
    $ticket_code = "TIK-" . time() . "-" . $attendee_id;
    $status = "active";

    if (createBooking($conn, $attendee_id, $event_id, $tier_id, $quantity, $total_price, $ticket_code, $status)) {
        $_SESSION["ticket_code"] = $ticket_code;
        header("Location: ../views/attendee/confirmation.php");
        exit();
    } else {
        header("Location: ../views/attendee/events.php");
        exit();
    }
}
?>