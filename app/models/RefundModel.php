<?php
class RefundModel extends Model {

    public function getByOrganiser($orgId, $status = 'pending') {
        return $this->db->query(
            "SELECT rr.*, u.name as attendee_name, u.email as attendee_email,
                    b.ticket_code, b.total_price, b.event_id,
                    e.title as event_title, t.name as tier_name
             FROM refund_requests rr
             JOIN bookings b      ON rr.booking_id = b.id
             JOIN users u         ON rr.attendee_id = u.id
             JOIN events e        ON b.event_id     = e.id
             JOIN ticket_tiers t  ON b.tier_id      = t.id
             WHERE e.organiser_id=? AND rr.status=?
             ORDER BY rr.created_at DESC",
            "is", [$orgId, $status]
        );
    }

    public function getStatusCounts($orgId) {
        $rows = $this->db->query(
            "SELECT rr.status, COUNT(*) as cnt
             FROM refund_requests rr
             JOIN bookings b ON rr.booking_id=b.id
             JOIN events e   ON b.event_id=e.id
             WHERE e.organiser_id=? GROUP BY rr.status",
            "i", [$orgId]
        );
        $counts = ['pending'=>0,'approved'=>0,'rejected'=>0];
        foreach ($rows as $r) $counts[$r['status']] = (int)$r['cnt'];
        return $counts;
    }

    public function getPendingById($reqId, $orgId) {
        $rows = $this->db->query(
            "SELECT rr.id, rr.booking_id FROM refund_requests rr
             JOIN bookings b ON rr.booking_id=b.id
             JOIN events e   ON b.event_id=e.id
             WHERE rr.id=? AND e.organiser_id=? AND rr.status='pending'",
            "ii", [$reqId, $orgId]
        );
        return $rows[0] ?? null;
    }

    public function approve($reqId, $bookingId) {
        $this->db->execute(
            "UPDATE refund_requests SET status='approved' WHERE id=?",
            "i", [$reqId]
        );
        $this->db->execute(
            "UPDATE bookings SET status='refunded' WHERE id=?",
            "i", [$bookingId]
        );
    }

    public function reject($reqId, $note) {
        $this->db->execute(
            "UPDATE refund_requests SET status='rejected', organiser_note=? WHERE id=?",
            "si", [$note, $reqId]
        );
    }
}