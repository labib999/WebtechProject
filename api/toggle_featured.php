<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/AdminModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$event_id    = (int)$_POST['event_id'];
$is_featured = (int)$_POST['is_featured'];

if ($is_featured === 1) {
    $currentFeatured = countFeaturedEvents();
    if ($currentFeatured >= 5) {
        echo json_encode(['success' => false, 'message' => 'Maximum 5 featured events allowed.']);
        exit;
    }
}

$result = toggleFeatured($event_id, $is_featured);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Featured status updated!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Something went wrong.']);
}