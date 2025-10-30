<?php
require_once __DIR__ . '/../models/VehicleModel.php';

class VehicleController {
    private $vehicleModel;

    public function __construct() {
        $this->vehicleModel = new VehicleModel();
    }

    public function getDrivers() {
        return $this->vehicleModel->getDrivers();
    }

    public function getVehicles() {
        return $this->vehicleModel->getVehicles();
    }

    public function addVehicle() {
        if (isset($_POST['add_vehicle'])) {
            session_start();

            $plateNo = $_POST['plate_no'];

            // ✅ Check for duplicate plate number
            if ($this->vehicleModel->isPlateExists($plateNo)) {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Duplicate Plate Number',
                    'text' => 'Plate number already exists! Please use a different one.'
                ];
                header("Location: ../modules/motorpool_admin/views/vehicles.php");
                exit;
            }

            $fileName = null;

            // ✅ Handle file upload
            if (!empty($_FILES['picture']['name']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . "/../../uploads/vehicles";
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

                $fileTmp = $_FILES['picture']['tmp_name'];
                $fileName = basename($_FILES['picture']['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','gif'];

                if (!in_array($fileExt, $allowed)) {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Invalid File Format',
                        'text' => 'Only JPG, JPEG, PNG, GIF are allowed!'
                    ];
                    header("Location: ../modules/motorpool_admin/views/vehicles.php");
                    exit;
                }

                $target_file = $upload_dir . '/' . $fileName;

                if (move_uploaded_file($fileTmp, $target_file)) {
                    $photoPath = "/public/uploads/vehicles/" . $fileName;
                } else {
                    $_SESSION['alert'] = [
                        'icon' => 'error',
                        'title' => 'Upload Failed',
                        'text' => 'Failed to upload vehicle photo.'
                    ];
                    header("Location: ../modules/motorpool_admin/views/vehicles.php");
                    exit;
                }
            }

            // ✅ Prepare data
            $data = [
                'vehicle_name' => $_POST['vehicle_name'],
                'plate_no' => $plateNo,
                'capacity' => $_POST['capacity'],
                'vehicle_type' => $_POST['vehicle_type'],
                'driver_id' => $_POST['driver_id'],
                'photo' => $fileName
            ];

            // ✅ Insert into DB
            if ($this->vehicleModel->addVehicle($data)) {
                $_SESSION['alert'] = [
                    'icon' => 'success',
                    'title' => 'Vehicle Added',
                    'text' => 'Vehicle added successfully!'
                ];
            } else {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Insert Failed',
                    'text' => 'Failed to add vehicle. Please try again.'
                ];
            }

            header("Location: ../modules/motorpool_admin/views/vehicles.php");
            exit;
        }
    }

    public function updateVehicle() {
        $data = [
            'vehicle_id' => $_POST['vehicle_id'] ?? null,
            'vehicle_name' => $_POST['vehicle_name'] ?? '',
            'plate_no' => $_POST['plate_no'] ?? '',
            'capacity' => $_POST['capacity'] ?? 0,
            'vehicle_type' => $_POST['vehicle_type'] ?? '',
            'driver_id' => $_POST['driver_id'] ?? 0,
            'status' => $_POST['status'] ?? '',
        ];

        // Handle optional file upload
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../uploads/vehicles/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $fileTmp = $_FILES['picture']['tmp_name'];
            $fileName = basename($_FILES['picture']['name']);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($fileTmp, $filePath)) {
                $data['photo'] = $fileName;
            }
        }

        $success = $this->vehicleModel->updateVehicle($data);

    // Ensure proper JSON response
        header('Content-Type: application/json');
        echo json_encode($success );
        exit; 
        exit;
    }

    public function fetchTravelHistory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['get_travel_history'])) {
            $vehicle_id = intval($_POST['vehicle_id'] ?? 0);

            $history = [];
            if ($vehicle_id > 0) {
                $history = $this->vehicleModel->getVehicleTravelHistory($vehicle_id);
            }

            header('Content-Type: application/json');
            echo json_encode($history);
            exit;
        }
    }
    
    public function updateStatusByToken($token, $status) {
        require_once __DIR__ . '/../models/RequestVehicleModel.php';
        $model = new VehicleRequestModel();
        return $model->updateRequestStatusByToken($token, $status);
    }

    public function approveVehicleRequest($token, $status) {
    require_once __DIR__ . '/../models/RequestVehicleModel.php';
    $model = new VehicleRequestModel();

    return $model->updateVehicleRequestStatusByToken($token, $status);
}


    public function sendVehicleRequestEmail($controlNo) {
    require_once __DIR__ . '/../models/RequestVehicleModel.php';
    $model = new VehicleRequestModel();
    $request = $model->getVehicleRequestByControlNo($controlNo);

    if (!$request) {
        echo json_encode(['success' => false, 'message' => 'Vehicle request not found.']);
        return;
    }

    $token = bin2hex(random_bytes(16));
    $model->updateApprovalToken($controlNo, $token);

    // Send email
    require_once __DIR__ . '/../config/constants.php';
    require_once __DIR__ . '/../../vendor/autoload.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jonagujol@gmail.com';;
        $mail->Password = 'wqhb eszj mxiz rmmh';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('jonagujol@gmail.com', 'U-Request System');
        $mail->addAddress('jsgujol00060@usep.edu.ph', 'Top Management');

        // ✅ Build base URL dynamically to avoid "http:///" issue
// ✅ Build full base URL dynamically (works with localhost:3000)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST']; // includes port like localhost:3000
$basePath = "/U--Request"; // adjust if project folder differs

$approveLink = "{$protocol}://{$host}{$basePath}/app/modules/email/approve_vehicle_request.php?token={$token}&status=Approved";
$rejectLink  = "{$protocol}://{$host}{$basePath}/app/modules/email/approve_vehicle_request.php?token={$token}&status=Rejected";

        $mail->isHTML(true);
        $mail->Subject = "Vehicle Request Pending Approval";
        $mail->Body = "
            <p>A new vehicle request (Control No: {$controlNo}) is ready for approval.</p>
            <p><b>Requester:</b> {$request['requester_name']}<br>
            <b>Destination:</b> {$request['travel_destination']}<br>
            <b>Date Needed:</b> {$request['date_request']}</p>
            <p>
              <a href='$approveLink' style='padding:10px 15px;background-color:green;color:white;text-decoration:none;'>Approve</a>
              <a href='$rejectLink' style='padding:10px 15px;background-color:red;color:white;text-decoration:none;margin-left:10px;'>Reject</a>
            </p>
        ";

        $mail->send();

        echo json_encode(['success' => true, 'message' => 'Email sent to Top Management successfully.']);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }
}

}

// --- Handle POST request ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vehicle'])) {
    $controller = new VehicleController();
    $controller->addVehicle();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_vehicle'])) {
    $controller = new VehicleController();
    $controller->updateVehicle();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['get_travel_history'])) {
    $controller = new VehicleController();
    $controller->fetchTravelHistory();
}

if (isset($_GET['send_email']) && isset($_GET['control_no'])) {
    $controller = new VehicleController();
    $controller->sendVehicleRequestEmail($_GET['control_no']);
    exit;
}

// --- HANDLE GET REQUEST (DELETE) ---
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
//     $controller->deleteDriver($_GET['delete']);
// }
