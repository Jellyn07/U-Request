<?php
require_once __DIR__ . '/../models/UserAdminModel.php';

class UserAdminController {
    private $model;

    public function __construct() {
        $this->model = new UserAdminModel();
    }

    public function getUsers($search = '', $status = 'all', $sort = 'az') {
        return $this->model->getUsers($search, $status, $sort);
    }

    public function getProfile($admin_email) {
        return $this->model->getProfileByEmail($admin_email);
    }

    public function getUserDetails($requester_id) {
        return $this->model->getUserDetails($requester_id);
    }
}

// ✅ Get user details (AJAX GET)
if (isset($_GET['requester_id'])) {
    header('Content-Type: application/json');

    $controller = new UserAdminController();
    $details = $controller->getUserDetails($_GET['requester_id']);

    echo json_encode($details);
    exit;
}

// ✅ Get request history (AJAX POST)
if (isset($_POST['get_request_history'])) {
    header('Content-Type: application/json');

    $requester_id = $_POST['requester_id'] ?? null;
    if (!$requester_id) {
        echo json_encode(['error' => 'Missing requester_id']);
        exit;
    }

    $UserAdminModel = new UserAdminModel();
    $history = $UserAdminModel->getRequestHistory($requester_id);

    echo json_encode($history);
    exit;
}
