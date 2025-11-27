<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// filepath: app/core/BaseModel.php

// class BaseModel {
//     public $db;

//     public function __construct() {
//         $this->db = new mysqli("localhost", "root", "", "u_request");
//         if ($this->db->connect_errno) {
//             die("DB Connection failed: " . $this->db->connect_error);
//         }
//     }

//     public function __destruct() {
//         if ($this->db !== null) {
//             $this->db->close();
//         }
//     }
// }

class BaseModel {
    protected $db;
    protected $host = 'localhost';
    protected $user = 'root';
    protected $pass = '';
    protected $name = 'u_request';
    protected $backupFolder;

    public function __construct() {
        $this->backupFolder = __DIR__ . '/../../backups/';
        $this->connect();
    }

    // 🔹 Connect to database
    private function connect() {
        $this->db = @new mysqli($this->host, $this->user, $this->pass, $this->name);
        if ($this->db->connect_error) {
            // Attempt auto-restore if DB fails
            $this->autoRestore();
        }
    }

    // 🔹 Backup database
    // ✅ Send email notification
    public function sendBackupEmail($success, $file = null, $message = '') {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'jonagujol@gmail.com';
            $mail->Password = 'wqhb eszj mxiz rmmh'; // generate App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('jonagujol@gmail.com', 'Database Backup System');
            $mail->addAddress('jsgujol00060@usep.edu.ph', 'Admin'); // recipient email

            // Content
            $mail->isHTML(true);
            $mail->Subject = $success ? 'Database Backup Successful' : 'Database Backup Failed';

            $color = $success ? '#16a34a' : '#dc2626'; // green / red
            $bg    = $success ? '#ecfdf5' : '#fef2f2'; // light background

            $body = "
            <!DOCTYPE html>
            <html>
            <body style='margin:0;background:#f3f4f6;font-family:Arial,sans-serif;padding:40px;'>

            <div style='max-width:520px;margin:auto;background:white;border-radius:8px;
                        padding:24px;box-shadow:0 4px 10px rgba(0,0,0,.05);'>

                <h2 style='margin:0;font-size:18px;color:$color;'>
                ".($success ? "Backup Successful ✅" : "Backup Failed ❌")."
                </h2>

                <div style='width:40px;height:3px;background:$color;border-radius:5px;margin:12px 0;'></div>

                <p style='font-size:14px;color:#374151;'>Hello Admin,</p>

                <p style='font-size:14px;color:#374151;'>
                This is an automated notification from your database backup system.
                </p>

                <div style='background:$bg;border-left:4px solid $color;
                            padding:12px;border-radius:6px;margin:14px 0;font-size:13px;'>

                ".($success
                    ? "✅ Backup file created: <b>$file</b>"
                    : "❌ Backup error: <b>$message</b>"
                )."

                </div>

                <p style='font-size:13px;color:#374151;'>Please review if necessary.</p>

                <p style='font-size:13px;color:#374151;'>
                Regards,<br>
                <b>Database Backup System</b>
                </p>

                <p style='margin-top:16px;font-size:11px;color:#9ca3af;text-align:center;'>
                This is an automated message. Do not reply.
                </p>

            </div>

            </body>
            </html>
            ";

            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            echo "Mailer Error: " . $e->getMessage();
        }

    }

    // Modified backupDatabase method
    public function backupDatabase() {
        // Ensure backup folder exists
        if (!is_dir($this->backupFolder)) mkdir($this->backupFolder, 0777, true);

        $filename = $this->backupFolder . "db_backup_" . date('Ymd_His') . ".sql";
        $mysqldumpPath = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

        // Check if mysqldump exists
        if (!file_exists($mysqldumpPath)) {
            return ["success" => false, "message" => "mysqldump not found at $mysqldumpPath"];
        }

        // Build command for Windows with proper quotes
        $command = "\"$mysqldumpPath\" " .
            "--user={$this->user} " .
            "--password={$this->pass} " .
            "--host={$this->host} " .
            "--routines " .        // stored procedures & functions
            "--events " .          // events
            "--triggers " .        // triggers
            "--add-drop-database " .
            "--add-drop-table " .
            "--create-options " .
            "--single-transaction " .
            "--databases {$this->name} " .
            "> \"$filename\"";

        // Execute command and capture output
        exec($command . " 2>&1", $output, $returnVar);

        // Log output for debugging
        error_log("Backup command: $command");
        error_log("Backup output: " . print_r($output, true));
        error_log("Backup return code: $returnVar");

        // Check if backup succeeded
        if ($returnVar === 0 && file_exists($filename) && filesize($filename) > 0) {
            try {
                $this->sendBackupEmail(true, $filename);
            } catch (\Exception $e) {
                error_log("Backup email failed: " . $e->getMessage());
            }
            return ["success" => true, "file" => $filename];
        } else {
            $errorMessage = implode("\n", $output);
            try {
                $this->sendBackupEmail(false, null, $errorMessage);
            } catch (\Exception $e) {
                error_log("Backup email failed: " . $e->getMessage());
            }
            return ["success" => false, "message" => "Backup failed. Output:\n$errorMessage"];
        }
    }

    // 🔹 Restore database from a given file
    public function restoreDatabase($file) {
        if (!file_exists($file)) {
            return ["success" => false, "message" => "Backup file does not exist"];
        }

        $mysqlPath = "C:\\xampp\\mysql\\bin\\mysql.exe";

        $tempFile = tempnam(sys_get_temp_dir(), 'restore_') . '.sql';
        $contents = file_get_contents($file);
        $contents = "SET FOREIGN_KEY_CHECKS=0;\n" . $contents . "\nSET FOREIGN_KEY_CHECKS=1;";
        file_put_contents($tempFile, $contents);

        $command = "\"$mysqlPath\" --user={$this->user} --password={$this->pass} --host={$this->host} {$this->name} < \"$tempFile\"";

        exec($command . " 2>&1", $output, $returnVar);

        unlink($tempFile);

        if ($returnVar === 0) {
            return ["success" => true, "file" => $file];
        } else {
            $errorMessage = implode("\n", $output);
            return ["success" => false, "message" => "Restore failed. Output:\n$errorMessage"];
        }
    }

    // 🔹 Auto-restore if DB connection fails
    private function autoRestore() {
        $files = glob($this->backupFolder . "*.sql");
        if (!empty($files)) {
            rsort($files);
            $latest = $files[0];
            $result = $this->restoreDatabase($latest);
            if ($result['success']) {
                $this->connect(); // reconnect after restore
            } else {
                die("Database connection failed and restore failed.");
            }
        } else {
            die("Database connection failed and no backup available.");
        }
    }

    // 🔹 Optional: Delete older backups, keep only N
    public function cleanOldBackups($keep = 5) {
        $files = glob($this->backupFolder . "*.sql");
        if (count($files) > $keep) {
            rsort($files);
            $filesToDelete = array_slice($files, $keep);
            foreach ($filesToDelete as $file) unlink($file);
        }
    }
}
