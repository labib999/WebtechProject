<?php
class TierModel extends Model {

    public function getByEvent($eventId) {
        return $this->db->query(
            "SELECT t.*, COALESCE(COUNT(b.id),0) as sold
             FROM ticket_tiers t
             LEFT JOIN bookings b ON b.tier_id=t.id AND b.status != 'refunded'
             WHERE t.event_id=?
             GROUP BY t.id ORDER BY t.price ASC",
            "i", [$eventId]
        );
    }

    public function create($eventId, $name, $price, $seats) {
        $this->db->execute(
            "INSERT INTO ticket_tiers (event_id, name, price, total_seats)
             VALUES (?,?,?,?)",
            "isdi", [$eventId, $name, $price, $seats]
        );
    }

    public function delete($tierId, $orgId) {
        $this->db->execute(
            "DELETE t FROM ticket_tiers t
             JOIN events e ON t.event_id=e.id
             WHERE t.id=? AND e.organiser_id=?",
            "ii", [$tierId, $orgId]
        );
    }

    public function hasSales($tierId) {
        $rows = $this->db->query(
            "SELECT COUNT(*) as cnt FROM bookings
             WHERE tier_id=? AND status != 'refunded'",
            "i", [$tierId]
        );
        return (int)($rows[0]['cnt'] ?? 0) > 0;
    }

    public function belongsToOrganiser($tierId, $orgId) {
        $rows = $this->db->query(
            "SELECT t.id FROM ticket_tiers t
             JOIN events e ON t.event_id=e.id
             WHERE t.id=? AND e.organiser_id=?",
            "ii", [$tierId, $orgId]
        );
        return !empty($rows);
    }
}