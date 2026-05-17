<?php
class BookingController extends Controller {

    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new BookingModel();
    }

    public function index() {
        Auth::requireRole('organiser');
        $orgId = Auth::userId();

        $filters = [
            'event_id'   => (int)($_GET['event_id']  ?? 0),
            'status'     => $_GET['status']           ?? 'all',
            'checked_in' => $_GET['checked_in']       ?? 'all',
            'search'     => trim($_GET['q']           ?? ''),
        ];

        $bookings     = $this->bookingModel->getByOrganiser($orgId, $filters);
        $totalRevenue = $this->bookingModel->getTotalRevenue($bookings);

        $eventModel = new EventModel();
        $myEvents   = $eventModel->getPublished($orgId);

        $error = Session::getFlash('error');

        $this->view('organiser/bookings/list', [
            'bookings'      => $bookings,
            'myEvents'      => $myEvents,
            'totalRevenue'  => $totalRevenue,
            'eventFilter'   => $filters['event_id'],
            'statusFilter'  => $filters['status'],
            'checkinFilter' => $filters['checked_in'],
            'search'        => $filters['search'],
            'error'         => $error,
        ]);
    }
}