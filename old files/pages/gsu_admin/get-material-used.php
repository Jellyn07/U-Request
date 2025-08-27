<?php
header('Content-Type: application/json');

if (!isset($_GET['reqAssignment_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing ID']);
    exit;
}

$reqAssignmentId = intval($_GET['reqAssignment_id']);

$conn = new mysqli('localhost', 'root', '', 'utrms_db');

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit;
}

$sql = "SELECT rm.material_code, m.material_desc, rm.quantity_needed 
        FROM request_materials_needed rm
        JOIN materials m ON rm.material_code = m.material_code
        WHERE rm.reqAssignment_id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reqAssignmentId);
$stmt->execute();
$result = $stmt->get_result();

$materials = [];
while ($row = $result->fetch_assoc()) {
    $materials[] = $row;
}

echo json_encode(['success' => true, 'materials' => $materials]);
