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
        parent::__construct(); // ✅ this initializes $this->db from BaseModel
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


    // Save new profile picture
    public function verifyPassword($email, $filePath)
    {
        return $this->model->verifyPassword($email, $filePath);
    }

    //check if there is an existing profile
    public function getProfilePicture($email)
    {
        $stmt = $this->db->prepare("SELECT profile_picture FROM administrator WHERE email = ?");
        if (!$stmt) {
            // Optional: log or handle prepare() failure
            return null;
        }

        $stmt->bind_param("s", $email); // "s" = string
        $stmt->execute();

        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return !empty($row['profile_picture']) ? $row['profile_picture'] : null;
        }

        return null;
    }
}

// AJAX endpoint: verify old password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify_old_password') {
    $email = $_SESSION['email'];
    $oldPassword = $_POST['old_password'] ?? '';

    $controller = new AdminProfileController();

    if ($controller->verifyPassword($email, $oldPassword)) {
        echo json_encode(["valid" => true]);
    } else {
        echo json_encode(["valid" => false]);
    }
    exit;
}


// Update Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {

    $email = $_SESSION['email'];
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $controller = new AdminProfileController();

    if ($controller->savePassword($email, $oldPassword, $newPassword)) {
        $_SESSION['success'] = "Password updated successfully.";
    } else {
        $_SESSION['error'] = "Old password is incorrect or update failed.";
    }
    $redirect = $_SERVER['HTTP_REFERER'] ?? '/'; // Go back to the page where the request came from

            header("Location: $redirect");
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
            $uploadFileDir = __DIR__ . '/../../public/uploads/profile_pics/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $destPath = $uploadFileDir . $newFileName;
            $relativePath = '/uploads/profile_pics/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $controller = new AdminProfileController();

                // Get old picture path from DB before saving new one
                $oldProfilePic = $controller->getProfilePicture($email);

                // Only try to delete if the old profile pic exists and isn't the default
                if (!empty($oldProfilePic) && $oldProfilePic !== '/uploads/profile_pics/default.png') {
                    $oldFilePath = $_SERVER['DOCUMENT_ROOT'] . $oldProfilePic;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // Save new picture path in DB
                if ($controller->saveProfilePicture($email, $newFileName)) {
                    $_SESSION['success'] = "Profile picture updated successfully.";
                } else {
                    $_SESSION['error'] = "Failed to update profile picture in database.";
                    // Rollback by deleting the newly uploaded file
                    if (file_exists($destPath)) {
                        unlink($destPath);
                    }
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

    $redirect = $_SERVER['HTTP_REFERER'] ?? '/'; // Go back to the page where the request came from

            header("Location: $redirect");
            exit;
}
