<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/RequestModel.php';
require_once __DIR__ . '/../config/constants.php';

class RequestController {
    private $model;

    public function __construct() {
        $this->model = new RequestModel();
    }

      public function getAllMaterials() {
        return $this->model->getAllMaterials();
    }

    public function submitRequest() {
        $tracking_id = 'TRK-' . date("Ymd") . '-' . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 5);

        $nature       = $_POST['nature-request'] ?? '';
        $req_id       = $_POST['req_id'] ?? 1; // Replace with actual logged-in requester ID
        $description  = $_POST['description'] ?? '';
        $unit         = $_POST['unit'] ?? '';
        $building     = $_POST['exLocb'] ?? '';
        $room         = $_POST['exLocr'] ?? '';
        $location     = $building . ' - ' . $room;
        $dateNoticed  = $_POST['dateNoticed'] ?? date('Y-m-d');

        $filePath = null;

        // Handle file if uploaded
        if (isset($_FILES['picture']) && !empty($_FILES['picture']['name'])) {
            $fileName = $_FILES['picture']['name'];
            $fileTmp  = $_FILES['picture']['tmp_name'];
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg','jpeg','png','gif'];
            if (!in_array($fileExt, $allowedExtensions)) {
                $_SESSION['alert'] = [
                    'type'=>'error',
                    'title'=>'Invalid File Type',
                    'message'=>'Only JPG, JPEG, PNG, and GIF files are allowed.',
                    'redirect'=>"/app/modules/user/views/request.php"
                ];
                header("Location: ../modules/user/views/request.php");
                exit;
            }

            $targetDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $newFileName = uniqid("img_", true) . "." . $fileExt;
            $targetPath = $targetDir . $fileName;

            if (move_uploaded_file($fileTmp, $targetPath)) {
                $filePath = '/public/uploads/' . $fileName;
            } else {
                $_SESSION['alert'] = [
                    'type'=>'error',
                    'title'=>'Upload Failed',
                    'message'=>'There was a problem uploading your file.',
                    'redirect'=>"../modules/user/views/request.php"
                ];
                header("Location: ../modules/user/views/request.php");
                exit;
            }
        }

        // Check for duplicate request
        if ($this->model->checkDuplicateRequest($unit, $location, $nature)) {
            $_SESSION['alert'] = [
                'type' => 'warning',
                'title' => 'Duplicate Request',
                'message' => 'A request with the same unit, location, and type already exists.',
                'redirect' => "../modules/user/views/request.php"
            ];
            header("Location: ../modules/user/views/request.php");
            exit;
        }

        // Save request to DB
        $request_id = $this->model->createRequest(
            $tracking_id, $nature, $req_id, $description,
            $unit, $location, $dateNoticed, $fileName 
        );

        if ($request_id) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Request Submitted',
                'message' => "Your tracking ID: {$tracking_id}",
                'redirect' => "/app/modules/user/views/request.php"
            ];
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Submission Failed',
                'message' => 'Something went wrong. Please try again.',
                'redirect' => "/app/modules/user/views/request.php"
            ];
        }

        header("Location: ../modules/user/views/request.php");
        exit;
    }

    public function getAllRequesters() {
        return $this->model->getAllRequesters();
    }

    public function showRequests() {
        $requests = $this->model->getAllVehicleRequests();
    }
    
    public function getRequesterById($id) {
        return $this->model->getRequesterById($id);
    }

    public function index() {
        $requests = $this->model->getAllRequests();
        // Pass data to view
        return [
            "requests"   => $requests
        ];
    }

    public function indexVehicle() {
        $requests = $this->model->getAllVehicleRequests();

        return [
            "requests" => $requests
        ];
    }

    // Show single request details
    public function view($id) {
        return $this->model->getRequestById($id);
    }

    // In RequestController.php
    public function saveAssignment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_id = $_POST['request_id'];
            $req_status = $_POST['req_status'] ?? null;
            $prio_level = $_POST['prio_level'] ?? null;

            // Arrays for personnel and materials
            $staff_ids = $_POST['staff_id'] ?? [];
            $remove_staff_ids = $_POST['remove_staff_ids'] ?? [];
            $materials_to_add = $_POST['materials'] ?? [];
            $materials_to_remove = $_POST['materials_to_remove'] ?? [];

            // Ensure arrays
            $staff_ids = is_array($staff_ids) ? $staff_ids : [];
            $remove_staff_ids = is_array($remove_staff_ids) ? $remove_staff_ids : [];
            $materials_to_add = is_array($materials_to_add) ? $materials_to_add : [];
            $materials_to_remove = is_array($materials_to_remove) ? $materials_to_remove : [];

            // Get current assigned personnel
            $assignedPersonnel = $this->model->getAssignedPersonnel($request_id);

            // ❌ Prevent status update if no personnel assigned and no new staff being added
            if (($req_status === 'In Progress' || $req_status === 'Completed') && empty($assignedPersonnel) && empty($staff_ids)) {
                echo json_encode([
                    "success" => false,
                    "message" => "Cannot update status to '{$req_status}' because no personnel are assigned."
                ]);
                exit;
            }

            $result = $this->model->addAssignment(
                $request_id,
                $req_status, // Pass null for status so it won't update yet
                $staff_ids,
                $prio_level,
                $materials_to_add,
                $remove_staff_ids,
                $materials_to_remove
            );

            $finalAssignedPersonnel = $this->model->getAssignedPersonnel($request_id);
            if (!empty($finalAssignedPersonnel) && ($req_status === 'In Progress' || $req_status === 'Completed')) {
                $this->model->updateRequestStatus($request_id, $req_status);
            }

            $redirect = $_SERVER['HTTP_REFERER'] ?? '/'; // Go back to the page where the request came from

            header("Location: $redirect");
            exit;
        }
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
            $request_id = $_POST['request_id'] ?? null;
            $req_status = $_POST['req_status'] ?? null;

        if (!$request_id || !$req_status) {
            echo json_encode([
                "success" => false,
                "message" => "Request ID or status is missing."
            ]);
            exit;
        }
        // ✅ Check assigned personnel
        $assignedPersonnel = $this->model->getAssignedPersonnel($request_id); 
        // Returns an array of staff_ids assigned
        if (($req_status === 'In Progress' || $req_status === 'Completed') && empty($assignedPersonnel)) {
            echo json_encode([
                "success" => false,
                "message" => "Cannot update status to '{$req_status}' because no personnel are assigned."
            ]);
            exit;
        }
        // ✅ If check passes, update status
        $result = $this->model->updateRequestStatus($request_id, $req_status);

        echo json_encode($result);
        exit;
    }
}


    public function getLocationsByUnit() {
        header('Content-Type: application/json');

        if (empty($_GET['unit'])) {
            echo json_encode(["success" => false, "message" => "Unit is required"]);
            exit;
        }

        $unit = $_GET['unit'];

        try {
            require_once __DIR__ . '/../models/RequestModel.php';
            $model = new RequestModel();
            $data = $model->getLocationsByUnit($unit);
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }

        exit;
    }

    public function getProfile($admin_email)
    {
        return $this->model->getProfileByEmail($admin_email);
    }

    public function getAvailableStaff() {
        return $this->model->getAvailableStaff();
    }

}

// Run controller on form submission
$controller = new RequestController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'saveAssignment') {
        $controller->saveAssignment();

    }else if (isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
        $controller->updateStatus();

    }else {
        $controller->submitRequest();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'getLocationsByUnit') {
    $controller = new RequestController();
    $controller->getLocationsByUnit();
}

require_once __DIR__ . '/../models/RequestModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'updateLocation') {
        $request_id = $_POST['request_id'] ?? null;
        $location = $_POST['location'] ?? null;

        if (!$request_id || !$location) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        $model = new RequestModel();
        $updated = $model->updateLocation($request_id, $location);

        if ($updated) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update location']);
        }
        exit;
    }
}
