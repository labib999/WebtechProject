<?php
class OrganiserController extends Controller {

    public function dashboard() {
        Auth::requireRole('organiser');

        $db    = Database::getInstance();
        $orgId = Auth::userId();

        // KPI: total events
        $eventsRow = $db->query(
            "SELECT COUNT(*) as total, SUM(status='published') as published
             FROM events WHERE organiser_id = ?", "i", [$orgId]
        );
        $totalEvents     = $eventsRow[0]['total']     ?? 0;
        $publishedEvents = $eventsRow[0]['published'] ?? 0;

        // KPI: tickets sold + revenue
        $salesRow = $db->query(
            "SELECT COALESCE(SUM(b.quantity),0)    as tickets_sold,
                    COALESCE(SUM(b.total_price),0) as revenue
             FROM bookings b JOIN events e ON b.event_id = e.id
             WHERE e.organiser_id = ? AND b.status = 'active'", "i", [$orgId]
        );
        $ticketsSold  = $salesRow[0]['tickets_sold'] ?? 0;
        $totalRevenue = $salesRow[0]['revenue']      ?? 0;

        // KPI: check-in stats
        $ciRow = $db->query(
            "SELECT COUNT(*) as total, COALESCE(SUM(checked_in),0) as checked_in
             FROM bookings b JOIN events e ON b.event_id = e.id
             WHERE e.organiser_id = ? AND b.status = 'active'", "i", [$orgId]
        );
        $totalActive    = $ciRow[0]['total']      ?? 0;
        $totalCheckedIn = $ciRow[0]['checked_in'] ?? 0;
        $checkinRate    = $totalActive > 0
            ? round(($totalCheckedIn / $totalActive) * 100, 1) : 0;

        // KPI: average rating
        $ratingRow = $db->query(
            "SELECT ROUND(AVG(r.rating),1) as avg_rating, COUNT(*) as review_count
             FROM event_reviews r JOIN events e ON r.event_id = e.id
             WHERE e.organiser_id = ?", "i", [$orgId]
        );
        $avgRating   = $ratingRow[0]['avg_rating']   ?? 0;
        $reviewCount = $ratingRow[0]['review_count'] ?? 0;

        // Pending refunds badge count
        $refundRow = $db->query(
            "SELECT COUNT(*) as total
             FROM refund_requests rr
             JOIN bookings b ON rr.booking_id = b.id
             JOIN events e   ON b.event_id   = e.id
             WHERE e.organiser_id = ? AND rr.status = 'pending'", "i", [$orgId]
        );
        $pendingRefunds = $refundRow[0]['total'] ?? 0;

        // Recent bookings (latest 6)
        $recentBookings = $db->query(
            "SELECT b.ticket_code, b.total_price, b.checked_in,
                    b.status, b.created_at,
                    u.name as attendee_name,
                    e.title as event_title,
                    t.name  as tier_name
             FROM bookings b
             JOIN users u        ON b.attendee_id = u.id
             JOIN events e       ON b.event_id    = e.id
             JOIN ticket_tiers t ON b.tier_id     = t.id
             WHERE e.organiser_id = ?
             ORDER BY b.created_at DESC LIMIT 6", "i", [$orgId]
        );

        // Chart: sales last 7 days
        $salesChart = $db->query(
            "SELECT DATE(b.created_at) as sale_date,
                    COUNT(*)           as tickets,
                    SUM(b.total_price) as revenue
             FROM bookings b JOIN events e ON b.event_id = e.id
             WHERE e.organiser_id = ?
               AND b.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY DATE(b.created_at)
             ORDER BY sale_date ASC", "i", [$orgId]
        );

        // Chart: revenue by tier
        $tierChart = $db->query(
            "SELECT t.name as tier_name,
                    COALESCE(SUM(b.total_price),0) as revenue,
                    COUNT(b.id)                    as sold,
                    t.total_seats
             FROM ticket_tiers t
             LEFT JOIN bookings b ON b.tier_id = t.id AND b.status != 'refunded'
             JOIN events e ON t.event_id = e.id
             WHERE e.organiser_id = ? AND e.status = 'published'
             GROUP BY t.id, t.name, t.total_seats
             ORDER BY revenue DESC", "i", [$orgId]
        );

        // My events list
        $myEvents = $db->query(
            "SELECT e.id, e.title, e.status, e.event_datetime,
                    COUNT(DISTINCT b.id) as bookings_count
             FROM events e
             LEFT JOIN bookings b ON b.event_id = e.id AND b.status != 'refunded'
             WHERE e.organiser_id = ?
             GROUP BY e.id ORDER BY e.created_at DESC LIMIT 5", "i", [$orgId]
        );

        // Greeting based on Bangladesh time
        date_default_timezone_set('Asia/Dhaka');
        $hour = (int)date('H');
        if ($hour >= 5 && $hour < 12)      $greeting = 'Good morning';
        elseif ($hour >= 12 && $hour < 17) $greeting = 'Good afternoon';
        elseif ($hour >= 17 && $hour < 21) $greeting = 'Good evening';
        else                               $greeting = 'Good night';

        $this->view('organiser/dashboard', compact(
            'totalEvents', 'publishedEvents', 'ticketsSold', 'totalRevenue',
            'totalCheckedIn', 'totalActive', 'checkinRate',
            'avgRating', 'reviewCount', 'pendingRefunds',
            'recentBookings', 'salesChart', 'tierChart', 'myEvents',
            'greeting'
        ));
    }
}