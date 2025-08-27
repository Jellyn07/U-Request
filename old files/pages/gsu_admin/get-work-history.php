<?php
 $conn = new mysqli("localhost", "root", "", "utrms_db"); 

header('Content-Type: application/json');

if (!isset($_GET['staff_id'])) {
    echo json_encode([]);
    exit;
}

$staff_id = intval($_GET['staff_id']);

$sql = "SELECT request_id, request_Type, COALESCE(date_finished, 'In Progress') AS date_status 
        FROM vw_work_history 
        WHERE staff_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}

echo json_encode($history);
?>
