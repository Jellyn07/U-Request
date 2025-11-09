<?php

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
            $mail->addAddress('jonagujol@gmail.com', 'Admin'); // recipient email

            // Content
            $mail->isHTML(true);
            $mail->Subject = $success ? 'Database Backup Successful' : 'Database Backup Failed';
            $body = $success 
                ? "Backup created successfully!<br>File: <b>$file</b>"
                : "Backup failed!<br>Reason: <b>$message</b>";
            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            error_log("Backup email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

    // Modified backupDatabase method
  public function backupDatabase() {
    if (!is_dir($this->backupFolder)) mkdir($this->backupFolder, 0777, true);

    $filename = $this->backupFolder . "db_backup_" . date('Ymd_His') . ".sql";
    $mysqldumpPath = "C:\\xampp\\mysql\\bin\\mysqldump.exe"; // 👈 FULL PATH
    $command = "\"$mysqldumpPath\" --user={$this->user} --password={$this->pass} --host={$this->host} --routines --events --triggers --add-drop-database --databases {$this->name} > \"$filename\"";


    exec($command, $output, $returnVar);

    if ($returnVar === 0 && filesize($filename) > 0) {
        $this->sendBackupEmail(true, $filename);
        return ["success" => true, "file" => $filename];
    } else {
        $this->sendBackupEmail(false, null, "Backup command failed or empty file.");
        return ["success" => false, "message" => "Backup failed"];
    }
}




    // 🔹 Restore database from a given file
    public function restoreDatabase($file = null) {
        if (!$file) {
            $files = glob($this->backupFolder . "*.sql");
            if (empty($files)) return ["success" => false, "message" => "No backup found"];
            rsort($files); // latest backup first
            $file = $files[0];
        }

        $command = "mysql --user={$this->user} --password={$this->pass} --host={$this->host} {$this->name} < $file";
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return ["success" => true, "file" => $file];
        } else {
            return ["success" => false, "message" => "Restore failed"];
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
