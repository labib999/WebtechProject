<?php
class VenueModel extends Model {

    public function getAll($city = '', $minCap = 0) {
        $sql    = "SELECT v.*, u.name as manager_name
                   FROM venues v
                   JOIN users u ON v.manager_id=u.id
                   WHERE v.is_active=1";
        $params = [];
        $types  = '';

        if (!empty($city)) {
            $sql .= " AND v.city LIKE ?";
            $params[] = '%'.$city.'%';
            $types   .= "s";
        }
        if ($minCap > 0) {
            $sql .= " AND v.capacity >= ?";
            $params[] = $minCap;
            $types   .= "i";
        }
        $sql .= " ORDER BY v.name ASC";

        return empty($params)
            ? $this->db->query($sql)
            : $this->db->query($sql, $types, $params);
    }

    public function getCities() {
        return $this->db->query(
            "SELECT DISTINCT city FROM venues
             WHERE is_active=1 ORDER BY city"
        );
    }

    public function getById($venueId) {
        $rows = $this->db->query(
            "SELECT * FROM venues WHERE id=? AND is_active=1",
            "i", [$venueId]
        );
        return $rows[0] ?? null;
    }

    public function createRequest($venueId, $orgId, $preview, $dates, $message) {
        $this->db->execute(
            "INSERT INTO venue_booking_requests
             (venue_id,organiser_id,event_title_preview,requested_dates,message,status)
             VALUES (?,?,?,?,?,'pending')",
            "iisss",
            [$venueId, $orgId, $preview, $dates, $message]
        );
    }

    public function getRequestsByOrganiser($orgId) {
        return $this->db->query(
            "SELECT vbr.*, v.name as venue_name, v.city, v.capacity
             FROM venue_booking_requests vbr
             JOIN venues v ON vbr.venue_id=v.id
             WHERE vbr.organiser_id=?
             ORDER BY vbr.submitted_at DESC",
            "i", [$orgId]
        );
    }
}