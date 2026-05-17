<?php
class TierController extends Controller {

    private $tierModel;

    public function __construct() {
        $this->tierModel = new TierModel();
    }

    public function index() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $eventId = (int)($_GET['event_id'] ?? 0);

        if ($eventId === 0) {
            $this->redirect('organiser/events'); return;
        }

        // Verify event belongs to organiser
        $eventModel = new EventModel();
        $event = $eventModel->getById($eventId, $orgId);
        if (!$event) {
            Session::setFlash('error', 'Event not found.');
            $this->redirect('organiser/events'); return;
        }

        $tiers   = $this->tierModel->getByEvent($eventId);
        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');

        $this->view('organiser/tiers/manage',
            compact('event','tiers','success','error'));
    }

    public function store() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $eventId = (int)($_POST['event_id'] ?? 0);
        $name    = trim($_POST['name']      ?? '');
        $price   = (float)($_POST['price']  ?? 0);
        $seats   = (int)($_POST['total_seats'] ?? 0);

        if (empty($name) || $price < 0 || $seats < 1) {
            Session::setFlash('error', 'Please fill all tier fields correctly.');
            $this->redirect('organiser/tiers?event_id='.$eventId); return;
        }

        // Verify event belongs to organiser
        $eventModel = new EventModel();
        $event = $eventModel->getById($eventId, $orgId);
        if (!$event) {
            Session::setFlash('error', 'Invalid event.');
            $this->redirect('organiser/events'); return;
        }

        $this->tierModel->create($eventId, $name, $price, $seats);
        Session::setFlash('success', 'Ticket tier "' . $name . '" added.');
        $this->redirect('organiser/tiers?event_id='.$eventId);
    }

    public function delete() {
        Auth::requireRole('organiser');
        $orgId  = Auth::userId();
        $tierId = (int)($_POST['tier_id']  ?? 0);
        $eventId= (int)($_POST['event_id'] ?? 0);

        if (!$this->tierModel->belongsToOrganiser($tierId, $orgId)) {
            Session::setFlash('error', 'Tier not found.');
            $this->redirect('organiser/tiers?event_id='.$eventId); return;
        }

        if ($this->tierModel->hasSales($tierId)) {
            Session::setFlash('error', 'Cannot delete a tier that already has ticket sales.');
            $this->redirect('organiser/tiers?event_id='.$eventId); return;
        }

        $this->tierModel->delete($tierId, $orgId);
        Session::setFlash('success', 'Tier deleted.');
        $this->redirect('organiser/tiers?event_id='.$eventId);
    }
}