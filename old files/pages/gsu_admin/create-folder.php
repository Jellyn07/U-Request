<?php
header('Content-Type: application/json');

// Get folder name from JSON input
$data = json_decode(file_get_contents("php://input"), true);
$folder = isset($data['folder']) ? basename(trim($data['folder'])) : '';

if ($folder === '') {
    echo json_encode(['success' => false, 'message' => 'Folder name is required.']);
    exit;
}

$baseDir = __DIR__ . '/uploads'; // 🔁 consistent with get-folders.php
$folderPath = $baseDir . '/' . $folder;

// Check if folder already exists
if (is_dir($folderPath)) {
    echo json_encode(['success' => false, 'message' => 'Folder already exists.']);
    exit;
}

// Try to create the folder
if (mkdir($folderPath, 0777, true)) {
    echo json_encode(['success' => true, 'message' => "Folder '$folder' created successfully."]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create folder.']);
}
?>
