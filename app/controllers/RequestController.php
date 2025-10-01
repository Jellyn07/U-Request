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
            $targetPath = $targetDir . $newFileName;

            if (move_uploaded_file($fileTmp, $targetPath)) {
                $filePath = '/public/uploads/' . $newFileName;
            } else {
                $_SESSION['alert'] = [
                    'type'=>'error',
                    'title'=>'Upload Failed',
                    'message'=>'There was a problem uploading your file.',
                    'redirect'=>"/app/modules/user/views/request.php"
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
                'redirect' => "/app/modules/user/views/request.php"
            ];
            header("Location: ../modules/user/views/request.php");
            exit;
        }

        // Save request to DB
        $request_id = $this->model->createRequest(
            $tracking_id, $nature, $req_id, $description,
            $unit, $location, $dateNoticed, $filePath
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

    // Show single request details
    public function view($id) {
        return $this->model->getRequestById($id);
    }

    // In RequestController.php
    public function saveAssignment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'saveAssignment') {
            $request_id = $_POST['request_id'] ?? null;
            $req_id     = $_POST['req_id'] ?? null;
            $req_status = $_POST['req_status'] ?? 'To Inspect';
            $prio_level = $_POST['prio_level'] ?? null;
            $staff_id   = $_POST['staff_id'] ?? null;
    
            if (!$request_id || !$req_id || !$prio_level || !$staff_id) {
                $_SESSION['alert'] = [
                    "type" => "warning",
                    "title" => "Missing Fields",
                    "message" => "Please fill in all required fields."
                ];
                header("Location: ../modules/gsu_admin/views/request.php");
                exit;
            }
    
            $this->model->addAssignment($request_id, $req_id, $req_status, $staff_id, $prio_level);
            header("Location: ../modules/gsu_admin/views/request.php");
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
    
            $result = $this->model->updateRequestStatus($request_id, $req_status);
            echo json_encode($result);
            exit;
        }
    }

    public function getProfile($admin_email)
    {
        return $this->model->getProfileByEmail($admin_email);
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


// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === "saveAssignment") {
    
//     // Safely get POST values with null coalescing
//     $request_id = $_POST['request_id'] ?? null;
//     $req_id = $_POST['req_id'] ?? null;
//     $req_status = $_POST['req_status'] ?? 'To Inspect';
//     $prio_level = $_POST['prio_level'] ?? null;
//     $staff_id = $_POST['staff_id'] ?? null; // ⚡ safely handle optional picture
    
//     // Validate required fields
//     if (!$request_id || !$req_id || !$prio_level || !$staff_id) {
//         echo json_encode([
//             "success" => false,
//             "message" => "Please fill in all required fields."
//         ]);
//         exit;
//     }

//     // Call model to save assignment
//     $result = $model->addAssignment($request_id, $req_id, $req_status, $staff_id, $prio_level);

//     echo json_encode($result);
//     exit;
// }
