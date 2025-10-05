<?php
require_once __DIR__ . '/../models/PersonnelModel.php';

class PersonnelController {
    private $model;

    public function __construct() {
        $this->model = new PersonnelModel();
    }

    public function getAllPersonnel() {
        return $this->model->getAllPersonnel();
    }

    public function getPersonnel($staff_id) {
        return $this->model->getPersonnelById($staff_id);
    }

    // --- ADD PERSONNEL ---
    public function addPersonnel($postData) {
        // ✅ Handle file upload
        $profile_picture_path = null;
    
        if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . "/../../public/uploads/profile_pics";
    
            // Create directory if not exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
    
            $filename = basename($_FILES["profile_picture"]["name"]);
            $target_file = $upload_dir . '/' . $filename;
    
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture_path = "/public/uploads/profile_pics/" . $filename;
            } else {
                $_SESSION['personnel_error'] = "Failed to upload profile picture.";
                header("Location: ../modules/gsu_admin/views/personnel.php");
                exit;
            }
        }
    
        // ✅ Collect input data + profile picture
        $data = [
            'staff_id'       => $postData['staff_id'] ?? '',
            'firstName'      => $postData['first_name'] ?? '',
            'lastName'       => $postData['last_name'] ?? '',
            'department'     => $postData['department'] ?? '',
            'contact'        => $postData['contact_no'] ?? '',
            'hire_date'      => $postData['hire_date'] ?? '',
            'unit'           => $postData['unit'] ?? '',
            'profile_picture'=> $filename 
        ];
    
        $result = $this->model->addPersonnel($data);
    
        if ($result) {
            $_SESSION['personnel_success'] = "Personnel added successfully!";
        } else {
            if (!isset($_SESSION['personnel_error'])) {
                $_SESSION['personnel_error'] = $_SESSION['db_error'] ?? "Failed to add personnel.";
            }
        }
    
        header("Location: ../modules/gsu_admin/views/personnel.php");
        exit;
    }
    

    // --- UPDATE PERSONNEL ---
    public function updatePersonnel($postData) {
         // ✅ Handle file upload
         $profile_picture_path = null;
    
         if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
             $upload_dir = __DIR__ . "/../../public/uploads/profile_pics";
     
             // Create directory if not exists
             if (!is_dir($upload_dir)) {
                 mkdir($upload_dir, 0755, true);
             }
     
             $filename = basename($_FILES["profile_picture"]["name"]);
             $target_file = $upload_dir . '/' . $filename;
     
             if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                 $profile_picture_path = "/public/uploads/profile_pics/" . $filename;
             } else {
                 $_SESSION['personnel_error'] = "Failed to upload profile picture.";
                 header("Location: ../modules/gsu_admin/views/personnel.php");
                 exit;
             }
         }

        $data = [
            'staff_id'  => $postData['staff_id'] ?? null,
            'firstName' => $postData['first_name'] ?? '',
            'lastName'  => $postData['last_name'] ?? '',
            'department'=> $postData['department'] ?? '',
            'contact'   => $postData['contact_no'] ?? '',
            'hire_date' => $postData['hire_date'] ?? '',
            'unit'      => $postData['unit'] ?? '',
            'profile_picture'=> $filename 
        ];

        $ok = $this->model->updatePersonnel($data);

        if ($ok) {
            $_SESSION['personnel_success'] = "Personnel updated successfully!";
        } else {
            if (!isset($_SESSION['personnel_error'])) {
                $_SESSION['personnel_error'] = "Failed to update personnel.";
            }
        }

        header("Location: ../modules/gsu_admin/views/personnel.php");
        exit;
    }


    // --- DELETE PERSONNEL ---
    public function deletePersonnel($staff_id) {
        $deleted = $this->model->deletePersonnel($staff_id);

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

$controller = new PersonnelController();

// --- HANDLE POST REQUESTS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_personnel'])) {
    $controller = new PersonnelController();
    $controller->addPersonnel($_POST);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_personnel'])) {
    $controller = new PersonnelController();
    $controller->updatePersonnel($_POST);
}


// --- HANDLE GET REQUEST (DELETE) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $controller->deletePersonnel($_GET['delete']);
}

$personnelModel = new PersonnelModel();
if (isset($_POST['get_work_history'])) {
    $staff_id = $_POST['staff_id'];
    $history = $personnelModel->getWorkHistory($staff_id);
    echo json_encode($history);
    exit;
}
