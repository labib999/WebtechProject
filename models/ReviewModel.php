<?php
function getReviewableEvents($conn, $attendee_id){
    $sql = "select bookings.id as booking_id, events.id as event_id, events.title
            from bookings
            join events on bookings.event_id = events.id
            where bookings.attendee_id = ? and bookings.checked_in = 1
            and bookings.id not in (select booking_id from event_reviews)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendee_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getMyReviews($conn, $attendee_id){
    $sql = "select event_reviews.*, events.title
            from event_reviews
            join events on event_reviews.event_id = events.id
            where event_reviews.attendee_id = ?
            order by event_reviews.id desc";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendee_id);
    $stmt->execute();
    return $stmt->get_result();
}

function addReview($conn, $event_id, $booking_id, $attendee_id, $rating, $review_text){
    $sql = "insert into event_reviews (event_id, booking_id, attendee_id, rating, review_text) values (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiis", $event_id, $booking_id, $attendee_id, $rating, $review_text);
    return $stmt->execute();
}
?>