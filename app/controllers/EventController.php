<?php
class EventController extends Controller {

    // List all events
    public function index() {
        Auth::requireRole('organiser');
        $db     = Database::getInstance();
        $orgId  = Auth::userId();
        $filter = $_GET['status'] ?? 'all';

        $sql = "SELECT e.*, c.name as category_name,
                       COUNT(DISTINCT b.id) as bookings_count,
                       COALESCE(SUM(CASE WHEN b.status='active' THEN b.total_price ELSE 0 END),0) as revenue
                FROM events e
                LEFT JOIN categories c  ON e.category_id = c.id
                LEFT JOIN bookings b    ON b.event_id    = e.id
                WHERE e.organiser_id = ?";

        $params = [$orgId];
        if ($filter !== 'all') {
            $sql   .= " AND e.status = ?";
            $params[] = $filter;
            $types  = "is";
        } else {
            $types = "i";
        }
        $sql .= " GROUP BY e.id ORDER BY e.created_at DESC";

        $events = $db->query($sql, $types, $params);

        $counts = $db->query(
            "SELECT status, COUNT(*) as cnt FROM events WHERE organiser_id=? GROUP BY status",
            "i", [$orgId]
        );
        $statusCounts = ['all' => 0];
        foreach ($counts as $row) {
            $statusCounts[$row['status']] = (int)$row['cnt'];
            $statusCounts['all'] += (int)$row['cnt'];
        }

        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');
        $this->view('organiser/events/list',
            compact('events','filter','statusCounts','success','error'));
    }

    // Show create form
    public function create() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

        $categories    = $db->query("SELECT * FROM categories ORDER BY name");
        $venueBookings = $db->query(
            "SELECT vbr.*, v.name as venue_name, v.city
             FROM venue_booking_requests vbr
             JOIN venues v ON vbr.venue_id = v.id
             WHERE vbr.organiser_id = ? AND vbr.status = 'approved'",
            "i", [$orgId]
        );
        $error = Session::getFlash('error');
        $this->view('organiser/events/create',
            compact('categories','venueBookings','error'));
    }

    // Save new event
    public function store() {
        Auth::requireRole('organiser');
        $orgId       = Auth::userId();
        $title       = trim($_POST['title']               ?? '');
        $description = trim($_POST['description']         ?? '');
        $categoryId  = (int)($_POST['category_id']        ?? 0);
        $venueOpt    = $_POST['venue_option']             ?? 'custom';
        $venueId     = (int)($_POST['venue_id']           ?? 0);
        $customVenue = trim($_POST['venue_name_override'] ?? '');
        $eventDate   = trim($_POST['event_datetime']      ?? '');
        $endDate     = trim($_POST['end_datetime']        ?? '');
        $action      = $_POST['action']                   ?? 'draft';

        if (empty($title)) {
            Session::setFlash('error', 'Event title is required.');
            $this->redirect('organiser/events/create'); return;
        }
        if (empty($eventDate) || empty($endDate)) {
            Session::setFlash('error', 'Start and end date/time are required.');
            $this->redirect('organiser/events/create'); return;
        }
        if (strtotime($endDate) <= strtotime($eventDate)) {
            Session::setFlash('error', 'End time must be after start time.');
            $this->redirect('organiser/events/create'); return;
        }
        if ($venueOpt === 'custom' && empty($customVenue)) {
            Session::setFlash('error', 'Please enter a venue name.');
            $this->redirect('organiser/events/create'); return;
        }

        // Banner upload
        $bannerPath = null;
        if (!empty($_FILES['banner']['name'])) {
            $allowed  = ['image/jpeg','image/png','image/webp'];
            $mimeType = mime_content_type($_FILES['banner']['tmp_name']);
            if (!in_array($mimeType, $allowed) || $_FILES['banner']['size'] > 2097152) {
                Session::setFlash('error', 'Banner must be JPG/PNG/WebP under 2 MB.');
                $this->redirect('organiser/events/create'); return;
            }
            $ext  = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
            $name = uniqid('banner_') . '.' . $ext;
            $dir  = __DIR__ . '/../../public/assets/uploads/banners/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            move_uploaded_file($_FILES['banner']['tmp_name'], $dir . $name);
            $bannerPath = 'assets/uploads/banners/' . $name;
        }

        $status = ($action === 'publish') ? 'published' : 'draft';
        $catId  = $categoryId > 0 ? $categoryId : null;
        $vId    = ($venueOpt === 'booked' && $venueId > 0) ? $venueId : null;
        $cv     = ($venueOpt === 'custom') ? $customVenue : null;

        $db = Database::getInstance();
        $db->execute(
            "INSERT INTO events
             (organiser_id,venue_id,category_id,title,description,
              venue_name_override,event_datetime,end_datetime,banner_image_path,status)
             VALUES (?,?,?,?,?,?,?,?,?,?)",
            "iissssssss",
            [$orgId,$vId,$catId,$title,$description,$cv,$eventDate,$endDate,$bannerPath,$status]
        );

        Session::setFlash('success',
            'Event ' . ($status==='published' ? 'published' : 'saved as draft') . ' successfully. Now add ticket tiers.');
        $this->redirect('organiser/events');
    }

    // Publish a draft event
    public function publish() {
        Auth::requireRole('organiser');
        $eventId = (int)($_POST['event_id'] ?? 0);
        $orgId   = Auth::userId();
        $db      = Database::getInstance();

        $tiers = $db->query(
            "SELECT COUNT(*) as cnt FROM ticket_tiers t
             JOIN events e ON t.event_id = e.id
             WHERE e.id = ? AND e.organiser_id = ?",
            "ii", [$eventId, $orgId]
        );
        if (($tiers[0]['cnt'] ?? 0) == 0) {
            Session::setFlash('error', 'Add at least one ticket tier before publishing.');
            $this->redirect('organiser/events'); return;
        }
        $db->execute(
            "UPDATE events SET status='published' WHERE id=? AND organiser_id=?",
            "ii", [$eventId, $orgId]
        );
        Session::setFlash('success', 'Event published successfully.');
        $this->redirect('organiser/events');
    }

    // Cancel an event
    public function cancel() {
        Auth::requireRole('organiser');
        $eventId = (int)($_POST['event_id'] ?? 0);
        $orgId   = Auth::userId();
        $db      = Database::getInstance();
        $db->execute(
            "UPDATE events SET status='cancelled' WHERE id=? AND organiser_id=?",
            "ii", [$eventId, $orgId]
        );
        Session::setFlash('success', 'Event cancelled.');
        $this->redirect('organiser/events');
    }

    // Show edit form
    public function edit() {
        Auth::requireRole('organiser');
        $eventId = (int)($_GET['id'] ?? 0);
        $orgId   = Auth::userId();
        $db      = Database::getInstance();

        $rows = $db->query(
            "SELECT * FROM events WHERE id=? AND organiser_id=?",
            "ii", [$eventId, $orgId]
        );
        if (empty($rows)) { $this->redirect('organiser/events'); return; }

        $event      = $rows[0];
        $categories = $db->query("SELECT * FROM categories ORDER BY name");
        $error      = Session::getFlash('error');
        $this->view('organiser/events/edit', compact('event','categories','error'));
    }

    // Save edit
    public function update() {
        Auth::requireRole('organiser');
        $eventId     = (int)($_POST['event_id']          ?? 0);
        $orgId       = Auth::userId();
        $title       = trim($_POST['title']               ?? '');
        $description = trim($_POST['description']         ?? '');
        $categoryId  = (int)($_POST['category_id']        ?? 0);
        $customVenue = trim($_POST['venue_name_override'] ?? '');
        $eventDate   = trim($_POST['event_datetime']      ?? '');
        $endDate     = trim($_POST['end_datetime']        ?? '');

        if (empty($title) || empty($eventDate) || empty($endDate)) {
            Session::setFlash('error', 'Title and dates are required.');
            $this->redirect('organiser/events/edit?id='.$eventId); return;
        }

        $db   = Database::getInstance();
        $rows = $db->query(
            "SELECT banner_image_path FROM events WHERE id=? AND organiser_id=?",
            "ii", [$eventId, $orgId]
        );
        if (empty($rows)) { $this->redirect('organiser/events'); return; }

        $bannerPath = $rows[0]['banner_image_path'];
        if (!empty($_FILES['banner']['name'])) {
            $allowed  = ['image/jpeg','image/png','image/webp'];
            $mimeType = mime_content_type($_FILES['banner']['tmp_name']);
            if (in_array($mimeType,$allowed) && $_FILES['banner']['size'] <= 2097152) {
                $ext  = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
                $name = uniqid('banner_') . '.' . $ext;
                $dir  = __DIR__ . '/../../public/assets/uploads/banners/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                move_uploaded_file($_FILES['banner']['tmp_name'], $dir . $name);
                $bannerPath = 'assets/uploads/banners/' . $name;
            }
        }

        $catId = $categoryId > 0 ? $categoryId : null;
        $db->execute(
            "UPDATE events SET title=?,description=?,category_id=?,
             venue_name_override=?,event_datetime=?,end_datetime=?,banner_image_path=?
             WHERE id=? AND organiser_id=?",
            "sssisssii",
            [$title,$description,$catId,$customVenue?:null,$eventDate,$endDate,$bannerPath,$eventId,$orgId]
        );
        Session::setFlash('success', 'Event updated.');
        $this->redirect('organiser/events');
    }
}