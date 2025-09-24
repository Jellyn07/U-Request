<?php
require_once __DIR__ . '/../models/MaterialModel.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class MaterialController {
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

    // Add material
    public function store($data) {
        return $this->model->addmaterial(
            $data['material_code'],
            $data['material_desc'],
            $data['qty'],
            $data['material_status']
        );
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
        return $this->model->update(
            $data['material_code'],
            $data['material_desc'],
            $data['qty'],
            $data['material_status']
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
        $success = $controller->store($_POST);

        if ($success) {
            $_SESSION['material_success'] = "Material added successfully!";
        } else {
            $_SESSION['material_error'] = "Failed to add material.";
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Handle update
    if (isset($_POST['update_material'])) {
        $success = $controller->update($_POST);

        if ($success) {
            $_SESSION['material_success'] = "Material updated successfully!";
        } else {
            $_SESSION['material_error'] = "Failed to update material.";
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    //Handle Add
    if (isset($_POST['add_material'])) {
        $success = $controller->store($_POST);
    }
}
