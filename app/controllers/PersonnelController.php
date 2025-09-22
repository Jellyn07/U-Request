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
        $data = [
            'staff_id'   => $postData['staff_id'] ?? '',
            'firstName'  => $postData['first_name'] ?? '',
            'lastName'   => $postData['last_name'] ?? '',
            'department' => $postData['department'] ?? '',
            'contact'    => $postData['contact_no'] ?? '',
            'hire_date'  => $postData['hire_date'] ?? '',
            'unit'       => $postData['unit'] ?? ''
        ];
    
        $result = $this->model->addPersonnel($data);
    
        if ($result) {
            $_SESSION['personnel_success'] = "Personnel added successfully!";
        } else {
            // ✅ Keep specific model error if it exists
            if (!isset($_SESSION['personnel_error'])) {
                $_SESSION['personnel_error'] = $_SESSION['db_error'] ?? "Failed to add personnel.";
            }
        }
    
        header("Location: ../modules/gsu_admin/views/personnel.php");
        exit;
    }
    

    // --- UPDATE PERSONNEL ---
    public function updatePersonnel($postData) {
        $data = [
            'staff_id'  => $postData['staff_id'] ?? null,
            'firstName' => $postData['first_name'] ?? '',
            'lastName'  => $postData['last_name'] ?? '',
            'department'=> $postData['department'] ?? '',
            'contact'   => $postData['contact_no'] ?? '',
            'hire_date' => $postData['hire_date'] ?? '',
            'unit'      => $postData['unit'] ?? ''
            
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
}

// ✅ Safe session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = new PersonnelController();

// --- HANDLE POST REQUESTS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_personnel'])) {
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
