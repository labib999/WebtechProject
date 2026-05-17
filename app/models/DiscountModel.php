<?php
class DiscountModel extends Model {

    public function getByOrganiser($orgId) {
        return $this->db->query(
            "SELECT d.*, e.title as event_title
             FROM discount_codes d
             JOIN events e ON d.event_id=e.id
             WHERE e.organiser_id=? ORDER BY d.id DESC",
            "i", [$orgId]
        );
    }

    public function codeExists($code) {
        $rows = $this->db->query(
            "SELECT id FROM discount_codes WHERE code=?",
            "s", [$code]
        );
        return !empty($rows);
    }

    public function create($eventId, $orgId, $code, $pct, $maxUses, $validUntil) {
        $this->db->execute(
            "INSERT INTO discount_codes
             (event_id,organiser_id,code,discount_pct,max_uses,valid_until,is_active)
             VALUES (?,?,?,?,?,?,1)",
            "iisdss",
            [$eventId, $orgId, $code, $pct, $maxUses, $validUntil]
        );
    }

    public function getById($codeId, $orgId) {
        $rows = $this->db->query(
            "SELECT d.* FROM discount_codes d
             JOIN events e ON d.event_id=e.id
             WHERE d.id=? AND e.organiser_id=?",
            "ii", [$codeId, $orgId]
        );
        return $rows[0] ?? null;
    }

    public function toggle($codeId, $newStatus) {
        $this->db->execute(
            "UPDATE discount_codes SET is_active=? WHERE id=?",
            "ii", [$newStatus, $codeId]
        );
    }
}