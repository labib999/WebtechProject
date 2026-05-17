<?php
class AnalyticsController extends Controller {

    private $analyticsModel;

    public function __construct() {
        $this->analyticsModel = new AnalyticsModel();
    }

    public function index() {
        Auth::requireRole('organiser');
        $orgId  = Auth::userId();
        $period = (int)($_GET['days'] ?? 30);
        if (!in_array($period, [7,30,90])) $period = 30;

        $kpi        = $this->analyticsModel->getKpis($orgId);
        $salesChart = $this->analyticsModel->getSalesChart($orgId, $period);
        $tierChart  = $this->analyticsModel->getTierChart($orgId);
        $eventChart = $this->analyticsModel->getEventChart($orgId);
        $hourChart  = $this->analyticsModel->getHourChart($orgId);
        $ratingData = $this->analyticsModel->getRatingStats($orgId);

        $this->view('organiser/analytics/dashboard',
            compact('kpi','salesChart','tierChart','eventChart',
                    'hourChart','ratingData','period'));
    }
}