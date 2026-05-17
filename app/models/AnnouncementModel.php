<?php
class AnnouncementModel extends Model {

    public function getByOrganiser($orgId) {
        return $this->db->query(
            "SELECT a.*, e.title as event_title,
                    (SELECT COUNT(*) FROM bookings b
                     WHERE b.event_id=a.event_id
                     AND b.status='active') as recipient_count
             FROM announcements a
             JOIN events e ON a.event_id=e.id
             WHERE e.organiser_id=?
             ORDER BY a.sent_at DESC",
            "i", [$orgId]
        );
    }

    public function getPublishedEvents($orgId) {
        return $this->db->query(
            "SELECT id, title FROM events
             WHERE organiser_id=? AND status='published'
             ORDER BY event_datetime DESC",
            "i", [$orgId]
        );
    }

    public function eventBelongsToOrganiser($eventId, $orgId) {
        $rows = $this->db->query(
            "SELECT id FROM events
             WHERE id=? AND organiser_id=? AND status='published'",
            "ii", [$eventId, $orgId]
        );
        return !empty($rows);
    }

    public function getRecipientCount($eventId) {
        $rows = $this->db->query(
            "SELECT COUNT(*) as cnt FROM bookings
             WHERE event_id=? AND status='active'",
            "i", [$eventId]
        );
        return (int)($rows[0]['cnt'] ?? 0);
    }

    public function create($eventId, $orgId, $title, $body) {
        $this->db->execute(
            "INSERT INTO announcements (event_id, organiser_id, title, body)
             VALUES (?,?,?,?)",
            "iiss", [$eventId, $orgId, $title, $body]
        );
    }
}