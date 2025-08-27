<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    $conn = new mysqli("localhost", "root", "", "utrms_db");

    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }

    if (!isset($_GET['requester_id'])) {
        throw new Exception('Requester ID not provided');
    }

    $requesterId = $conn->real_escape_string($_GET['requester_id']);

    // Log the requester ID for debugging
    error_log("Fetching history for requester ID: " . $requesterId);

    // First, get the req_id from the requester table
    $checkSql = "SELECT req_id FROM REQUESTER WHERE requester_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    if (!$checkStmt) {
        throw new Exception('Prepare check statement failed: ' . $conn->error);
    }
    
    $checkStmt->bind_param("s", $requesterId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        throw new Exception('Requester not found');
    }
    
    $reqRow = $checkResult->fetch_assoc();
    $req_id = $reqRow['req_id'];
    $checkStmt->close();

    // Now get the request history using req_id
    $sql = "SELECT 
                r.request_id, 
                r.request_Type, 
                r.location, 
                r.request_date, 
                ra.req_status,
                ra.date_finished
            FROM REQUEST r 
            LEFT JOIN REQUEST_ASSIGNMENT ra ON r.request_id = ra.request_id 
            WHERE r.req_id = ? 
            ORDER BY r.request_id DESC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Prepare main statement failed: ' . $conn->error);
    }

    $stmt->bind_param("i", $req_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    $history = [];
    while ($row = $result->fetch_assoc()) {
        // Format dates for better display
        if ($row['request_date']) {
            $row['request_date'] = date('Y-m-d', strtotime($row['request_date']));
        }
        if ($row['date_finished']) {
            $row['date_finished'] = date('Y-m-d', strtotime($row['date_finished']));
        }
        $history[] = $row;
    }

    // Log the number of records found
    error_log("Found " . count($history) . " history records");

    echo json_encode(['success' => true, 'data' => $history]);

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Error in get-user-history.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'details' => 'Check server logs for more information'
    ]);
}
?> 