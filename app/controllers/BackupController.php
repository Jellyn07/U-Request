<?php
session_start();
require_once __DIR__ . '/../core/BaseModel.php';

class BackupController {

    private $model;

    public function __construct() {
        $this->model = new BaseModel();
    }

    // Handle requests
    public function handleRequest() {
        $action = $_GET['action'] ?? null;

        if ($action === 'backup') {
            $this->backup();
        } elseif ($action === 'restore') {
            $this->restore();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action.'
            ]);
        }
    }

    // Backup database
    private function backup() {
        $backupFile = $this->model->backupDatabase();
        if ($backupFile) {
            echo json_encode([
                'success' => true,
                'file' => $backupFile,
                'message' => 'Backup completed successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Backup failed.'
            ]);
        }
    }

    // Restore database
    private function restore() {
        $backupFile = $_GET['file'] ?? null;
        if (!$backupFile) {
            echo json_encode([
                'success' => false,
                'message' => 'No backup file specified.'
            ]);
            return;
        }

        $restore = $this->model->restoreDatabase($backupFile);
        if ($restore) {
            echo json_encode([
                'success' => true,
                'message' => 'Database restored successfully from ' . $backupFile
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Restore failed.'
            ]);
        }
    }
}

// Execute
$controller = new BackupController();
$controller->handleRequest();
