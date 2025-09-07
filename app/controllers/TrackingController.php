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
    
}
