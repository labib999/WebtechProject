<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'venue_manager') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$venueId = (int)($_GET['venue_id'] ?? 0);
$month   = (int)($_GET['month']    ?? date('n'));
$year    = (int)($_GET['year']     ?? date('Y'));

if ($venueId <= 0) {
    echo json_encode(['error' => 'Invalid venue']);
    exit;
}

$conn = getDB();
$stmt = $conn->prepare("SELECT date, status, note FROM venue_availability WHERE venue_id = ? AND MONTH(date) = ? AND YEAR(date) = ?");
$stmt->bind_param('iii', $venueId, $month, $year);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['date']] = [
        'status' => $row['status'],
        'note'   => $row['note']
    ];
}

$stmt->close();
$conn->close();

echo json_encode($data);