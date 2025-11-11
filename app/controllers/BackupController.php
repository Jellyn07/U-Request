<?php
session_start();
require_once __DIR__ . '/../core/BaseModel.php';

class BackupController {
    private $model;

    public function __construct() {
        $this->model = new BaseModel();
    }

    public function handleRequest() {
        // Manual actions from form buttons
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['backup_now'])) {
                $result = $this->model->backupDatabase();
                $_SESSION['backup_status'] = $result['success'] ? 'backup_success' : 'error';
                header("Location: /app/modules/superadmin/views/backup.php");
                exit;
            }
            $uploadedFile = null;
            if (isset($_POST['restore_now']) && isset($_FILES['restore_file'])) {
                $uploadDir = __DIR__ . '/../../backups/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                // Store uploaded file in variable
                $uploadedFile = $uploadDir . basename($_FILES['restore_file']['name']);
                move_uploaded_file($_FILES['restore_file']['tmp_name'], $uploadedFile);

                // Pass the file path to restoreDatabase
                $result = $this->model->restoreDatabase($uploadedFile);

                $_SESSION['backup_status'] = $result['success'] ? 'restore_success' : 'error';
                header("Location: /app/modules/superadmin/views/backup.php");
                exit;
            }
        }

        // Automatic (JS fetch)
        $action = $_GET['action'] ?? null;
        if ($action === 'backup') {
            $result = $this->model->backupDatabase();
            echo json_encode($result);
        } elseif ($action === 'restore') {
            $result = $this->model->restoreDatabase($uploadedFile);
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }
}

$controller = new BackupController();
$controller->handleRequest();
