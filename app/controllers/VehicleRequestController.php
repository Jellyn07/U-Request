<?php
session_start();
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/RequestVehicleModel.php';

class VehicleRequestController {
    private $model;

    public function __construct() {
        $this->model = new VehicleRequestModel();
    }

    // Handle POST form submission
    public function submitRequest() {
        if (!isset($_SESSION['req_id'])) {
            throw new Exception("Requester ID not found in session.");
        }

        $req_id = $_SESSION['req_id'];
        $tracking_id = "TRK-VR" . date("Ymd") . "-" . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 5);

        $control_no = $this->model->addVehicleRequest(
            $req_id,
            $tracking_id,
            $_POST['purpose_of_trip'] ?? '',
            $_POST['travel_destination'] ?? '',
            $_POST['date_of_travel'] ?? '',
            $_POST['date_of_return'] ?? '',
            $_POST['time_of_departure'] ?? '',
            $_POST['time_of_return'] ?? '',
            $_POST['source_of_fuel'] ?? '',
            $_POST['source_of_oil'] ?? '',
            $_POST['source_of_repair_maintenance'] ?? '',
            $_POST['source_of_driver_assistant_per_diem'] ?? ''
        );

        if (!$control_no) {
            throw new Exception("Failed to create vehicle request.");
        }

        // Add passengers (avoid duplicates)
        if (!empty($_POST['first_name']) && is_array($_POST['first_name'])) {
            foreach ($_POST['first_name'] as $i => $fname) {
                $lname = $_POST['last_name'][$i] ?? '';
                if (!empty($fname) && !empty($lname)) {

                    // Check if passenger already exists
                    $passenger_id = $this->model->getPassengerByName($fname, $lname);

                    if (!$passenger_id) {
                        // Add new passenger if not exist
                        $passenger_id = $this->model->addPassenger($fname, $lname);
                    }

                    // Link passenger to vehicle request
                    if ($passenger_id) {
                        $this->model->linkPassenger($control_no, $passenger_id);
                    }
                }
            }
        }

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Request Submitted',
            'message' => 'Your vehicle request has been submitted successfully!'
        ];

        header("Location: ../modules/user/views/request.php");
        exit;
    }

    // Fetch vehicles as JSON
    public function fetchVehicles() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getVehicles());
        exit;
    }

    // Fetch drivers as JSON
    public function fetchDrivers() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getDriver());
        exit;
    }

    public function saveAssignment($reqAssignment_id, $vehicle_id, $driver_id, $req_status, $approved_by) {
        return $this->model->saveAssignment($reqAssignment_id, $vehicle_id, $driver_id, $req_status, $approved_by);
    }
}

// Route requests
$controller = new VehicleRequestController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->submitRequest();
}

if (isset($_GET['vehicles'])) {
    $controller->fetchVehicles();
} elseif (isset($_GET['drivers'])) {
    $controller->fetchDrivers();
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$controller = new VehicleRequestController(); // ensure class exists

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$action = $_POST['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'saveAssignment':
        $reqAssignment_id = $_POST['req_id'] ?? null; // your form uses name=req_id
        $vehicle_id = $_POST['vehicle_id'] ?? null;
        $driver_id = $_POST['driver_id'] ?? null;
        $req_status = $_POST['req_status'] ?? null;
        $approved_by = $_POST['approved_by'] ?? null;

        // Basic validation (allow approved_by null when not approved)
        if (!$reqAssignment_id) {
            echo json_encode(['success' => false, 'message' => 'Request ID missing.']);
            exit;
        }
        if (!$vehicle_id) {
            echo json_encode(['success' => false, 'message' => 'Vehicle ID missing.']);
            exit;
        }
        // driver_id may be null/empty if vehicle has no driver; adjust if required
        if ($driver_id === '' || $driver_id === null) {
            // set to NULL (if SP expects INT, you may need to pass 0 instead)
            $driver_id = null;
        }

        // call model and capture result and message
        try {
            $result = $controller->saveAssignment($reqAssignment_id, $vehicle_id, $driver_id, $req_status, $approved_by);
            if ($result['success']) {
                echo json_encode(['success' => true, 'message' => $result['message'] ?? 'Saved successfully.']);
            } else {
                // bubble up message from model
                echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Failed to save.']);
            }
        } catch (Exception $ex) {
            error_log('saveAssignment exception: ' . $ex->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error. Check logs.']);
        }
        exit;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
        exit;
}
