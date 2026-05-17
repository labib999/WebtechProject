<?php
class CheckinController extends Controller {

    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new BookingModel();
    }

    public function scanner() {
        Auth::requireRole('organiser');
        $orgId = Auth::userId();

        $eventModel = new EventModel();
        $myEvents   = $eventModel->getPublished($orgId);

        $eventId = (int)($_GET['event_id'] ?? ($myEvents[0]['id'] ?? 0));
        $stats   = $this->bookingModel->getCheckinStats($eventId);
        $recentCheckins = $this->bookingModel->getRecentCheckins($eventId);

        $this->view('organiser/checkin/scanner',
            compact('myEvents','eventId','stats','recentCheckins'));
    }

    public function process() {
        Auth::requireRole('organiser');

        $input      = json_decode(file_get_contents('php://input'), true);
        $ticketCode = strtoupper(trim($input['ticket_code'] ?? ''));
        $eventId    = (int)($input['event_id'] ?? 0);

        if (empty($ticketCode) || $eventId === 0) {
            $this->json(['status'=>'error','message'=>'Invalid request.']); return;
        }

        $booking = $this->bookingModel->getByTicketCode($ticketCode, $eventId);

        if (!$booking) {
            $this->json(['status'=>'error','message'=>'Ticket code not found.']); return;
        }

        if ($booking['status'] !== 'active') {
            $this->json([
                'status'  => 'error',
                'message' => 'Ticket is ' . $booking['status'] . ' — cannot check in.'
            ]); return;
        }

        if ($booking['checked_in']) {
            date_default_timezone_set('Asia/Dhaka');
            $time = date('g:i A', strtotime($booking['checked_in_at']));
            $this->json([
                'status'  => 'used',
                'message' => 'Already checked in at ' . $time . '.'
            ]); return;
        }

        $this->bookingModel->checkIn($booking['id']);

        $stats = $this->bookingModel->getCheckinStats($eventId);

        date_default_timezone_set('Asia/Dhaka');
        $this->json([
            'status'           => 'ok',
            'message'          => 'Welcome, ' . $booking['attendee_name'] . '!',
            'attendee'         => $booking['attendee_name'],
            'tier'             => $booking['tier_name'],
            'checked_in_at'    => date('g:i A'),
            'checked_in_count' => (int)$stats['checked_in_count'],
            'total_sold'       => (int)$stats['total_sold'],
        ]);
    }
}