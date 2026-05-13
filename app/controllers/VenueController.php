<?php
class VenueController extends Controller {

    // Browse available venues
    public function browse() {
        Auth::requireRole('organiser');
        $db     = Database::getInstance();
        $city   = trim($_GET['city']     ?? '');
        $minCap = (int)($_GET['min_cap'] ?? 0);

        $sql    = "SELECT v.*, u.name as manager_name FROM venues v
                   JOIN users u ON v.manager_id=u.id WHERE v.is_active=1";
        $params = []; $types = '';

        if (!empty($city)) {
            $sql .= " AND v.city LIKE ?"; $params[] = '%'.$city.'%'; $types .= "s";
        }
        if ($minCap > 0) {
            $sql .= " AND v.capacity >= ?"; $params[] = $minCap; $types .= "i";
        }
        $sql .= " ORDER BY v.name ASC";

        $venues = empty($params)
            ? $db->query($sql)
            : $db->query($sql, $types, $params);

        $cities = $db->query("SELECT DISTINCT city FROM venues WHERE is_active=1 ORDER BY city");

        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');
        $this->view('organiser/venues/browse',
            compact('venues','cities','city','minCap','success','error'));
    }

    // Submit a venue booking request
    public function request() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $venueId = (int)($_POST['venue_id']       ?? 0);
        $dates   = trim($_POST['requested_dates'] ?? '');
        $preview = trim($_POST['event_title_preview'] ?? '');
        $message = trim($_POST['message']         ?? '');

        if ($venueId === 0 || empty($dates)) {
            Session::setFlash('error', 'Please select a venue and at least one date.');
            $this->redirect('organiser/venues'); return;
        }

        $db = Database::getInstance();
        $datesArr = array_filter(array_map('trim', explode(',', $dates)));
        $db->execute(
            "INSERT INTO venue_booking_requests
             (venue_id,organiser_id,event_title_preview,requested_dates,message,status)
             VALUES (?,?,?,?,?,'pending')",
            "iisss",
            [$venueId, $orgId, $preview, json_encode(array_values($datesArr)), $message]
        );
        Session::setFlash('success', 'Venue booking request submitted. The venue manager will review it.');
        $this->redirect('organiser/venue-requests');
    }

    // My venue booking requests
    public function myRequests() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

        $requests = $db->query(
            "SELECT vbr.*, v.name as venue_name, v.city, v.capacity
             FROM venue_booking_requests vbr
             JOIN venues v ON vbr.venue_id=v.id
             WHERE vbr.organiser_id=?
             ORDER BY vbr.submitted_at DESC",
            "i", [$orgId]
        );

        $success = Session::getFlash('success');
        $this->view('organiser/venues/requests', compact('requests','success'));
    }
}