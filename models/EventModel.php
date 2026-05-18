<?php
function getPublishedEvents($conn){
    $sql = "select events.id, events.title, events.description, events.venue_name_override, events.event_datetime, categories.name as category_name,
            (select min(price) from ticket_tiers where ticket_tiers.event_id = events.id) as min_price
            from events
            left join categories on events.category_id = categories.id
            where events.status = 'published'
            order by events.event_datetime asc";
    return $conn->query($sql);
}

function searchPublishedEvents($conn, $search){
    $sql = "select events.id, events.title, events.description, events.venue_name_override, events.event_datetime, categories.name as category_name,
            (select min(price) from ticket_tiers where ticket_tiers.event_id = events.id) as min_price
            from events
            left join categories on events.category_id = categories.id
            where events.status = 'published' and events.title like ?
            order by events.event_datetime asc";
    $stmt = $conn->prepare($sql);
    $searchText = "%" . $search . "%";
    $stmt->bind_param("s", $searchText);
    $stmt->execute();
    return $stmt->get_result();
}

function getEventDetails($conn, $event_id){
    $sql = "select events.*, categories.name as category_name, users.name as organiser_name
            from events
            left join categories on events.category_id = categories.id
            left join users on events.organiser_id = users.id
            where events.id = ? and events.status = 'published'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getTicketTiers($conn, $event_id){
    $sql = "select * from ticket_tiers where event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getEventReviews($conn, $event_id){
    $sql = "select event_reviews.*, users.name as attendee_name
            from event_reviews
            left join users on event_reviews.attendee_id = users.id
            where event_reviews.event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getCheckoutEvent($conn, $event_id, $tier_id){
    $sql = "select events.title, events.event_datetime, events.venue_name_override, ticket_tiers.id as tier_id, ticket_tiers.name as tier_name, ticket_tiers.price
            from events
            join ticket_tiers on events.id = ticket_tiers.event_id
            where events.id = ? and ticket_tiers.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $event_id, $tier_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getUpcomingEvents($conn){
    $sql = "select id, title, event_datetime, venue_name_override
            from events
            where status = 'published'
            order by event_datetime asc
            limit 3";
    return $conn->query($sql);
}
?>