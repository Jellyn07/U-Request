<?php
$conn = new mysqli("localhost", "root", "", "utrms_db");
if ($conn->connect_error) die(json_encode(['success' => false, 'message' => 'DB error.']));

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$newName = basename($data['newName']); // sanitize

// Get current file info
$result = $conn->query("SELECT * FROM uploaded_files WHERE id = $id");
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'File not found.']);
    exit;
}

$row = $result->fetch_assoc();
$oldPath = $row['filepath'];
$oldName = $row['filename'];
$ext = pathinfo($oldName, PATHINFO_EXTENSION);

// Keep extension
if (pathinfo($newName, PATHINFO_EXTENSION) !== $ext) {
    $newName .= "." . $ext;
}

$newPath = dirname($oldPath) . '/' . time() . "_" . $newName;

if (file_exists($oldPath) && rename($oldPath, $newPath)) {
    $stmt = $conn->prepare("UPDATE uploaded_files SET filename = ?, filepath = ? WHERE id = ?");
    $stmt->bind_param("ssi", $newName, $newPath, $id);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'File renamed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to rename file.']);
}
?>
