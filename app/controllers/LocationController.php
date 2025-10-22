<?php
require_once __DIR__ . '/../models/LocationModel.php';

class LocationController {
    private $model;

    public function __construct() {
        $this->model = new LocationModel();
    }

    public function getAllLocations() {
        return $this->model->getAllLocations();
    }

    public function getAllBuildings() {
        return $this->model->getAllBuildings();
    }

    public function addLocation($data) {
        $unit = $data['unit'] ?? '';
        $buildingOption = $data['buildingOption'] ?? '';

        // Determine building value
        $building = ($buildingOption === 'new') 
            ? trim($data['new_building'] ?? '') 
            : trim($data['existing_building'] ?? '');

        $exact_location = trim($data['exact_location'] ?? '');

        // Validate required fields
        if (empty($unit) || empty($building) || empty($exact_location)) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        $result = $this->model->addLocation($unit, $building, $exact_location);

        switch ($result) {
            case 'success':
                return ['status' => 'success', 'message' => 'Location added successfully.'];
            case 'exists':
                return ['status' => 'error', 'message' => 'This location already exists.'];
            default:
                return ['status' => 'error', 'message' => 'Failed to add location.'];
        }
    }


    public function updateLocation($data) {
        $id = $data['location_id'] ?? 0;
        $building = $data['building'] ?? '';
        $exact_location = $data['exact_location'] ?? '';

        if (!$id) {
            return ['status' => 'error', 'message' => 'Invalid location ID.'];
        }

        $success = $this->model->updateLocation($id, $building, $exact_location);
        return $success 
            ? ['status' => 'success', 'message' => 'Location updated successfully.']
            : ['status' => 'error', 'message' => 'Failed to update location.'];
    }

    public function deleteLocation($id) {
        if (!$id) {
            return ['status' => 'error', 'message' => 'Invalid location ID.'];
        }

        $success = $this->model->deleteLocation($id);
        return $success 
            ? ['status' => 'success', 'message' => 'Location deleted successfully.']
            : ['status' => 'error', 'message' => 'Failed to delete location.'];
    }
}

// 🟩 Handle AJAX requests directly
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../models/LocationModel.php';
    $controller = new LocationController();

    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'add':
            $response = $controller->addLocation($_POST);
            break;
        case 'update':
            $response = $controller->updateLocation($_POST);
            break;
        case 'delete':
            $id = $_POST['location_id'] ?? 0;
            $response = $controller->deleteLocation($id);
            break;
        default:
            $response = ['status' => 'error', 'message' => 'Invalid action'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
