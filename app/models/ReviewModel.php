<?php
class ReviewModel extends Model {

    public function getByOrganiser($orgId, $rating = 'all') {
        $sql    = "SELECT r.*, u.name as attendee_name,
                          e.title as event_title
                   FROM event_reviews r
                   JOIN users u  ON r.attendee_id = u.id
                   JOIN events e ON r.event_id    = e.id
                   WHERE e.organiser_id = ?";
        $params = [$orgId];
        $types  = "i";

        if ($rating !== 'all' && is_numeric($rating)) {
            $sql .= " AND r.rating=?"; $params[] = (int)$rating; $types .= "i";
        }
        $sql .= " ORDER BY r.created_at DESC";
        return $this->db->query($sql, $types, $params);
    }

    public function getStats($orgId) {
        $rows = $this->db->query(
            "SELECT ROUND(AVG(r.rating),1) as avg_rating, COUNT(*) as total
             FROM event_reviews r
             JOIN events e ON r.event_id=e.id
             WHERE e.organiser_id=?",
            "i", [$orgId]
        );
        return $rows[0] ?? ['avg_rating'=>0,'total'=>0];
    }

    public function belongsToOrganiser($reviewId, $orgId) {
        $rows = $this->db->query(
            "SELECT r.id FROM event_reviews r
             JOIN events e ON r.event_id=e.id
             WHERE r.id=? AND e.organiser_id=?",
            "ii", [$reviewId, $orgId]
        );
        return !empty($rows);
    }

    public function saveReply($reviewId, $reply) {
        $this->db->execute(
            "UPDATE event_reviews SET organiser_reply=? WHERE id=?",
            "si", [$reply, $reviewId]
        );
    }
}