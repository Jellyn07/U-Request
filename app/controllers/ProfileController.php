<?php
require_once __DIR__ . '/../models/ProfileModel.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . "/../models/ProfileModel.php"; 

class ProfileController {
    private $model;

    public function __construct() {
        $this->model = new ProfileModel();
    }

    // Load profile info
    public function getProfile($requester_email) {
        return $this->model->getProfileByEmail($requester_email);
    }

    // Save department/office change
    public function saveOfficeOrDept($requester_email, $officeOrDept) {
        return $this->model->updateOfficeOrDept($officeOrDept, $requester_email);
    }

    // Save new profile picture
    public function saveProfilePicture($requester_email, $filePath) {
        return $this->model->updateProfilePicture($requester_email, $filePath);
    }

    // Update Password
    public function savePassword($email, $oldPassword, $newPassword) {
        return $this->model->savePassword($email, $oldPassword, $newPassword);
    }
}

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new ProfileController();
        $requester_email = $_POST['requester_email'] ?? null;

        // --- Office/Dept update ---
        if (isset($_POST['officeOrDept'])) {
        $officeOrDept = $_POST['officeOrDept'];
        $requester_email = $_POST['requester_email'] ?? null;

        $success = $controller->saveOfficeOrDept($requester_email, $officeOrDept);

        if ($success) {
            header("Location: /app/modules/user/views/profile.php?success=1");
        } else {
            header("Location: /app/modules/user/views/profile.php?error=1");
        }
        exit();
        }
    }

    // --- Password update ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$requester_email) {
        header("Location: /app/modules/user/views/profile.php?error=not_logged_in");
        exit();
    }

    // --- Picture update ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new ProfileController();
        $requester_email = $_SESSION['email'] ?? null;

        if (isset($_POST['action']) && $_POST['action'] === 'upload_picture') {
            if (!empty($_FILES['profile_picture']['tmp_name'])) {
                $targetDir = __DIR__ . "/../../public/uploads/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $fileName = uniqid() . "_" . basename($_FILES["profile_picture"]["name"]);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                    $dbPath = "/public/uploads/" . $fileName;
                    $success = $controller->saveProfilePicture($requester_email, $dbPath);

                    if ($success) {
                        header("Location: /app/modules/user/views/profile.php?success=pic_updated");
                    } else {
                        header("Location: /app/modules/user/views/profile.php?error=db_update_failed");
                    }
                    exit();
                } else {
                    header("Location: /app/modules/user/views/profile.php?error=upload_failed");
                    exit();
                }
            } else {
                header("Location: /app/modules/user/views/profile.php?error=no_file");
                exit();
            }
        }
    }
}

class ProfileController1 {
    private $model;

    public function __construct() {
        $this->model = new ProfileModel();
    }

    public function savePassword($email, $oldPassword, $newPassword) {
        return $this->model->savePassword($email, $oldPassword, $newPassword);
    }
}

$controller = new ProfileController();
$requester_email = $_SESSION['email'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$requester_email) {
        header("Location: /app/modules/user/views/profile.php?error=not_logged_in");
        exit();
    }

    $action = $_POST['action'] ?? '';

    // --- Password change ---
    if ($action === 'change_password') {
        $old = $_POST['old_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // 1. Check match
        if ($new !== $confirm) {
            header("Location: /app/modules/user/views/profile.php?error=Passwords+do+not+match");
            exit();
        }

        // 2. Enforce stronger password rules
        if (strlen($new) < 8) {
            header("Location: /app/modules/user/views/profile.php?error=Password+must+be+at+least+8+characters");
            exit();
        }

        // 3. Save password securely
        $success = $controller->savePassword($requester_email, $old, $new);

        if ($success) {
            session_destroy(); // force re-login
            header("Location: /app/modules/user/views/login.php?success=Password+updated");
        } else {
            header("Location: /app/modules/user/views/profile.php?error=Old+password+incorrect");
        }
        exit();
    }
}

