<?php
require_once __DIR__ . '/../config/db.php';

class VenueModel {

    public function createVenue($managerId, $name, $description, $address, $city, $capacity, $facilities, $photos) {
        $conn = getDB();
        $stmt = $conn->prepare("INSERT INTO venues (manager_id, name, description, address, city, capacity, facilities, photos) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('issssiss', $managerId, $name, $description, $address, $city, $capacity, $facilities, $photos);
        $result = $stmt->execute();
        $id = $conn->insert_id;
        $stmt->close();
        $conn->close();
        return $result ? $id : false;
    }

    public function getVenuesByManager($managerId) {
        $conn = getDB();
        $stmt = $conn->prepare("SELECT * FROM venues WHERE manager_id = ? AND is_active = 1 ORDER BY created_at DESC");
        $stmt->bind_param('i', $managerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $venues = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $venues;
    }

    public function getVenueById($venueId) {
        $conn = getDB();
        $stmt = $conn->prepare("SELECT * FROM venues WHERE id = ?");
        $stmt->bind_param('i', $venueId);
        $stmt->execute();
        $result = $stmt->get_result();
        $venue = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $venue;
    }

    public function updateVenue($venueId, $name, $description, $address, $city, $capacity, $facilities) {
        $conn = getDB();
        $stmt = $conn->prepare("UPDATE venues SET name=?, description=?, address=?, city=?, capacity=?, facilities=? WHERE id=?");
        $stmt->bind_param('ssssisi', $name, $description, $address, $city, $capacity, $facilities, $venueId);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function deleteVenue($venueId) {
        $conn = getDB();
        $stmt = $conn->prepare("UPDATE venues SET is_active = 0 WHERE id = ?");
        $stmt->bind_param('i', $venueId);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function savePricing($venueId, $weekday, $weekend, $holiday) {
        $conn = getDB();
        $types = ['weekday' => $weekday, 'weekend' => $weekend, 'holiday' => $holiday];
        foreach ($types as $type => $price) {
            $stmt = $conn->prepare("INSERT INTO venue_pricing (venue_id, day_type, price_per_day) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE price_per_day = ?");
            $stmt->bind_param('isdd', $venueId, $type, $price, $price);
            $stmt->execute();
            $stmt->close();
        }
        $conn->close();
        return true;
    }

    public function getPricing($venueId) {
        $conn = getDB();
        $stmt = $conn->prepare("SELECT day_type, price_per_day FROM venue_pricing WHERE venue_id = ?");
        $stmt->bind_param('i', $venueId);
        $stmt->execute();
        $result = $stmt->get_result();
        $pricing = [];
        while ($row = $result->fetch_assoc()) {
            $pricing[$row['day_type']] = $row['price_per_day'];
        }
        $stmt->close();
        $conn->close();
        return $pricing;
    }

    public function getAvailability($venueId, $year, $month) {
        $conn = getDB();
        $stmt = $conn->prepare("SELECT date, status, note FROM venue_availability WHERE venue_id = ? AND YEAR(date) = ? AND MONTH(date) = ?");
        $stmt->bind_param('iii', $venueId, $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        $availability = [];
        while ($row = $result->fetch_assoc()) {
            $availability[$row['date']] = ['status' => $row['status'], 'note' => $row['note']];
        }
        $stmt->close();
        $conn->close();
        return $availability;
    }

    public function blockDate($venueId, $date, $note) {
        $conn = getDB();
        $stmt = $conn->prepare("INSERT INTO venue_availability (venue_id, date, status, note) VALUES (?, ?, 'blocked', ?) ON DUPLICATE KEY UPDATE status='blocked', note=?");
        $stmt->bind_param('isss', $venueId, $date, $note, $note);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function getBookingRequests($managerId, $status = null) {
        $conn = getDB();
        if ($status) {
            $stmt = $conn->prepare("SELECT vbr.*, u.name as organiser_name, u.email as organiser_email, v.name as venue_name FROM venue_booking_requests vbr JOIN users u ON vbr.organiser_id = u.id JOIN venues v ON vbr.venue_id = v.id WHERE v.manager_id = ? AND vbr.status = ? ORDER BY vbr.submitted_at DESC");
            $stmt->bind_param('is', $managerId, $status);
        } else {
            $stmt = $conn->prepare("SELECT vbr.*, u.name as organiser_name, u.email as organiser_email, v.name as venue_name FROM venue_booking_requests vbr JOIN users u ON vbr.organiser_id = u.id JOIN venues v ON vbr.venue_id = v.id WHERE v.manager_id = ? ORDER BY vbr.submitted_at DESC");
            $stmt->bind_param('i', $managerId);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $requests = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $requests;
    }

    public function approveRequest($requestId) {
        $conn = getDB();
        $stmt = $conn->prepare("SELECT venue_id, requested_dates FROM venue_booking_requests WHERE id = ?");
        $stmt->bind_param('i', $requestId);
        $stmt->execute();
        $result = $stmt->get_result();
        $request = $result->fetch_assoc();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE venue_booking_requests SET status = 'approved' WHERE id = ?");
        $stmt->bind_param('i', $requestId);
        $stmt->execute();
        $stmt->close();

        $dates = json_decode($request['requested_dates'], true);
        foreach ($dates as $date) {
            $stmt = $conn->prepare("INSERT INTO venue_availability (venue_id, date, status) VALUES (?, ?, 'booked') ON DUPLICATE KEY UPDATE status='booked'");
            $stmt->bind_param('is', $request['venue_id'], $date);
            $stmt->execute();
            $stmt->close();
        }

        $conn->close();
        return true;
    }

    public function rejectRequest($requestId, $note) {
        $conn = getDB();
        $stmt = $conn->prepare("UPDATE venue_booking_requests SET status = 'rejected', manager_note = ? WHERE id = ?");
        $stmt->bind_param('si', $note, $requestId);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result;
    }

    public function getUpcomingEvents($managerId) {
        $conn = getDB();
        $stmt = $conn->prepare("SELECT e.title, e.event_datetime, u.name as organiser_name, v.name as venue_name FROM events e JOIN venues v ON e.venue_id = v.id JOIN users u ON e.organiser_id = u.id WHERE v.manager_id = ? AND e.status = 'published' AND e.event_datetime >= NOW() ORDER BY e.event_datetime ASC");
        $stmt->bind_param('i', $managerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $events = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $events;
    }

    public function getOccupancyReport($managerId, $month, $year) {
        $conn = getDB();
        $stmt = $conn->prepare("SELECT v.name, COUNT(va.date) as booked_days, SUM(vp.price_per_day) as revenue FROM venue_availability va JOIN venues v ON va.venue_id = v.id JOIN venue_pricing vp ON vp.venue_id = v.id WHERE v.manager_id = ? AND va.status = 'booked' AND MONTH(va.date) = ? AND YEAR(va.date) = ? AND vp.day_type = 'weekday' GROUP BY v.id, v.name");
        $stmt->bind_param('iii', $managerId, $month, $year);
        $stmt->execute();
        $result = $stmt->get_result();
        $report = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $report;
    }
}
?>