<?php
require_once __DIR__ . '/../models/UserAdminModel.php';

class UserAdminController {
    private $model;

    public function __construct() {
        $this->model = new UserAdminModel();
    }

    // Get users with optional filters (search, status, sort)
    public function getUsers($search = '', $status = 'all', $sort = 'az') {
        return $this->model->getUsers($search, $status, $sort);
    }

    public function getProfile($admin_email) {
        return $this->model->getProfileByEmail($admin_email);
    }

    // Get detailed user info by requester_id
    public function getUserDetails($requester_id)
    {
        return $this->model->getUserDetails($requester_id);
    }
}

// ✅ Handle AJAX request to get user details
if (isset($_GET['requester_id'])) {
    header('Content-Type: application/json');

    require_once __DIR__ . '/UserAdminController.php';
    $controller = new UserAdminController();
    $details = $controller->getUserDetails($_GET['requester_id']);

    echo json_encode($details);
    exit;
}

$UserAdminModel = new UserAdminModel();
if (isset($_POST['get_request_history'])) {
    $staff_id = $_POST['requester_id'];
    $history = $UserAdminModel->getRequestHistory($requester_id);
    echo json_encode($history);
    exit;
}

// ✅ Handle AJAX work history request
if (isset($_POST['get_work_history'])) {
    $req_id = $_POST['req_id']; // match your front-end param
    $personnelModel = new PersonnelModel();
    $history = $personnelModel->getWorkHistory($req_id);
    echo json_encode($history);
    exit;
}



