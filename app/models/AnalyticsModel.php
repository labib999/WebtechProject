<?php
class AnalyticsModel extends Model {

    public function getKpis($orgId) {
        $rows = $this->db->query(
            "SELECT COALESCE(SUM(b.quantity),0) as tickets_sold,
                    COALESCE(SUM(CASE WHEN b.status='active' THEN b.total_price ELSE 0 END),0) as revenue,
                    COALESCE(SUM(b.checked_in),0) as checked_in,
                    COUNT(CASE WHEN b.status='refunded' THEN 1 END) as refunded_count
             FROM bookings b JOIN events e ON b.event_id=e.id
             WHERE e.organiser_id=?",
            "i", [$orgId]
        );
        return $rows[0] ?? [];
    }

    public function getSalesChart($orgId, $period) {
        return $this->db->query(
            "SELECT DATE(b.created_at) as sale_date,
                    SUM(b.quantity) as tickets,
                    SUM(CASE WHEN b.status='active' THEN b.total_price ELSE 0 END) as revenue
             FROM bookings b JOIN events e ON b.event_id=e.id
             WHERE e.organiser_id=?
               AND b.created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
             GROUP BY DATE(b.created_at) ORDER BY sale_date ASC",
            "ii", [$orgId, $period]
        );
    }

    public function getTierChart($orgId) {
        return $this->db->query(
            "SELECT t.name as tier_name, t.total_seats,
                    COALESCE(COUNT(b.id),0) as sold,
                    COALESCE(SUM(CASE WHEN b.status!='refunded' THEN b.total_price ELSE 0 END),0) as revenue
             FROM ticket_tiers t
             LEFT JOIN bookings b ON b.tier_id=t.id
             JOIN events e ON t.event_id=e.id
             WHERE e.organiser_id=?
             GROUP BY t.id, t.name, t.total_seats ORDER BY revenue DESC",
            "i", [$orgId]
        );
    }

    public function getEventChart($orgId) {
        return $this->db->query(
            "SELECT e.title, e.status,
                    COALESCE(COUNT(b.id),0) as bookings,
                    COALESCE(SUM(CASE WHEN b.status='active' THEN b.total_price ELSE 0 END),0) as revenue,
                    COALESCE(SUM(b.checked_in),0) as checked_in
             FROM events e LEFT JOIN bookings b ON b.event_id=e.id
             WHERE e.organiser_id=?
             GROUP BY e.id ORDER BY revenue DESC",
            "i", [$orgId]
        );
    }

    public function getHourChart($orgId) {
        return $this->db->query(
            "SELECT HOUR(b.checked_in_at) as hr, COUNT(*) as cnt
             FROM bookings b JOIN events e ON b.event_id=e.id
             WHERE e.organiser_id=? AND b.checked_in=1
               AND b.checked_in_at IS NOT NULL
             GROUP BY HOUR(b.checked_in_at) ORDER BY hr ASC",
            "i", [$orgId]
        );
    }

    public function getRatingStats($orgId) {
        $rows = $this->db->query(
            "SELECT ROUND(AVG(r.rating),1) as avg_rating, COUNT(*) as total
             FROM event_reviews r JOIN events e ON r.event_id=e.id
             WHERE e.organiser_id=?",
            "i", [$orgId]
        );
        return $rows[0] ?? ['avg_rating'=>0,'total'=>0];
    }
}