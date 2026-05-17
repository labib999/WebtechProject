<?php
class AnnouncementController extends Controller {

    private $announcementModel;

    public function __construct() {
        $this->announcementModel = new AnnouncementModel();
    }

    public function index() {
        Auth::requireRole('organiser');
        $orgId = Auth::userId();

        $history  = $this->announcementModel->getByOrganiser($orgId);
        $myEvents = $this->announcementModel->getPublishedEvents($orgId);
        $success  = Session::getFlash('success');
        $error    = Session::getFlash('error');

        $this->view('organiser/announcements/compose',
            compact('myEvents','history','success','error'));
    }

    public function send() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $eventId = (int)($_POST['event_id'] ?? 0);
        $title   = trim($_POST['title']     ?? '');
        $body    = trim($_POST['body']      ?? '');

        if (empty($title) || empty($body)) {
            Session::setFlash('error', 'Title and message body are required.');
            $this->redirect('organiser/announcements'); return;
        }

        if (!$this->announcementModel->eventBelongsToOrganiser($eventId, $orgId)) {
            Session::setFlash('error', 'Invalid event selected.');
            $this->redirect('organiser/announcements'); return;
        }

        $count = $this->announcementModel->getRecipientCount($eventId);
        $this->announcementModel->create($eventId, $orgId, $title, $body);

        Session::setFlash('success',
            'Announcement sent to ' . $count . ' ticket holder' . ($count != 1 ? 's' : '') . '.');
        $this->redirect('organiser/announcements');
    }
}