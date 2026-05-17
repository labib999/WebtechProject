<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "attendee") {
    header("Location: ../views/attendee/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendee_id = $_SESSION["user_id"];
    $booking_event = $_POST["booking_event"];
    $rating = $_POST["rating"];
    $review_text = trim($_POST["review_text"]);

    if ($booking_event == "" || $review_text == "") {
        $_SESSION["error"] = "Please select event and write review";
        header("Location: ../views/attendee/reviews.php");
        exit();
    }

    $parts = explode("-", $booking_event);
    $booking_id = $parts[0];
    $event_id = $parts[1];

    $sql = "insert into event_reviews (event_id, booking_id, attendee_id, rating, review_text) values (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiis", $event_id, $booking_id, $attendee_id, $rating, $review_text);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Review submitted successfully";
        header("Location: ../views/attendee/reviews.php");
        exit();
    } else {
        $_SESSION["error"] = "Review submit failed";
        header("Location: ../views/attendee/reviews.php");
        exit();
    }
}
?>