<?php
$conn = new mysqli("localhost", "root", "", "utrms_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$result = $conn->query("SELECT id, filename, filepath FROM uploaded_files ORDER BY uploaded_at DESC");

$files = [];
while ($row = $result->fetch_assoc()) {
    $files[] = $row;
}
echo json_encode($files);
?>
