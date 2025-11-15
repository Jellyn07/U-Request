<?php
session_start();
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/RequestVehicleModel.php';

class VehicleRequestController {
    private $model;
    private $req_id;

    public function __construct() {
        $this->model = new VehicleRequestModel();
        if (isset($_SESSION['req_id'])) {
            $this->req_id = $_SESSION['req_id'];
        }
    }

    // ==============================
    // 🟢 USER: Submit Vehicle Request
    // ==============================
    public function submitRequest() {
        if (!$this->req_id) {
            throw new Exception("Requester ID not found in session.");
        }

        $tracking_id = "TRK-VR" . date("Ymd") . "-" . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 5);

        $control_no = $this->model->addVehicleRequest(
            $this->req_id,
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
            error_log("❌ Vehicle request failed. Reason: " . ($this->model->lastError ?? 'Unknown'));
            throw new Exception("Failed to create vehicle request.");
        }


        $addAssign = $this->model->addAssignment($control_no, $this->req_id);
        if (!$addAssign) {
            error_log("⚠️ Failed to add default assignment for control_no=$control_no");
        }

        // ✅ Add passengers
        if (!empty($_POST['first_name']) && is_array($_POST['first_name'])) {
            foreach ($_POST['first_name'] as $i => $fname) {
                $lname = $_POST['last_name'][$i] ?? '';
                if (!empty($fname) && !empty($lname)) {
                    $passenger_id = $this->model->getPassengerByName($fname, $lname);
                    if (!$passenger_id) {
                        $passenger_id = $this->model->addPassenger($fname, $lname);
                    }
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

    // ==============================
    // 🟡 ADMIN: Update Assignment
    // ==============================
    public function updateAssignment($data) {
        header('Content-Type: application/json');
        try {
            $control_no = $data['control_no'] ?? null;
            $vehicle_id = $data['vehicle_id'] ?? null;
            $req_status = $data['req_status'] ?? null;
            $approved_by = $data['approved_by'] ?? null;
            $reason = $data['reason'] ?? null;

            if (!$control_no) {
                throw new Exception('Missing Control No.');
            }

            $result = $this->model->updateAssignment($control_no, $vehicle_id, $req_status, $approved_by, $reason);

            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Assignment updated successfully.' : 'No changes made or failed to update.'
            ]);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ==============================
    // 🔵 Fetchers
    // ==============================
    public function fetchVehicles() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getVehicles());
        exit;
    }

    public function fetchDrivers() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getDriver());
        exit;
    }
}

// ==============================
// 🔶 ROUTER HANDLER
// ==============================
$controller = new VehicleRequestController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['form_action'] ?? '';

    switch ($action) {
        case 'submitRequest':
            $controller->submitRequest();
            break;

        case 'saveAssignment':
            $controller->updateAssignment($_POST);
            break;

        default:
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid form action.']);
            break;
    }
} elseif (isset($_GET['vehicles'])) {
    $controller->fetchVehicles();
} elseif (isset($_GET['drivers'])) {
    $controller->fetchDrivers();
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request']);
}
