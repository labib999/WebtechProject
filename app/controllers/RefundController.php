<?php
class RefundController extends Controller {

    private $refundModel;

    public function __construct() {
        $this->refundModel = new RefundModel();
    }

    public function index() {
        Auth::requireRole('organiser');
        $orgId  = Auth::userId();
        $filter = $_GET['status'] ?? 'pending';

        $requests     = $this->refundModel->getByOrganiser($orgId, $filter);
        $statusCounts = $this->refundModel->getStatusCounts($orgId);
        $success      = Session::getFlash('success');
        $error        = Session::getFlash('error');

        $this->view('organiser/refunds/queue',
            compact('requests','filter','statusCounts','success','error'));
    }

    public function approve() {
        Auth::requireRole('organiser');
        $reqId = (int)($_POST['request_id'] ?? 0);
        $orgId = Auth::userId();

        $req = $this->refundModel->getPendingById($reqId, $orgId);
        if (!$req) {
            $this->redirect('organiser/refunds'); return;
        }

        $this->refundModel->approve($reqId, $req['booking_id']);
        Session::setFlash('success', 'Refund approved. Booking marked as refunded.');
        $this->redirect('organiser/refunds');
    }

    public function reject() {
        Auth::requireRole('organiser');
        $reqId = (int)($_POST['request_id'] ?? 0);
        $note  = trim($_POST['organiser_note'] ?? '');
        $orgId = Auth::userId();

        $req = $this->refundModel->getPendingById($reqId, $orgId);
        if (!$req) {
            $this->redirect('organiser/refunds'); return;
        }

        $this->refundModel->reject($reqId, $note);
        Session::setFlash('success', 'Refund request rejected.');
        $this->redirect('organiser/refunds');
    }
}