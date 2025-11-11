<?php
require_once __DIR__ . '/../models/MaterialModel.php';
require_once __DIR__ . '/../core/BaseModel.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class MaterialController extends BaseModel
{
    private $model;

    public function __construct()
    {
        parent::__construct(); // ✅ Base model constructor
        $this->model = new MaterialModel();
    }

    // Get filtered materials
    public function getFiltered($search = '', $status = 'all', $order = 'az')
    {
        return $this->model->getFilteredMaterials($search, $status, $order);
    }

    // Get all materials
    public function index()
    {
        return $this->model->getAll();
    }

    // Search
    public function search($keyword)
    {
        return $this->model->search($keyword);
    }

    // Filter
    public function filter($status)
    {
        return $this->model->filterByStatus($status);
    }

    // Sort
    public function sort($order = 'az')
    {
        return $this->model->sortByName($order);
    }

    // Update
    public function update($data)
    {
        $duplicate = $this->model->existsForUpdate($data['material_code'], $data['material_desc'], $data['material_code']);
        if ($duplicate) return $duplicate;

        return $this->model->update(
            $data['material_code'],
            $data['material_desc'],
            $data['qty'],
            $data['material_status']
        );
    }

    // Store new material
    public function store($data)
    {
        $duplicate = $this->model->exists($data['material_code'], $data['material_desc']);
        if ($duplicate) return $duplicate;

        $status = ($data['qty'] == 0) ? 'Unavailable' : 'Available';

        return $this->model->addmaterial(
            $data['material_code'],
            $data['material_desc'],
            $data['qty'],
            $status
        );
    }

    // Show single material
    public function show($id)
    {
        return $this->model->find($id);
    }

    // Get next material code
    public function getNextMaterialCode()
    {
        $stmt = $this->db->prepare("SELECT MAX(material_code) AS max_code FROM materials");

        if (!$stmt) return 1;

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $maxCode = $row['max_code'] ?? 0;
        return $maxCode + 1;
    }

    // Add stock
    public function addStock($material_code, $quantity)
    {
        return $this->model->addQuantity($material_code, $quantity);
    }
}

//
// ✅ Handle Form Submissions Below
//
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new MaterialController();

    // ✅ Add Material
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

    // ✅ Update Material
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

    // ✅ Add Stock
    if (isset($_POST['add_stock'])) {
        $material_code = $_POST['material_code'] ?? null;
        $quantity_to_add = $_POST['quantity'] ?? 0;

        if (!$material_code || $quantity_to_add <= 0) {
            $_SESSION['material_error'] = "Invalid stock data submitted.";
            $redirect = $_SERVER['HTTP_REFERER'] ?? '/'; // Go back to the page where the request came from

            header("Location: $redirect");
            exit;
        }

        if ($controller->addStock($material_code, $quantity_to_add)) {
            $_SESSION['material_success'] = "Stock added successfully!";
        } else {
            $_SESSION['material_error'] = "Failed to update stock.";
        }

        $redirect = $_SERVER['HTTP_REFERER'] ?? '/'; // Go back to the page where the request came from

        header("Location: $redirect");
        exit;
    }
}
