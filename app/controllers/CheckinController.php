<?php
class CheckinController extends Controller {

    // Show the scanner page
    public function scanner() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

        // Get all published events for this organiser
        $myEvents = $db->query(
            "SELECT id, title, event_datetime FROM events
             WHERE organiser_id=? AND status='published'
             ORDER BY event_datetime DESC",
            "i", [$orgId]
        );

        $eventId = (int)($_GET['event_id'] ?? 0);

        // Auto-select first event if none chosen
        if ($eventId === 0 && !empty($myEvents)) {
            $this->redirect('organiser/checkin?event_id=' . $myEvents[0]['id']);
            return;
        }

        $event = null; $stats = null; $recentCheckins = [];

        if ($eventId > 0) {
            $evRows = $db->query(
                "SELECT * FROM events WHERE id=? AND organiser_id=?",
                "ii", [$eventId, $orgId]
            );
            if (!empty($evRows)) {
                $event = $evRows[0];

                // Stats
                $sRow = $db->query(
                    "SELECT COUNT(*) as total_sold,
                            COALESCE(SUM(checked_in),0) as checked_in_count
                     FROM bookings WHERE event_id=? AND status='active'",
                    "i", [$eventId]
                );
                $stats = $sRow[0];

                // Recent check-ins (last 8)
                $recentCheckins = $db->query(
                    "SELECT b.ticket_code, b.checked_in_at,
                            u.name as attendee_name, t.name as tier_name
                     FROM bookings b
                     JOIN users u ON b.attendee_id = u.id
                     JOIN ticket_tiers t ON b.tier_id = t.id
                     WHERE b.event_id=? AND b.checked_in=1
                     ORDER BY b.checked_in_at DESC LIMIT 8",
                    "i", [$eventId]
                );
            }
        }

        $this->view('organiser/checkin/scanner',
            compact('myEvents','event','eventId','stats','recentCheckins'));
    }

    // AJAX endpoint — processes ticket code, returns JSON
    public function process() {
        Auth::requireRole('organiser');

        $input      = json_decode(file_get_contents('php://input'), true);
        $ticketCode = strtoupper(trim($input['ticket_code'] ?? ''));
        $eventId    = (int)($input['event_id'] ?? 0);
        $orgId      = Auth::userId();

        if (empty($ticketCode) || $eventId === 0) {
            $this->json(['status'=>'error','message'=>'Missing ticket code or event.']);
            return;
        }

        $db = Database::getInstance();

        // Verify event belongs to organiser
        $ev = $db->query(
            "SELECT id FROM events WHERE id=? AND organiser_id=?",
            "ii", [$eventId, $orgId]
        );
        if (empty($ev)) {
            $this->json(['status'=>'error','message'=>'Event not found.']);
            return;
        }

        // Find booking
        $rows = $db->query(
            "SELECT b.*, u.name as attendee_name, t.name as tier_name
             FROM bookings b
             JOIN users u ON b.attendee_id = u.id
             JOIN ticket_tiers t ON b.tier_id = t.id
             WHERE b.ticket_code=? AND b.event_id=?",
            "si", [$ticketCode, $eventId]
        );

        if (empty($rows)) {
            $this->json(['status'=>'error','message'=>'Invalid ticket code. No booking found.']);
            return;
        }

        $booking = $rows[0];

        if ($booking['status'] === 'cancelled' || $booking['status'] === 'refunded') {
            $this->json(['status'=>'error',
                'message'=>'This ticket has been ' . $booking['status'] . '.']);
            return;
        }

        if ($booking['checked_in']) {
            $time = date('h:i A', strtotime($booking['checked_in_at']));
            $this->json(['status'=>'used',
                'message'=>$booking['attendee_name'] . ' already checked in at ' . $time . '.']);
            return;
        }

        // Mark as checked in
        $now = date('Y-m-d H:i:s');
        $db->execute(
            "UPDATE bookings SET checked_in=1, checked_in_at=? WHERE id=?",
            "si", [$now, $booking['id']]
        );

        // Get updated stats for live counter update
        $statsRow = $db->query(
            "SELECT COUNT(*) as total_sold,
                    COALESCE(SUM(checked_in),0) as checked_in_count
             FROM bookings WHERE event_id=? AND status='active'",
            "i", [$eventId]
        );

        $this->json([
            'status'         => 'ok',
            'message'        => 'Welcome, ' . $booking['attendee_name'] . '! Entry granted.',
            'attendee'       => $booking['attendee_name'],
            'tier'           => $booking['tier_name'],
            'checked_in_at'  => date('H:i', strtotime($now)),
            'total_sold'     => (int)$statsRow[0]['total_sold'],
            'checked_in_count' => (int)$statsRow[0]['checked_in_count'],
        ]);
    }
}