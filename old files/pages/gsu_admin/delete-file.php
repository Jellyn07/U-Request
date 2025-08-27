<?php
$conn = new mysqli("localhost", "root", "", "utrms_db");
if ($conn->connect_error) die(json_encode(['success' => false, 'message' => 'DB error.']));

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$filepath = $data['filepath'];

if (file_exists($filepath)) {
    unlink($filepath); // delete file from folder
}

$stmt = $conn->prepare("DELETE FROM uploaded_files WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'File deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete from database.']);
}
