<?php
$conn = new mysqli("localhost", "root", "", "utrms_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get folder name from form data (can be empty)
$folder = isset($_POST['folder']) && $_POST['folder'] !== '' ? basename($_POST['folder']) : null;

// Set upload directory
$baseDir = 'uploads/';
$uploadDir = $folder ? $baseDir . $folder . '/' : $baseDir;

// Create folder if it doesn't exist
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Prepare file path
$fileName = basename($_FILES['file']['name']);
$targetPath = $uploadDir . time() . "_" . $fileName;

if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
    // Save to DB with folder (can be null)
    $stmt = $conn->prepare("INSERT INTO uploaded_files (filename, filepath, folder) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fileName, $targetPath, $folder);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'File uploaded successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Upload failed.']);
}
?>

