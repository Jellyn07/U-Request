<?php
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/VehicleController.php';


$token  = $_GET['token'] ?? '';
$status = $_GET['status'] ?? '';

if (empty($token) || empty($status)) {
    die("Invalid approval link.");
}

if (empty($token) || empty($status)) {
    die("Invalid approval link.");
}

try {
    $controller = new VehicleController();
    $result = $controller->approveVehicleRequest($token, $status);

    if ($result['success']) {
        echo "<h2 style='font-family:Arial;text-align:center;color:green;'>✅ Request has been {$status} successfully.</h2>";
    } else {
        echo "<h2 style='font-family:Arial;text-align:center;color:red;'>❌ {$result['message']}</h2>";
    }
} catch (Exception $e) {
    echo "<h2 style='font-family:Arial;text-align:center;color:red;'>Error: {$e->getMessage()}</h2>";
}

?>
