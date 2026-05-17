<?php
require_once 'config/db.php';


function getEventById($id) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT e.*, 
                                   u.name as organiser_name,
                                   c.name as category_name, c.icon as category_icon
                            FROM events e
                            JOIN users u ON e.organiser_id = u.id
                            JOIN categories c ON e.category_id = c.id
                            WHERE e.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


function getPublishedUpcomingEvents() {
    $conn = getDB();
    $sql = "SELECT e.id, e.title, e.event_datetime, e.is_featured,
                   u.name as organiser_name,
                   c.name as category_name, c.icon as category_icon
            FROM events e
            JOIN users u ON e.organiser_id = u.id
            JOIN categories c ON e.category_id = c.id
            WHERE e.status = 'published' AND e.event_datetime > NOW()
            ORDER BY e.event_datetime ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getFeaturedEvents() {
    $conn = getDB();
    $sql = "SELECT e.id, e.title, e.event_datetime,
                   u.name as organiser_name,
                   c.name as category_name
            FROM events e
            JOIN users u ON e.organiser_id = u.id
            JOIN categories c ON e.category_id = c.id
            WHERE e.is_featured = 1
            ORDER BY e.event_datetime ASC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function countTicketsSoldForEvent($event_id) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM bookings 
                            WHERE event_id = ? AND status = 'active'");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}