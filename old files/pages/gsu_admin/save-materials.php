<?php include 'auth-check.php'; ?>
<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "utrms_db");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

if (!isset($_POST['reqAssignment_id']) || !is_numeric($_POST['reqAssignment_id'])) {
    die(json_encode(['success' => false, 'message' => 'Missing or invalid Request Assignment ID']));
}

if (!isset($_POST['materials']) || !is_array($_POST['materials']) || empty($_POST['materials'])) {
    die(json_encode(['success' => false, 'message' => 'No materials selected']));
}

if (!isset($_POST['quantities']) || !is_array($_POST['quantities']) || empty($_POST['quantities'])) {
    die(json_encode(['success' => false, 'message' => 'No quantities specified']));
}

$reqAssignment_id = intval($_POST['reqAssignment_id']);
$materials = $_POST['materials'];
$quantities = $_POST['quantities'];

if (count($materials) !== count($quantities)) {
    die(json_encode(['success' => false, 'message' => 'Mismatch between materials and quantities']));
}

try {
    $conn->begin_transaction();

    $oldMaterials = [];
    $oldStmt = $conn->prepare("SELECT material_code, quantity_needed FROM request_materials_needed WHERE reqAssignment_id = ?");
    $oldStmt->bind_param("i", $reqAssignment_id);
    $oldStmt->execute();
    $res = $oldStmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $oldMaterials[$row['material_code']] = $row['quantity_needed'];
    }
    $oldStmt->close();

    $insertStmt = $conn->prepare("INSERT INTO request_materials_needed (reqAssignment_id, material_code, quantity_needed) VALUES (?, ?, ?)");
    $updateStockStmt = $conn->prepare("UPDATE materials SET qty = qty - ? WHERE material_code = ? AND qty >= ?");

    for ($i = 0; $i < count($materials); $i++) {
        $material_code = intval($materials[$i]);
        $quantity = intval($quantities[$i]);

        if ($material_code <= 0 || $quantity <= 0) {
            throw new Exception("Invalid material or quantity at index $i");
        }

        // Check stock before reducing it
        $checkStock = $conn->prepare("SELECT material_desc, qty FROM materials WHERE material_code = ?");
        $checkStock->bind_param("i", $material_code);
        $checkStock->execute();
        $result = $checkStock->get_result();
        $stock = $result->fetch_assoc();
        $checkStock->close();

        if (!$stock) {
            throw new Exception("Material code $material_code not found");
        }

        if ($stock['qty'] < $quantity) {
            throw new Exception("Insufficient stock for {$stock['material_desc']} (Available: {$stock['qty']})");
        }

        // Insert the material requested
        $insertStmt->bind_param("iii", $reqAssignment_id, $material_code, $quantity);
        if (!$insertStmt->execute()) {
            throw new Exception("Failed to save material: " . $insertStmt->error);
        }

        // Decrease stock by requested quantity
        $updateStockStmt->bind_param("iii", $quantity, $material_code, $quantity);
        if (!$updateStockStmt->execute() || $updateStockStmt->affected_rows === 0) {
            throw new Exception("Failed to update stock for material_code $material_code");
        }
    }

    $insertStmt->close();
    $updateStockStmt->close();

    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Materials used saved and stock updated successfully']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
