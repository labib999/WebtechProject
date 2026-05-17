<?php
class VenueController extends Controller {

    private $venueModel;

    public function __construct() {
        $this->venueModel = new VenueModel();
    }

    public function browse() {
        Auth::requireRole('organiser');

        $city   = trim($_GET['city']     ?? '');
        $minCap = (int)($_GET['min_cap'] ?? 0);

        $venues  = $this->venueModel->getAll($city, $minCap);
        $cities  = $this->venueModel->getCities();
        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');

        $this->view('organiser/venues/browse',
            compact('venues','cities','city','minCap','success','error'));
    }

    public function request() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $venueId = (int)($_POST['venue_id']            ?? 0);
        $dates   = trim($_POST['requested_dates']      ?? '');
        $preview = trim($_POST['event_title_preview']  ?? '');
        $message = trim($_POST['message']              ?? '');

        if ($venueId === 0 || empty($dates)) {
            Session::setFlash('error', 'Please select a venue and enter at least one date.');
            $this->redirect('organiser/venues'); return;
        }

        if (!$this->venueModel->getById($venueId)) {
            Session::setFlash('error', 'Invalid venue selected.');
            $this->redirect('organiser/venues'); return;
        }

        $datesArr = array_values(array_filter(
            array_map('trim', explode(',', $dates))
        ));

        $this->venueModel->createRequest(
            $venueId, $orgId, $preview,
            json_encode($datesArr), $message
        );

        Session::setFlash('success', 'Venue booking request submitted successfully.');
        $this->redirect('organiser/venue-requests');
    }

    public function myRequests() {
        Auth::requireRole('organiser');
        $orgId    = Auth::userId();
        $requests = $this->venueModel->getRequestsByOrganiser($orgId);
        $success  = Session::getFlash('success');

        $this->view('organiser/venues/requests',
            compact('requests','success'));
    }
}