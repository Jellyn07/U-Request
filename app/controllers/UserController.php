<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function fetchRequestHistory($requester_id) {
        $data = $this->model->getRequestHistory($requester_id);
        echo json_encode([
            'success' => !empty($data),
            'records' => $data
        ]);
    }
    
}

// at bottom of UserController.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // quick sanity: ensure required keys exist
    if (!isset($_POST['action']) || $_POST['action'] !== 'get_history') {
        echo json_encode(['success' => false, 'error' => 'Missing or invalid action']);
        exit;
    }

    if (!isset($_POST['requester_id']) || empty($_POST['requester_id'])) {
        echo json_encode(['success' => false, 'error' => 'Missing requester_id']);
        exit;
    }

    $controller = new UserController();
    try {
        $controller->fetchRequestHistory($_POST['requester_id']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

