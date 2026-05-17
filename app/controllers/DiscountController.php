<?php
class DiscountController extends Controller {

    private $discountModel;

    public function __construct() {
        $this->discountModel = new DiscountModel();
    }

    public function index() {
        Auth::requireRole('organiser');
        $orgId = Auth::userId();

        $codes    = $this->discountModel->getByOrganiser($orgId);
        $eventModel = new EventModel();
        $myEvents = $eventModel->getPublished($orgId);
        $success  = Session::getFlash('success');
        $error    = Session::getFlash('error');

        $this->view('organiser/discounts/manage',
            compact('codes','myEvents','success','error'));
    }

    public function store() {
        Auth::requireRole('organiser');
        $orgId      = Auth::userId();
        $eventId    = (int)($_POST['event_id']       ?? 0);
        $code       = strtoupper(trim($_POST['code'] ?? ''));
        $pct        = (float)($_POST['discount_pct'] ?? 0);
        $maxUses    = trim($_POST['max_uses']         ?? '') ?: null;
        $validUntil = trim($_POST['valid_until']      ?? '') ?: null;

        if (empty($code) || $pct <= 0 || $pct > 100) {
            Session::setFlash('error', 'Code and a valid discount % (1-100) are required.');
            $this->redirect('organiser/discounts'); return;
        }

        $eventModel = new EventModel();
        if (!$eventModel->getById($eventId, $orgId)) {
            Session::setFlash('error', 'Invalid event selected.');
            $this->redirect('organiser/discounts'); return;
        }

        if ($this->discountModel->codeExists($code)) {
            Session::setFlash('error', 'Code "' . $code . '" already exists.');
            $this->redirect('organiser/discounts'); return;
        }

        $this->discountModel->create($eventId, $orgId, $code, $pct, $maxUses, $validUntil);
        Session::setFlash('success', 'Discount code "' . $code . '" created.');
        $this->redirect('organiser/discounts');
    }

    public function toggle() {
        Auth::requireRole('organiser');
        $codeId = (int)($_POST['code_id'] ?? 0);
        $orgId  = Auth::userId();

        $code = $this->discountModel->getById($codeId, $orgId);
        if (!$code) {
            $this->redirect('organiser/discounts'); return;
        }

        $newStatus = $code['is_active'] ? 0 : 1;
        $this->discountModel->toggle($codeId, $newStatus);
        Session::setFlash('success', 'Code ' . ($newStatus ? 'activated' : 'deactivated') . '.');
        $this->redirect('organiser/discounts');
    }
}