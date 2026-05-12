<?php
class RefundController extends Controller {

    public function index() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

        $filter   = $_GET['status'] ?? 'pending';
        $requests = $db->query(
            "SELECT rr.*, u.name as attendee_name, u.email as attendee_email,
                    b.ticket_code, b.total_price, b.event_id,
                    e.title as event_title, t.name as tier_name
             FROM refund_requests rr
             JOIN bookings b ON rr.booking_id = b.id
             JOIN users u    ON rr.attendee_id = u.id
             JOIN events e   ON b.event_id     = e.id
             JOIN ticket_tiers t ON b.tier_id  = t.id
             WHERE e.organiser_id = ?
               AND rr.status = ?
             ORDER BY rr.created_at DESC",
            "is", [$orgId, $filter]
        );

        $counts = $db->query(
            "SELECT rr.status, COUNT(*) as cnt
             FROM refund_requests rr
             JOIN bookings b ON rr.booking_id=b.id
             JOIN events e   ON b.event_id=e.id
             WHERE e.organiser_id=? GROUP BY rr.status",
            "i", [$orgId]
        );
        $statusCounts = ['pending'=>0,'approved'=>0,'rejected'=>0];
        foreach ($counts as $row) $statusCounts[$row['status']] = (int)$row['cnt'];

        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');
        $this->view('organiser/refunds/queue',
            compact('requests','filter','statusCounts','success','error'));
    }

    public function approve() {
        Auth::requireRole('organiser');
        $reqId = (int)($_POST['request_id'] ?? 0);
        $orgId = Auth::userId();
        $db    = Database::getInstance();

        $rows = $db->query(
            "SELECT rr.id, rr.booking_id FROM refund_requests rr
             JOIN bookings b ON rr.booking_id=b.id
             JOIN events e   ON b.event_id=e.id
             WHERE rr.id=? AND e.organiser_id=? AND rr.status='pending'",
            "ii", [$reqId, $orgId]
        );
        if (empty($rows)) { $this->redirect('organiser/refunds'); return; }

        $db->execute("UPDATE refund_requests SET status='approved' WHERE id=?", "i", [$reqId]);
        $db->execute("UPDATE bookings SET status='refunded' WHERE id=?", "i", [$rows[0]['booking_id']]);

        Session::setFlash('success', 'Refund approved. Booking marked as refunded.');
        $this->redirect('organiser/refunds');
    }

    public function reject() {
        Auth::requireRole('organiser');
        $reqId  = (int)($_POST['request_id'] ?? 0);
        $note   = trim($_POST['organiser_note'] ?? '');
        $orgId  = Auth::userId();
        $db     = Database::getInstance();

        $rows = $db->query(
            "SELECT rr.id FROM refund_requests rr
             JOIN bookings b ON rr.booking_id=b.id
             JOIN events e   ON b.event_id=e.id
             WHERE rr.id=? AND e.organiser_id=? AND rr.status='pending'",
            "ii", [$reqId, $orgId]
        );
        if (empty($rows)) { $this->redirect('organiser/refunds'); return; }

        $db->execute(
            "UPDATE refund_requests SET status='rejected', organiser_note=? WHERE id=?",
            "si", [$note, $reqId]
        );
        Session::setFlash('success', 'Refund request rejected.');
        $this->redirect('organiser/refunds');
    }
}