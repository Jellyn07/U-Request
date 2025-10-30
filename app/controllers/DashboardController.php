<?php
// filepath: app/controllers/DashboardController.php
require_once __DIR__ . '/../models/DashboardModel.php';

class DashboardController {
    private $model;

    public function __construct() {
        $this->model = new DashboardModel();
    }

    public function getDashboardData($year) {
        $summary = $this->model->getSummary($year);
        $monthly = $this->model->getMonthlyRequests($year);
    
        $data['summary'] = [
            'total_rrequests'   => $summary['total_rrequests'] ?? 0,
            'total_vrequests'   => $summary['total_vrequests'] ?? 0,
            'totalgPersonnel'   => $summary['totalgPersonnel'] ?? 0,
            'totalDrivers'      => $summary['totalDrivers'] ?? 0,
            'total_vrequests_p' => $summary['total_vrequests_p'] ?? 0,
            'total_user'        => $summary['total_user'] ?? 0,
            'total_admin'       => $summary['total_admin'] ?? 0,
            'total_pending'     => $summary['total_pending'] ?? 0,
            'total_requests'    => $summary['total_requests'] ?? 0
        ];
    
        $data['monthly'] = $monthly;
    
        return $data;  // ✅ always return the cleaned structure
    }

    public function fetchRequestStatusData() {
        $data = $this->model->getVehicleRequestStatusCounts();
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function fetchVehicleUsageData() {
        $data = $this->model->getVehicleUsageData();
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function fetchBuildingRequestsData() {
        $data = $this->model->getBuildingRequestsData();
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function fetchWorkloadData() {
        $data = $this->model->getWorkloadData();
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function fetchRequestTypeData(){
        $year = date('Y');
        $data = $this->model->getRequestTypeCounts($year);
        echo json_encode($data);
    }

    public function fetchMonthlyRequestData(){
        $year = date('Y');
        $data = $this->model->getMonthlyRequestCounts($year);
        echo json_encode($data);
    }

    public function getProfile($admin_email)
    {
        return $this->model->getProfileByEmail($admin_email);
    }
    
}
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $controller = new DashboardController();

    if (isset($_GET['building_requests'])) {
        $controller->fetchBuildingRequestsData();
        exit;
    }

    if (isset($_GET['request_status'])) {
        $controller->fetchRequestStatusData();
        exit;
    }

    if (isset($_GET['workload_data'])) {
        $controller->fetchWorkloadData();
        exit;
    }

    if (isset($_GET['vehicle_usage'])) {
        $controller->fetchVehicleUsageData();
        exit;
    }

    if (isset($_GET['request_type'])) {
        $controller->fetchRequestTypeData();
        exit;
    }

    if (isset($_GET['monthly_requests'])) {
        $controller->fetchMonthlyRequestData();
        exit;
    }
}
