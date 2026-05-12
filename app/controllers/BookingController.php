<?php
class BookingController extends Controller {

    public function index() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

        $eventFilter   = (int)($_GET['event_id']  ?? 0);
        $statusFilter  = $_GET['status']           ?? 'all';
        $checkinFilter = $_GET['checked_in']       ?? 'all';
        $search        = trim($_GET['q']           ?? '');

        $myEvents = $db->query(
            "SELECT id, title FROM events WHERE organiser_id=? ORDER BY event_datetime DESC",
            "i", [$orgId]
        );

        $sql    = "SELECT b.*, u.name as attendee_name, u.email as attendee_email,
                          e.title as event_title, t.name as tier_name
                   FROM bookings b
                   JOIN users u        ON b.attendee_id = u.id
                   JOIN events e       ON b.event_id    = e.id
                   JOIN ticket_tiers t ON b.tier_id     = t.id
                   WHERE e.organiser_id = ?";
        $params = [$orgId];
        $types  = "i";

        if ($eventFilter > 0) {
            $sql .= " AND b.event_id=?"; $params[]=$eventFilter; $types.="i";
        }
        if ($statusFilter !== 'all') {
            $sql .= " AND b.status=?"; $params[]=$statusFilter; $types.="s";
        }
        if ($checkinFilter === 'yes') { $sql .= " AND b.checked_in=1"; }
        if ($checkinFilter === 'no')  { $sql .= " AND b.checked_in=0"; }
        if (!empty($search)) {
            $sql .= " AND (b.ticket_code LIKE ? OR u.name LIKE ?)";
            $params[]='%'.$search.'%'; $params[]='%'.$search.'%'; $types.="ss";
        }
        $sql .= " ORDER BY b.created_at DESC LIMIT 100";

        $bookings     = $db->query($sql, $types, $params);
        $totalRevenue = array_sum(array_column(
            array_filter($bookings, fn($b)=>$b['status']==='active'), 'total_price'));
        $error = Session::getFlash('error');

        $this->view('organiser/bookings/list',
            compact('bookings','myEvents','eventFilter','statusFilter',
                    'checkinFilter','search','totalRevenue','error'));
    }
}