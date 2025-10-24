<?php
require_once __DIR__ . '/../models/DriverModel.php';

class DriverController {
    private $model;

    public function __construct() {
        $this->model = new  DriverModel();
    }

    public function getAllDriver() {
        return $this->model->getAllDriver();
    }

    public function getDriver($staff_id) {
        return $this->model->getDriverById($staff_id);
    }

    // --- ADD PERSONNEL ---
    public function addDriver($postData) {
        // ✅ Handle file upload
        $profile_picture_path = null;
    
        if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . "/../../uploads/profile_pics";
    
            // Create directory if not exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
    
            $filename = basename($_FILES["profile_picture"]["name"]);
            $target_file = $upload_dir . '/' . $filename;
    
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture_path = "/public/uploads/profile_pics/" . $filename;
            } else {
                $_SESSION['driver_error'] = "Failed to upload profile picture.";
                header("Location: ../modules/motorpool_admin/views/drivers.php");
                exit;
            }
        }
    
        // ✅ Collect input data + profile picture
        $data = [
            'firstName'      => $postData['first_name'] ?? '',
            'lastName'       => $postData['last_name'] ?? '',
            'contact'        => $postData['contact_no'] ?? '',
            'hire_date'      => $postData['hire_date'] ?? '',
            'profile_picture'=> $filename 
        ];
        $result = $this->model->addDriver($data);
    
        if ($result) {
            $_SESSION['driver_success'] = "New driver added successfully!";
        } else {
            if (!isset($_SESSION['driver_error'])) {
                $_SESSION['driver_error'] = $_SESSION['db_error'] ?? "Failed to add driver.";
            }
        }
    
        header("Location: ../modules/motorpool_admin/views/drivers.php");
        exit;
    }
    

    // --- UPDATE PERSONNEL ---
    public function updateDriver($postData) {
         // ✅ Handle file upload
         $profile_picture_path = null;
    
         if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
             $upload_dir = __DIR__ . "/../../uploads/profile_pics";
     
             // Create directory if not exists
             if (!is_dir($upload_dir)) {
                 mkdir($upload_dir, 0755, true);
             }
     
             $filename = basename($_FILES["profile_picture"]["name"]);
             $target_file = $upload_dir . '/' . $filename;
     
             if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                 $profile_picture_path = "/uploads/profile_pics/" . $filename;
             } else {
                 $_SESSION['driver_error'] = "Failed to upload profile picture.";
                 header("Location: ../modules/motorpool_admin/views/drivers.php");
                 exit;
             }
         }

        $data = [
            'driver_id'  => $postData['staff_id'] ?? null,
            'firstName' => $postData['first_name'] ?? '',
            'lastName'  => $postData['last_name'] ?? '',
            'contact'   => $postData['contact_no'] ?? '',
            'hire_date' => $postData['hire_date'] ?? '',
            'profile_picture'=> $filename
        ];

        $ok = $this->model->updateDriver($data);

        if ($ok) {
            $_SESSION['driver_success'] = "Driver updated successfully!";
        } else {
            if (!isset($_SESSION['driver_error'])) {
                $_SESSION['driver_error'] = "Failed to update driver.";
            }
        }

        header("Location: ../modules/motorpool_admin/views/drivers.php");
        exit;
    }


    // --- DELETE PERSONNEL ---
    public function deleteDriver($staff_id) {
        $deleted = $this->model->deleteDriver($staff_id);
        if ($deleted) {
            $_SESSION['personnel_success'] = "Personnel deleted successfully!";
        } else {
            $_SESSION['personnel_error'] = "Failed to delete personnel.";
        }

        header("Location: ../modules/gsu_admin/views/personnel.php");
        exit;
    }

    public function getProfile($admin_email)
    {
        return $this->model->getProfileByEmail($admin_email);
    }
}

// ✅ Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = new DriverController();

// --- HANDLE POST REQUESTS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_driver'])) {
    $controller = new DriverController();
    $controller->addDriver($_POST);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_driver'])) {
    $controller = new DriverController();
    $controller->updateDriver($_POST);
}


// --- HANDLE GET REQUEST (DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $controller->deleteDriver($_GET['delete']);
}

$driverModel = new DriverModel();
if (isset($_POST['get_work_history'])) {
    $staff_id = $_POST['staff_id'];
    $history = $personnelModel->getWorkHistory($staff_id);
    echo json_encode($history);
    exit;
}
