<?php
session_start();
require_once __DIR__ . '/../models/RequestModel.php';
require_once __DIR__ . '/../config/constants.php';

class RequestController {
    private $model;

    public function __construct() {
        $this->model = new RequestModel();
    }

    public function submitRequest() {
        // Tracking ID
        $tracking_id = 'TRK-' . date("Ymd") . '-' . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 5);

        // Collect inputs (matching your form names)
        $nature       = $_POST['nature-request'] ?? '';
        $req_id       = $_POST['req_id'] ?? 1; // Replace with actual logged-in requester ID
        $description  = $_POST['description'] ?? '';
        $unit         = $_POST['unit'] ?? '';
        $building     = $_POST['exLocb'] ?? '';
        $room         = $_POST['exLocr'] ?? '';
        $location    = $building . ' - ' . $room;
        $dateNoticed  = $_POST['dateNoticed'] ?? date('Y-m-d');

        $fileName = $_FILES['picture']['name'];
        $fileTmp  = $_FILES['picture']['tmp_name'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Allowed file extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExt, $allowedExtensions)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Only JPG, JPEG, PNG, and GIF files are allowed.'
                });
            </script>";
            exit;
        }

        $filePath = null;
        if (!empty($fileName)) {
            // Save files inside: /public/uploads/
            $targetDir = __DIR__ . '/../../public/uploads/';

            // Make sure the folder exists
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Add unique ID before filename (avoid overwrite)
            $newFileName = uniqid("img_", true) . "." . $fileExt;

            // Full server path
            $targetPath = $targetDir . $newFileName;

            // Move file
            if (move_uploaded_file($fileTmp, $targetPath)) {
                // Store relative path for DB
                $filePath = '/public/uploads/' . $newFileName;
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: 'There was a problem uploading your file.'
                    });
                </script>";
                exit;
            }
        }


        // Save to DB
        $request_id = $this->model->createRequest(
            $tracking_id, $nature, $req_id, $description,
            $unit, $location, $dateNoticed, $filePath
        );

        if ($request_id) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Request Submitted',
                'message' => "Your tracking ID: {$tracking_id}",
                'redirect' => "/app/modules/user/views/request.php"
            ];
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Submission Failed',
                'message' => 'Something went wrong. Please try again.',
                'redirect' => "/app/modules/user/views/request.php"
            ];
        }

        header("Location: ../modules/user/views/request.php");
        exit;


    }
}

// Run controller on form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new RequestController();
    $controller->submitRequest();
}
