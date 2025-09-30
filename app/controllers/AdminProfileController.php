<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/AdminProfileModel.php';
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/constants.php';

class AdminProfileController extends BaseModel
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminProfileModel();
    }

    // Load profile info
    public function getProfile($admin_email)
    {
        return $this->model->getProfileByEmail($admin_email);
    }

    // Save new profile picture
    public function saveProfilePicture($email, $filePath)
    {
        return $this->model->updateProfilePicture($email, $filePath);
    }

    // Save password update
    public function savePassword($requester_email, $oldPassword, $newPassword)
    {
        $profile = $this->model->getProfileByEmail($requester_email);

        // Verify old password using encryption
        if (!$this->model->verifyPassword($requester_email, $oldPassword)) {
            return false; // old password incorrect
        }

        return $this->model->updatePassword($requester_email, $newPassword);
    }

    // Delete account
    public function deleteAccount($requester_id)
    {
        return $this->model->deleteAccount($requester_id);
    }
}

// Update Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $email = $_SESSION['email'];
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $controller = new AdminProfileController(); // ✅ fixed class name

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "New passwords do not match.";
    } else {
        if ($controller->savePassword($email, $oldPassword, $newPassword)) {
            $_SESSION['success'] = "Password updated successfully.";
        } else {
            $_SESSION['error'] = "Old password is incorrect or update failed.";
        }
    }

    header("Location: /app/modules/gsu_admin/views/profile.php");
    exit;
}

// Update Profile Picture
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_picture') {
    $email = $_SESSION['email'];

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExts)) {
            $newFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . $fileExtension;
            $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/profile_pics/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $destPath = $uploadFileDir . $newFileName;
            $relativePath = '/uploads/profile_pics/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $controller = new AdminProfileController();
                if ($controller->saveProfilePicture($email, $relativePath)) {
                    $_SESSION['success'] = "Profile picture updated successfully.";
                } else {
                    $_SESSION['error'] = "Failed to update profile picture in database.";
                }
            } else {
                $_SESSION['error'] = "Error moving uploaded file.";
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Allowed: " . implode(", ", $allowedExts);
        }
    } else {
        $_SESSION['error'] = "No file uploaded or upload error.";
    }

    header("Location: /app/modules/gsu_admin/views/profile.php");
    exit;
}
