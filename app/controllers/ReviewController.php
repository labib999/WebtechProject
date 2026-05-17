<?php
class ReviewController extends Controller {

    private $reviewModel;

    public function __construct() {
        $this->reviewModel = new ReviewModel();
    }

    public function index() {
        Auth::requireRole('organiser');
        $orgId  = Auth::userId();
        $filter = $_GET['rating'] ?? 'all';

        $reviews      = $this->reviewModel->getByOrganiser($orgId, $filter);
        $stats        = $this->reviewModel->getStats($orgId);
        $avgRating    = $stats['avg_rating'];
        $totalReviews = $stats['total'];
        $success      = Session::getFlash('success');

        $this->view('organiser/reviews/list',
            compact('reviews','filter','avgRating','totalReviews','success'));
    }

    public function reply() {
        Auth::requireRole('organiser');
        $reviewId = (int)($_POST['review_id'] ?? 0);
        $reply    = trim($_POST['reply']      ?? '');
        $orgId    = Auth::userId();

        if (empty($reply)) {
            Session::setFlash('error', 'Reply cannot be empty.');
            $this->redirect('organiser/reviews'); return;
        }

        if (!$this->reviewModel->belongsToOrganiser($reviewId, $orgId)) {
            $this->redirect('organiser/reviews'); return;
        }

        $this->reviewModel->saveReply($reviewId, $reply);
        Session::setFlash('success', 'Reply posted successfully.');
        $this->redirect('organiser/reviews');
    }
}