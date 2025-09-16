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
            'total_rrequests' => $summary['total_rrequests'] ?? 0,
            'total_vrequests' => $summary['total_vrequests'] ?? 0,
            'totalgPersonnel' => $summary['totalgPersonnel'] ?? 0,
            'totalDrivers'    => $summary['totalDrivers'] ?? 0,
            'total_user'      => $summary['total_user'] ?? 0,
        ];
    
        $data['monthly'] = $monthly;
    
        return $data;  // ✅ always return the cleaned structure
    }
    
}
