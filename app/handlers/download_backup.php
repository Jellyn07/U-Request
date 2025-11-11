<?php
// Directory where backups are stored
$backupDir = __DIR__ . '/../../backups/'; // adjust path to your backups folder

// Get the file name from GET parameter
$file = $_GET['file'] ?? '';

// Resolve full path securely
$path = realpath($backupDir . $file);

// Security check: file exists inside backup folder
if ($path && str_starts_with($path, realpath($backupDir)) && file_exists($path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . basename($path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
}

// If file is invalid
echo "File not found!";
