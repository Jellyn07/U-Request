<?php
require_once __DIR__ . '/../models/MaterialModel.php';
require_once __DIR__ . '/../core/BaseModel.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class MaterialController extends BaseModel {
    private $model;

    public function __construct() {
        $this->model = new MaterialModel();
    }

    // Mother division filter
    public function getFiltered($search = '', $status = 'all', $order = 'az') {
        return $this->model->getFilteredMaterials($search, $status, $order);
    }

    // Display all materials
    public function index() {
        return $this->model->getAll();
    }

    // Search materials
    public function search($keyword) {
        return $this->model->search($keyword);
    }

    // Filter by status
    public function filter($status) {
        return $this->model->filterByStatus($status);
    }

    // Sort by name
    public function sort($order = 'az') {
        return $this->model->sortByName($order);
    }

    // Update existing material
    public function update($data) {
        // check duplicates first
        $duplicate = $this->model->existsForUpdate($data['material_code'], $data['material_desc'], $data['material_code']);
        if ($duplicate) {
            return $duplicate; // "code" or "description"
        }
    
        return $this->model->update(
            $data['material_code'],
            $data['material_desc'],
            $data['qty'],
            $data['material_status']
        );
    }
    

    // Add new material
    public function store($data) {
        $duplicate = $this->model->exists($data['material_code'], $data['material_desc']);
        if ($duplicate) {
            return $duplicate; // "code" or "description"
        }
    
        $status = ($data['qty'] == 0) ? 'Unavailable' : 'Available';
    
        return $this->model->addmaterial(
            $data['material_code'],
            $data['material_desc'],
            $data['qty'],
            $status
        );
    }
    

    // Get one material by id
    public function show($id) {
        return $this->model->find($id);
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $controller = new MaterialController();

    // Handle add
    if (isset($_POST['add_material'])) {
        $result = $controller->store($_POST);
    
        if ($result === "code") {
            $_SESSION['material_error'] = "Material Code already exists!";
        } elseif ($result === "description") {
            $_SESSION['material_error'] = "Material Description already exists!";
        } elseif ($result) {
            $_SESSION['material_success'] = "Material added successfully!";
        } else {
            $_SESSION['material_error'] = "Failed to add material.";
        }
    
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    

    // Handle update
    if (isset($_POST['update_material'])) {
        $result = $controller->update($_POST);
    
        if ($result === "code") {
            $_SESSION['material_error'] = "Material Code already exists!";
        } elseif ($result === "description") {
            $_SESSION['material_error'] = "Material Description already exists!";
        } elseif ($result) {
            $_SESSION['material_success'] = "Material updated successfully!";
        } else {
            $_SESSION['material_error'] = "Failed to update material.";
        }
    
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
}
