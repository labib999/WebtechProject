<?php
class DiscountController extends Controller {

    public function index() {
        Auth::requireRole('organiser');
        $db    = Database::getInstance();
        $orgId = Auth::userId();

        $myEvents = $db->query(
            "SELECT id, title FROM events WHERE organiser_id=? AND status='published' ORDER BY event_datetime DESC",
            "i", [$orgId]
        );

        $codes = $db->query(
            "SELECT d.*, e.title as event_title
             FROM discount_codes d JOIN events e ON d.event_id=e.id
             WHERE e.organiser_id=? ORDER BY d.id DESC",
            "i", [$orgId]
        );

        $success = Session::getFlash('success');
        $error   = Session::getFlash('error');
        $this->view('organiser/discounts/manage',
            compact('codes','myEvents','success','error'));
    }

    public function store() {
        Auth::requireRole('organiser');
        $orgId       = Auth::userId();
        $eventId     = (int)($_POST['event_id']      ?? 0);
        $code        = strtoupper(trim($_POST['code'] ?? ''));
        $pct         = (float)($_POST['discount_pct'] ?? 0);
        $maxUses     = trim($_POST['max_uses']        ?? '') ?: null;
        $validUntil  = trim($_POST['valid_until']     ?? '') ?: null;

        if (empty($code) || $pct <= 0 || $pct > 100) {
            Session::setFlash('error', 'Code and a valid discount percentage (1-100) are required.');
            $this->redirect('organiser/discounts'); return;
        }

        $db = Database::getInstance();
        $ev = $db->query("SELECT id FROM events WHERE id=? AND organiser_id=?", "ii", [$eventId, $orgId]);
        if (empty($ev)) {
            Session::setFlash('error', 'Invalid event selected.');
            $this->redirect('organiser/discounts'); return;
        }

        $exists = $db->query("SELECT id FROM discount_codes WHERE code=?", "s", [$code]);
        if (!empty($exists)) {
            Session::setFlash('error', 'Code "' . $code . '" already exists. Choose a different code.');
            $this->redirect('organiser/discounts'); return;
        }

        $db->execute(
            "INSERT INTO discount_codes (event_id,organiser_id,code,discount_pct,max_uses,valid_until,is_active)
             VALUES (?,?,?,?,?,?,1)",
            "iisdss",
            [$eventId, $orgId, $code, $pct, $maxUses, $validUntil]
        );
        Session::setFlash('success', 'Discount code "' . $code . '" created.');
        $this->redirect('organiser/discounts');
    }

    public function toggle() {
        Auth::requireRole('organiser');
        $codeId = (int)($_POST['code_id'] ?? 0);
        $orgId  = Auth::userId();
        $db     = Database::getInstance();

        $rows = $db->query(
            "SELECT d.id, d.is_active FROM discount_codes d
             JOIN events e ON d.event_id=e.id
             WHERE d.id=? AND e.organiser_id=?",
            "ii", [$codeId, $orgId]
        );
        if (empty($rows)) { $this->redirect('organiser/discounts'); return; }

        $newStatus = $rows[0]['is_active'] ? 0 : 1;
        $db->execute("UPDATE discount_codes SET is_active=? WHERE id=?", "ii", [$newStatus, $codeId]);
        Session::setFlash('success', 'Code ' . ($newStatus ? 'activated' : 'deactivated') . '.');
        $this->redirect('organiser/discounts');
    }
}