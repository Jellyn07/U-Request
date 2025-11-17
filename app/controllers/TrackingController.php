<?php
require_once __DIR__ . '/../models/TrackingModel.php';

class TrackingController {
    private $model;

    public function __construct() {
        $this->model = new TrackingModel();
    }

    public function listTracking($email) {
        return $this->model->getTrackingByEmail($email);
    }

    public function listRepairTracking($email) {
        return $this->model->getRepairTrackingByEmail($email);
    }

    public function listVehicleTracking($email) {
        return $this->model->getVehicleTrackingByEmail($email);
    }

    public function viewDetails($tracking_id, $email) {
        return $this->model->getTrackingDetails($tracking_id, $email);
    }
    public function getTrackingDetails($trackingId, $email = null) {
        if ($email === null) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $email = $_SESSION['email'] ?? '';
        }
        return $this->model->getTrackingDetails($trackingId, $email);
    }

    public function getFilteredTracking($email, $type = 'repair', $statusFilter = '', $sort = 'newest') {
        // Fetch appropriate list
        $repairList = $this->model->getRepairTrackingByEmail($email);
        $vehicleList = $this->model->getVehicleTrackingByEmail($email);
        $list = ($type === 'vehicle') ? $vehicleList : $repairList;

        // --- Filter by status ---
        if (!empty($statusFilter)) {
            $list = array_filter($list, function($item) use ($statusFilter) {
                $itemStatus = $item['req_status'] ?? '';
                return strcasecmp($itemStatus, $statusFilter) === 0;
            });
        }

        // --- Sort ONLY by date_request ---
        usort($list, function ($a, $b) use ($sort) {
        $dateA = strtotime($a['date_request'] ?? $a['request_date'] ?? 0);
        $dateB = strtotime($b['date_request'] ?? $b['request_date'] ?? 0);

        return ($sort === 'oldest') 
            ? ($dateA - $dateB)
            : ($dateB - $dateA);
    });
        return $list;
    }
}
