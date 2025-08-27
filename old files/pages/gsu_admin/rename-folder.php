<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$old = $data['oldName'];
$new = $data['newName'];

$basePath = __DIR__ . '/uploads';
$oldPath = "$basePath/$old";
$newPath = "$basePath/$new";

if (!is_dir($oldPath)) {
    echo json_encode(['success' => false, 'message' => 'Folder not found.']);
} elseif (is_dir($newPath)) {
    echo json_encode(['success' => false, 'message' => 'Folder name already exists.']);
} elseif (rename($oldPath, $newPath)) {
    echo json_encode(['success' => true, 'message' => 'Folder renamed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to rename folder.']);
}
