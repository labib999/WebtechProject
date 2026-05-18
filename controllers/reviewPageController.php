<?php
include("../../config/db.php");
include("../../models/ReviewModel.php");

$attendee_id = $_SESSION["user_id"];
$eventResult = getReviewableEvents($conn, $attendee_id);
$reviewResult = getMyReviews($conn, $attendee_id);
?>