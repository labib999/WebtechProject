<?php
class AnnouncementController extends Controller {

    public function index() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

        $myEvents = $db->query(
            "SELECT id, title FROM events
             WHERE organiser_id=? AND status='published'
             ORDER BY event_datetime DESC",
            "i", [$orgId]
        );

        $history = $db->query(
            "SELECT a.*, e.title as event_title,
                    (SELECT COUNT(*) FROM bookings b
                     WHERE b.event_id=a.event_id AND b.status='active') as recipient_count
             FROM announcements a
             JOIN events e ON a.event_id=e.id
             WHERE e.organiser_id=?
             ORDER BY a.sent_at DESC",
            "i", [$orgId]
        );

        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');
        $this->view('organiser/announcements/compose',
            compact('myEvents','history','success','error'));
    }

    public function send() {
        Auth::requireRole('organiser');
        $orgId   = Auth::userId();
        $eventId = (int)($_POST['event_id'] ?? 0);
        $title   = trim($_POST['title']     ?? '');
        $body    = trim($_POST['body']       ?? '');

        if (empty($title) || empty($body)) {
            Session::setFlash('error', 'Title and message body are required.');
            $this->redirect('organiser/announcements'); return;
        }

        $db = Database::getInstance();
        $ev = $db->query(
            "SELECT id FROM events WHERE id=? AND organiser_id=? AND status='published'",
            "ii", [$eventId, $orgId]
        );
        if (empty($ev)) {
            Session::setFlash('error', 'Invalid event selected.');
            $this->redirect('organiser/announcements'); return;
        }

        // Count recipients
        $rcpt = $db->query(
            "SELECT COUNT(*) as cnt FROM bookings WHERE event_id=? AND status='active'",
            "i", [$eventId]
        );
        $count = (int)($rcpt[0]['cnt'] ?? 0);

        $db->execute(
            "INSERT INTO announcements (event_id, organiser_id, title, body)
             VALUES (?,?,?,?)",
            "iiss", [$eventId, $orgId, $title, $body]
        );

        Session::setFlash('success',
            'Announcement sent to ' . $count . ' ticket holder' . ($count!=1?'s':'') . '.');
        $this->redirect('organiser/announcements');
    }
}