<?php
require_once __DIR__ . '/../models/MaterialModel.php';

class MaterialController {
    private $model;

    public function __construct() {
        $this->model = new MaterialModel();
    }

    //mother division
    public function getFiltered($search, $status, $order) {
        return $this->model->getFilteredMaterials($search, $status, $order);
    }


    //display all
    public function index() {
        return $this->model->getAll();
    }

    //search material
    public function search($keyword) {
    return $this->model->search($keyword);
    }

    //add material
    public function store($data) {
        return $this->model->create(
            $data['material_code'],
            $data['material_desc'],
            $data['qty'],
            $data['material_status']
        );
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_material'])) {
        $controller = new MaterialController();
        $success = $controller->store($_POST);

        if ($success) {
            header("Location: ../app/modules/gsu_admin/views/inventory.php?success=1");
        } else {
            header("Location: ../app/modules/gsu_admin/views/inventory.php?error=1");
        }
        exit;
    }
    }

    //filter status
    public function filter($status) {
        return $this->model->filterByStatus($status);
    }

    //sort a-z vice versa
    public function sort($order) {
    return $this->model->sortByName($order);
    }
}