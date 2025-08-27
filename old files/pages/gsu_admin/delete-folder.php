<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$folder = $data['folder'];
$path = __DIR__ . '/uploads/' . $folder;

function deleteFolder($folderPath) {
    if (!file_exists($folderPath)) return true;
    foreach (scandir($folderPath) as $item) {
        if ($item === '.' || $item === '..') continue;
        $itemPath = "$folderPath/$item";
        is_dir($itemPath) ? deleteFolder($itemPath) : unlink($itemPath);
    }
    return rmdir($folderPath);
}

if (deleteFolder($path)) {
    echo json_encode(['success' => true, 'message' => 'Folder deleted.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete folder.']);
}
