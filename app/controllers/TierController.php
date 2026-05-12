<?php
class TierController extends Controller {

    // Show tiers for an event
    public function index() {
        Auth::requireRole('organiser');
        $eventId = (int)($_GET['event_id'] ?? 0);
        $orgId   = Auth::userId();
        $db      = Database::getInstance();

        $events = $db->query(
            "SELECT * FROM events WHERE id=? AND organiser_id=?",
            "ii", [$eventId, $orgId]
        );
        if (empty($events)) { $this->redirect('organiser/events'); return; }
        $event = $events[0];

        $tiers = $db->query(
            "SELECT t.*,
                    COALESCE(SUM(CASE WHEN b.status!='refunded' THEN b.quantity ELSE 0 END),0) as sold_count,
                    COUNT(DISTINCT b.id) as bookings_count
             FROM ticket_tiers t
             LEFT JOIN bookings b ON b.tier_id = t.id
             WHERE t.event_id = ?
             GROUP BY t.id ORDER BY t.price ASC",
            "i", [$eventId]
        );

        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');
        $this->view('organiser/tiers/manage',
            compact('event','tiers','success','error'));
    }

    // Add new tier
    public function store() {
        Auth::requireRole('organiser');
        $eventId = (int)($_POST['event_id']    ?? 0);
        $orgId   = Auth::userId();
        $name    = trim($_POST['name']         ?? '');
        $desc    = trim($_POST['description']  ?? '');
        $price   = (float)($_POST['price']     ?? 0);
        $seats   = (int)($_POST['total_seats'] ?? 0);
        $sStart  = trim($_POST['sales_start']  ?? '') ?: null;
        $sEnd    = trim($_POST['sales_end']    ?? '') ?: null;

        $db = Database::getInstance();
        $ev = $db->query("SELECT id FROM events WHERE id=? AND organiser_id=?",
            "ii", [$eventId, $orgId]);
        if (empty($ev)) { $this->redirect('organiser/events'); return; }

        if (empty($name)) {
            Session::setFlash('error', 'Tier name is required.');
            $this->redirect('organiser/tiers?event_id='.$eventId); return;
        }
        if ($price < 0) {
            Session::setFlash('error', 'Price cannot be negative.');
            $this->redirect('organiser/tiers?event_id='.$eventId); return;
        }
        if ($seats < 1) {
            Session::setFlash('error', 'Total seats must be at least 1.');
            $this->redirect('organiser/tiers?event_id='.$eventId); return;
        }

        $db->execute(
            "INSERT INTO ticket_tiers
             (event_id, name, description, price, total_seats, sales_start, sales_end)
             VALUES (?,?,?,?,?,?,?)",
            "issdiss",
            [$eventId, $name, $desc, $price, $seats, $sStart, $sEnd]
        );
        Session::setFlash('success', 'Tier "' . $name . '" added.');
        $this->redirect('organiser/tiers?event_id='.$eventId);
    }

    // Delete a tier
    public function delete() {
        Auth::requireRole('organiser');
        $tierId  = (int)($_POST['tier_id']  ?? 0);
        $eventId = (int)($_POST['event_id'] ?? 0);
        $orgId   = Auth::userId();
        $db      = Database::getInstance();

        // Verify ownership through the event
        $rows = $db->query(
            "SELECT t.id FROM ticket_tiers t
             JOIN events e ON t.event_id = e.id
             WHERE t.id=? AND e.organiser_id=?",
            "ii", [$tierId, $orgId]
        );
        if (empty($rows)) { $this->redirect('organiser/tiers?event_id='.$eventId); return; }

        // Block if active bookings exist
        $check = $db->query(
            "SELECT COUNT(*) as cnt FROM bookings
             WHERE tier_id=? AND status!='refunded'",
            "i", [$tierId]
        );
        if (($check[0]['cnt'] ?? 0) > 0) {
            Session::setFlash('error',
                'Cannot delete — this tier has active bookings.');
            $this->redirect('organiser/tiers?event_id='.$eventId); return;
        }

        $db->execute("DELETE FROM ticket_tiers WHERE id=?", "i", [$tierId]);
        Session::setFlash('success', 'Tier deleted successfully.');
        $this->redirect('organiser/tiers?event_id='.$eventId);
    }
}
