<?php
function createBooking($conn, $attendee_id, $event_id, $tier_id, $quantity, $total_price, $ticket_code, $status){
    $sql = "insert into bookings (attendee_id, event_id, tier_id, quantity, total_price, ticket_code, status) values (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiidss", $attendee_id, $event_id, $tier_id, $quantity, $total_price, $ticket_code, $status);
    return $stmt->execute();
}

function getBookingByTicketCode($conn, $ticket_code, $attendee_id){
    $sql = "select bookings.*, events.title, events.event_datetime, events.venue_name_override, ticket_tiers.name as tier_name
            from bookings
            join events on bookings.event_id = events.id
            join ticket_tiers on bookings.tier_id = ticket_tiers.id
            where bookings.ticket_code = ? and bookings.attendee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $ticket_code, $attendee_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getMyTickets($conn, $attendee_id){
    $sql = "select bookings.*, events.title, events.event_datetime, events.venue_name_override, ticket_tiers.name as tier_name
            from bookings
            join events on bookings.event_id = events.id
            join ticket_tiers on bookings.tier_id = ticket_tiers.id
            where bookings.attendee_id = ?
            order by bookings.id desc";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendee_id);
    $stmt->execute();
    return $stmt->get_result();
}

function countTotalTickets($conn, $attendee_id){
    $sql = "select count(*) as total from bookings where attendee_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendee_id);
    $stmt->execute();
    return $stmt->get_result();
}

function countUpcomingEvents($conn, $attendee_id){
    $sql = "select count(*) as total from bookings
            join events on bookings.event_id = events.id
            where bookings.attendee_id = ? and events.event_datetime > now()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendee_id);
    $stmt->execute();
    return $stmt->get_result();
}

function countEventsAttended($conn, $attendee_id){
    $sql = "select count(*) as total from bookings where attendee_id = ? and checked_in = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendee_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getRecentBookings($conn, $attendee_id){
    $sql = "select bookings.*, events.title, events.event_datetime, ticket_tiers.name as tier_name
            from bookings
            join events on bookings.event_id = events.id
            join ticket_tiers on bookings.tier_id = ticket_tiers.id
            where bookings.attendee_id = ?
            order by bookings.id desc
            limit 3";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attendee_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>