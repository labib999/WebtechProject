<?php
session_start();
require_once '../models/VenueModel.php';

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'venue_manager') {
    header('Location: /webtechproject/WebtechProject/views/shared/login.php');
    exit;
}

$action    = $_POST['action'] ?? $_GET['action'] ?? '';
$model     = new VenueModel();
$managerId = $_SESSION['user_id'];

if ($action === 'create_venue')       createVenue($model, $managerId);
elseif ($action === 'update_venue')   updateVenue($model);
elseif ($action === 'delete_venue')   deleteVenue($model);
elseif ($action === 'save_pricing')   savePricing($model);
elseif ($action === 'block_date')     blockDate($model);
elseif ($action === 'approve_request') approveRequest($model);
elseif ($action === 'reject_request')  rejectRequest($model);
else {
    header('Location: /webtechproject/WebtechProject/views/venue/dashboard.php');
    exit;
}

function createVenue($model, $managerId) {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $city        = trim($_POST['city'] ?? '');
    $capacity    = (int)($_POST['capacity'] ?? 0);
    $facilities  = isset($_POST['facilities']) ? json_encode($_POST['facilities']) : json_encode([]);

    if (empty($name) || empty($address) || empty($city) || $capacity <= 0) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Please fill in all required fields.'];
        header('Location: /webtechproject/WebtechProject/views/venue/create_venue.php');
        exit;
    }

    $photos = [];
    if (!empty($_FILES['photos']['name'][0])) {
        $uploadDir = '../public/uploads/venues/';
        foreach ($_FILES['photos']['tmp_name'] as $i => $tmp) {
            if ($_FILES['photos']['error'][$i] === 0) {
                $ext     = pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION);
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array(strtolower($ext), $allowed)) continue;
                $filename = 'venue_' . time() . '_' . $i . '.' . $ext;
                if (move_uploaded_file($tmp, $uploadDir . $filename)) {
                    $photos[] = $filename;
                }
            }
        }
    }

    $photosJson = json_encode($photos);
    $venueId    = $model->createVenue($managerId, $name, $description, $address, $city, $capacity, $facilities, $photosJson);

    if ($venueId) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Venue created successfully!'];
        header('Location: /webtechproject/WebtechProject/views/venue/manage_venues.php');
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Failed to create venue. Please try again.'];
        header('Location: /webtechproject/WebtechProject/views/venue/create_venue.php');
    }
    exit;
}

function updateVenue($model) {
    $venueId     = (int)($_POST['venue_id'] ?? 0);
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $city        = trim($_POST['city'] ?? '');
    $capacity    = (int)($_POST['capacity'] ?? 0);
    $facilities  = isset($_POST['facilities']) ? json_encode($_POST['facilities']) : json_encode([]);

    if (empty($name) || empty($address) || empty($city) || $capacity <= 0) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Please fill in all required fields.'];
        header('Location: /webtechproject/WebtechProject/views/venue/edit_venue.php?id=' . $venueId);
        exit;
    }

    $result = $model->updateVenue($venueId, $name, $description, $address, $city, $capacity, $facilities);

    if ($result) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Venue updated successfully!'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Failed to update venue.'];
    }
    header('Location: /webtechproject/WebtechProject/views/venue/manage_venues.php');
    exit;
}

function deleteVenue($model) {
    $venueId = (int)($_POST['venue_id'] ?? 0);
    $result  = $model->deleteVenue($venueId);

    if ($result) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Venue deleted successfully!'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Failed to delete venue.'];
    }
    header('Location: /webtechproject/WebtechProject/views/venue/manage_venues.php');
    exit;
}

function savePricing($model) {
    $venueId = (int)($_POST['venue_id'] ?? 0);
    $weekday = (float)($_POST['weekday_price'] ?? 0);
    $weekend = (float)($_POST['weekend_price'] ?? 0);
    $holiday = (float)($_POST['holiday_price'] ?? 0);

    if ($venueId <= 0) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Invalid venue.'];
        header('Location: /webtechproject/WebtechProject/views/venue/pricing.php');
        exit;
    }

    $result = $model->savePricing($venueId, $weekday, $weekend, $holiday);

    if ($result) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Pricing saved successfully!'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Failed to save pricing.'];
    }
    header('Location: /webtechproject/WebtechProject/views/venue/pricing.php');
    exit;
}

function blockDate($model) {
    $venueId = (int)($_POST['venue_id'] ?? 0);
    $date    = trim($_POST['date'] ?? '');
    $note    = trim($_POST['note'] ?? '');

    if ($venueId <= 0 || empty($date)) {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
        exit;
    }

    $result = $model->blockDate($venueId, $date, $note);
    echo json_encode(['success' => $result, 'message' => $result ? 'Date blocked.' : 'Failed to block date.']);
    exit;
}

function approveRequest($model) {
    $requestId = (int)($_POST['request_id'] ?? 0);
    $result    = $model->approveRequest($requestId);

    if ($result) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Booking request approved!'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Failed to approve request.'];
    }
    header('Location: /webtechproject/WebtechProject/views/venue/booking_requests.php');
    exit;
}

function rejectRequest($model) {
    $requestId = (int)($_POST['request_id'] ?? 0);
    $note      = trim($_POST['manager_note'] ?? '');

    if (empty($note)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Please provide a rejection reason.'];
        header('Location: /webtechproject/WebtechProject/views/venue/booking_requests.php');
        exit;
    }

    $result = $model->rejectRequest($requestId, $note);

    if ($result) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Booking request rejected.'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Failed to reject request.'];
    }
    header('Location: /webtechproject/WebtechProject/views/venue/booking_requests.php');
    exit;
}
?>