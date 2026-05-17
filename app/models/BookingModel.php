<?php
class BookingModel extends Model {

    public function getByOrganiser($orgId, $filters = []) {
        $sql    = "SELECT b.*, u.name as attendee_name, u.email as attendee_email,
                          e.title as event_title, t.name as tier_name
                   FROM bookings b
                   JOIN users u        ON b.attendee_id = u.id
                   JOIN events e       ON b.event_id    = e.id
                   JOIN ticket_tiers t ON b.tier_id     = t.id
                   WHERE e.organiser_id = ?";
        $params = [$orgId];
        $types  = "i";

        if (!empty($filters['event_id'])) {
            $sql .= " AND b.event_id=?"; $params[]=$filters['event_id']; $types.="i";
        }
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $sql .= " AND b.status=?"; $params[]=$filters['status']; $types.="s";
        }
        if (isset($filters['checked_in'])) {
            if ($filters['checked_in'] === 'yes') $sql .= " AND b.checked_in=1";
            if ($filters['checked_in'] === 'no')  $sql .= " AND b.checked_in=0";
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (b.ticket_code LIKE ? OR u.name LIKE ?)";
            $params[] = '%'.$filters['search'].'%';
            $params[] = '%'.$filters['search'].'%';
            $types .= "ss";
        }

        $sql .= " ORDER BY b.created_at DESC LIMIT 100";
        return $this->db->query($sql, $types, $params);
    }

    public function getByTicketCode($ticketCode, $eventId) {
        $rows = $this->db->query(
            "SELECT b.*, u.name as attendee_name, t.name as tier_name
             FROM bookings b
             JOIN users u        ON b.attendee_id = u.id
             JOIN ticket_tiers t ON b.tier_id     = t.id
             WHERE b.ticket_code=? AND b.event_id=?",
            "si", [$ticketCode, $eventId]
        );
        return $rows[0] ?? null;
    }

    public function checkIn($bookingId) {
        $this->db->execute(
            "UPDATE bookings SET checked_in=1, checked_in_at=NOW() WHERE id=?",
            "i", [$bookingId]
        );
    }

    public function getCheckinStats($eventId) {
        $rows = $this->db->query(
            "SELECT COUNT(*) as total_sold,
                    COALESCE(SUM(checked_in),0) as checked_in_count
             FROM bookings WHERE event_id=? AND status='active'",
            "i", [$eventId]
        );
        return $rows[0] ?? ['total_sold'=>0,'checked_in_count'=>0];
    }

    public function getRecentCheckins($eventId, $limit = 8) {
        return $this->db->query(
            "SELECT b.checked_in_at, u.name as attendee_name, t.name as tier_name
             FROM bookings b
             JOIN users u        ON b.attendee_id=u.id
             JOIN ticket_tiers t ON b.tier_id=t.id
             WHERE b.event_id=? AND b.checked_in=1
             ORDER BY b.checked_in_at DESC LIMIT ?",
            "ii", [$eventId, $limit]
        );
    }

    public function getTotalRevenue($bookings) {
        return array_sum(array_column(
            array_filter($bookings, fn($b) => $b['status']==='active'),
            'total_price'
        ));
    }
}