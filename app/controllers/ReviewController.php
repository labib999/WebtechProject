<?php
class ReviewController extends Controller {

    public function index() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

        $filter  = $_GET['rating'] ?? 'all';
        $sql     = "SELECT r.*, u.name as attendee_name,
                           e.title as event_title
                    FROM event_reviews r
                    JOIN users u  ON r.attendee_id = u.id
                    JOIN events e ON r.event_id    = e.id
                    WHERE e.organiser_id = ?";
        $params  = [$orgId];
        $types   = "i";

        if ($filter !== 'all' && is_numeric($filter)) {
            $sql .= " AND r.rating = ?"; $params[] = (int)$filter; $types .= "i";
        }
        $sql .= " ORDER BY r.created_at DESC";

        $reviews = $db->query($sql, $types, $params);

        $avgRow  = $db->query(
            "SELECT ROUND(AVG(r.rating),1) as avg, COUNT(*) as total
             FROM event_reviews r JOIN events e ON r.event_id=e.id
             WHERE e.organiser_id=?", "i", [$orgId]
        );
        $avgRating   = $avgRow[0]['avg']   ?? 0;
        $totalReviews= $avgRow[0]['total'] ?? 0;

        $success = Session::getFlash('success');
        $this->view('organiser/reviews/list',
            compact('reviews','filter','avgRating','totalReviews','success'));
    }

    public function reply() {
        Auth::requireRole('organiser');
        $reviewId = (int)($_POST['review_id'] ?? 0);
        $reply    = trim($_POST['reply']      ?? '');
        $orgId    = Auth::userId();
        $db       = Database::getInstance();

        if (empty($reply)) {
            Session::setFlash('error', 'Reply cannot be empty.');
            $this->redirect('organiser/reviews'); return;
        }

        $rows = $db->query(
            "SELECT r.id FROM event_reviews r
             JOIN events e ON r.event_id=e.id
             WHERE r.id=? AND e.organiser_id=?",
            "ii", [$reviewId, $orgId]
        );
        if (empty($rows)) { $this->redirect('organiser/reviews'); return; }

        $db->execute(
            "UPDATE event_reviews SET organiser_reply=? WHERE id=?",
            "si", [$reply, $reviewId]
        );
        Session::setFlash('success', 'Reply posted successfully.');
        $this->redirect('organiser/reviews');
    }
}