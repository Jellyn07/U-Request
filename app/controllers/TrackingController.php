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

    // public function getTrackingDetails($tracking_id, $email) {
    //     // Try repair first
    //     $repair = $this->model->getRepairTrackingDetails($tracking_id, $email);
    //     if ($repair) return $repair;
    
    //     // Otherwise, try vehicle
    //     $vehicle = $this->model->getVehicleTrackingDetails($tracking_id, $email);
    //     if ($vehicle) return $vehicle;
    
    //     return null;
    // }
    

    
    
}
