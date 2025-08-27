<?php
header('Content-Type: application/json');

$folder = basename($_GET['folder'] ?? '');
$path = __DIR__ . '/uploads/' . $folder;
$files = [];

if (is_dir($path)) {
    foreach (scandir($path) as $file) {
        if ($file !== '.' && $file !== '..' && is_file("$path/$file")) {
            $files[] = ['filename' => $file, 'filepath' => "uploads/$folder/$file"];
        }
    }
}

echo json_encode($files);
