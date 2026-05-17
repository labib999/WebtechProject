<?php
class EventController extends Controller {

    private $eventModel;

    public function __construct() {
        $this->eventModel = new EventModel();
    }

    public function index() {
        Auth::requireRole('organiser');
        $orgId  = Auth::userId();
        $filter = $_GET['status'] ?? 'all';
        $search = trim($_GET['q']  ?? '');

        $events       = $this->eventModel->getAllByOrganiser($orgId, $filter, $search);
        $statusCounts = $this->eventModel->getStatusCounts($orgId);
        $success      = Session::getFlash('success');
        $error        = Session::getFlash('error');

        $this->view('organiser/events/list',
            compact('events','filter','statusCounts','success','error','search'));
    }

    public function create() {
        Auth::requireRole('organiser');
        $categories = $this->eventModel->getCategories();
        $myVenues   = $this->getApprovedVenues(Auth::userId());
        $error      = Session::getFlash('error');
        $this->view('organiser/events/create', compact('categories','myVenues','error'));
    }

    public function store() {
        Auth::requireRole('organiser');
        $orgId = Auth::userId();

        $title    = trim($_POST['title']              ?? '');
        $desc     = trim($_POST['description']        ?? '');
        $catId    = (int)($_POST['category_id']       ?? 0);
        $evDT     = trim($_POST['event_datetime']     ?? '');
        $endDT    = trim($_POST['end_datetime']       ?? '');
        $venueName= trim($_POST['venue_name_override']?? '');
        $venueAddr= trim($_POST['venue_address']      ?? '');
        $venueCity= trim($_POST['venue_city']         ?? '');
        $capacity = (int)($_POST['max_capacity']      ?? 0);

        if (empty($title) || empty($evDT) || empty($endDT)) {
            Session::setFlash('error', 'Title and dates are required.');
            $this->redirect('organiser/events/create'); return;
        }

        $bannerPath = null;
        if (!empty($_FILES['banner']['name'])) {
            $bannerPath = $this->uploadBanner($_FILES['banner']);
        }

        $this->eventModel->create($orgId, compact(
            'title','desc','catId','evDT','endDT',
            'venueName','venueAddr','venueCity','capacity'
        ), $bannerPath);

        Session::setFlash('success', 'Event created as draft.');
        $this->redirect('organiser/events');
    }

    public function edit() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $eventId = (int)($_GET['id'] ?? 0);
        $event   = $this->eventModel->getById($eventId, $orgId);

        if (!$event) {
            Session::setFlash('error', 'Event not found.');
            $this->redirect('organiser/events'); return;
        }

        $categories = $this->eventModel->getCategories();
        $myVenues   = $this->getApprovedVenues($orgId);
        $error      = Session::getFlash('error');
        $this->view('organiser/events/edit',
            compact('event','categories','myVenues','error'));
    }

    public function update() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $eventId = (int)($_POST['event_id'] ?? 0);

        $data = [
            'title'               => trim($_POST['title']               ?? ''),
            'description'         => trim($_POST['description']         ?? ''),
            'category_id'         => (int)($_POST['category_id']        ?? 0),
            'event_datetime'      => trim($_POST['event_datetime']      ?? ''),
            'end_datetime'        => trim($_POST['end_datetime']        ?? ''),
            'venue_name_override' => trim($_POST['venue_name_override'] ?? ''),
            'venue_address'       => trim($_POST['venue_address']       ?? ''),
            'venue_city'          => trim($_POST['venue_city']          ?? ''),
            'max_capacity'        => (int)($_POST['max_capacity']       ?? 0),
        ];

        if (empty($data['title'])) {
            Session::setFlash('error', 'Title is required.');
            $this->redirect('organiser/events/edit?id='.$eventId); return;
        }

        $bannerPath = null;
        if (!empty($_FILES['banner']['name'])) {
            $bannerPath = $this->uploadBanner($_FILES['banner']);
        }

        $this->eventModel->update($eventId, $orgId, $data, $bannerPath);
        Session::setFlash('success', 'Event updated successfully.');
        $this->redirect('organiser/events');
    }

    public function publish() {
        Auth::requireRole('organiser');
        $eventId = (int)($_POST['event_id'] ?? 0);
        $this->eventModel->publish($eventId, Auth::userId());
        Session::setFlash('success', 'Event published successfully.');
        $this->redirect('organiser/events');
    }

    public function cancel() {
        Auth::requireRole('organiser');
        $eventId = (int)($_POST['event_id'] ?? 0);
        $this->eventModel->cancel($eventId, Auth::userId());
        Session::setFlash('success', 'Event cancelled.');
        $this->redirect('organiser/events');
    }

    private function uploadBanner($file) {
        $allowed = ['image/jpeg','image/png','image/webp'];
        if (!in_array(mime_content_type($file['tmp_name']), $allowed)) return null;
        if ($file['size'] > 2097152) return null;
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = uniqid('banner_') . '.' . $ext;
        $dir  = __DIR__ . '/../public/assets/uploads/banners/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        move_uploaded_file($file['tmp_name'], $dir . $name);
        return 'assets/uploads/banners/' . $name;
    }

    private function getApprovedVenues($orgId) {
        $db = Database::getInstance();
        return $db->query(
            "SELECT vbr.id, v.name, v.city FROM venue_booking_requests vbr
             JOIN venues v ON vbr.venue_id=v.id
             WHERE vbr.organiser_id=? AND vbr.status='approved'",
            "i", [$orgId]
        );
    }
}