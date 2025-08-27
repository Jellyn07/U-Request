<?php
header('Content-Type: application/json');

$uploadBase = __DIR__ . '/uploads';
$folders = [];

if (is_dir($uploadBase)) {
    $items = scandir($uploadBase);
    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..' && is_dir($uploadBase . '/' . $item)) {
            $folders[] = ['name' => $item];
        }
    }
}

echo json_encode($folders);
?>
