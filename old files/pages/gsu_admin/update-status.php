<?php
header('Content-Type: application/json');

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if (isset($data['request_id']) && isset($data['status'])) {
    $request_id = $data['request_id'];
    $status = $data['status'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "utrms_db");

    if ($conn->connect_error) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    // Prepare the SQL statement based on the status
    if ($status == 'Completed') {
        // If the status is "Completed", also update the `date_finished` field to the current timestamp
        $stmt = $conn->prepare("UPDATE request_assignment SET req_status = ?, date_finished = NOW() WHERE request_id = ?");
    } else {
        // Otherwise, only update the `req_status`
        $stmt = $conn->prepare("UPDATE request_assignment SET req_status = ? WHERE request_id = ?");
    }

    if ($stmt === false) {
        echo json_encode(['error' => 'Failed to prepare the statement']);
        exit;
    }

    // Bind parameters (the second parameter is an integer for the request_id)
    if ($status == 'Completed') {
        $stmt->bind_param("si", $status, $request_id);
    } else {
        $stmt->bind_param("si", $status, $request_id);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => 'Status updated successfully']);
    } else {
        // Log error to file
        error_log("Failed to execute query: " . $stmt->error);
        echo json_encode(['error' => 'Failed to update status: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Invalid input']);
}
?>
