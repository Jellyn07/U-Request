<?php
$conn = new mysqli("localhost", "root", "", "utrms_db");

if (isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    $stmt = $conn->prepare("SELECT image_path FROM request WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $file = $row['image_path'];
        $filepath = '../user/uploads/' . $file;

        if (file_exists($filepath)) {
            header("Content-Type: " . mime_content_type($filepath));
            readfile($filepath);
            exit;
        } else {
            http_response_code(404);
            echo "Image file not found.";
            exit;
        }
    } else {
        echo "Request not found.";
    }
} else {
    echo "No request ID specified.";
}
?>

