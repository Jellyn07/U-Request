<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/ProfileModel.php';
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/constants.php';

class ProfileController extends BaseModel {
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
        return $this->model->updateOfficeOrDept($requester_email, $officeOrDept);
    }

    // Save new profile picture
    public function saveProfilePicture($filePath, $originalFileName) {
        return $this->model->updateProfilePicture( $filePath, $originalFileName);
    }

    // Save password update
    public function savePassword($requester_email, $oldPassword, $newPassword) {
        $profile = $this->model->getProfileByEmail($requester_email);

        // Verify old password using encryption
        if (!$this->model->verifyPassword($requester_email, $oldPassword)) {
            return false; // old password incorrect
        }

        return $this->model->updatePassword($requester_email, $newPassword);
    }

    // Delete account
    public function deleteAccount($requester_id) {
        return $this->model->deleteAccount($requester_id);
    }

    // Update full profile
    public function updateProfile($data) {
        $sql = "UPDATE requester SET 
                    requester_id = ?, 
                    firstName = ?, 
                    lastName = ?, 
                    officeOrDept = ?, 
                    contact_no = ?, 
                    accessLevel_id = ?
                WHERE email = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new Exception("SQL Prepare failed: " . $this->db->error);
        }

        return $stmt->execute([
            $data['requester_id'],
            $data['firstName'],
            $data['lastName'],
            $data['officeOrDept'],
            $data['contact_no'] ?? null,
            $data['accessLevel_id'] ?? null,
            $data['email']
        ]);
    }
}

// Update Password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {

    $email = $_SESSION['email'];
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $controller = new ProfileController();

    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "New passwords do not match.";
    } else {
        if ($controller->savePassword($email, $oldPassword, $newPassword)) {
            $_SESSION['success'] = "Password updated successfully.";
        } else {
            $_SESSION['error'] = "Old password is incorrect or update failed.";
        }
    }

    header("Location: /app/modules/user/views/profile.php");
    exit;
}



//Update Pic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_picture') {

    $code = $_SESSION['email']; // assuming user ID is stored in session

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {

        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExts)) {

            $baseFileName = pathinfo($fileName, PATHINFO_FILENAME); // original filename without extension
            $newFileName = $baseFileName . '_' . time() . '.' . $fileExtension;
            $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/profile_pics/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $relativePath = '/uploads/profile_pics/' . $newFileName;

                // ✅ Use controller method instead of $model
                $profileController = new ProfileController();
                $originalFileName = $fileName;
                if ($profileController->saveProfilePicture($code, $relativePath, $originalFileName)) {
                    $_SESSION['success'] = "Profile picture updated successfully.";
                } else {
                    $_SESSION['error'] = "Failed to update profile picture in database.";
                }
            } else {
                $_SESSION['error'] = "Error moving the uploaded file.";
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Allowed: " . implode(", ", $allowedExts);
        }
    } else {
        $_SESSION['error'] = "No file uploaded or upload error.";
    }

    header("Location: /app/modules/user/views/profile.php");
    exit;
}

//Update Office
$controller = new ProfileController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['officeOrDept'], $_POST['requester_email'])) {

    $email = $_POST['requester_email'];
    $officeOrDept = $_POST['officeOrDept'];

    if ($controller->saveOfficeOrDept($email, $officeOrDept)) {
    $_SESSION['success'] = "Department/Office updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update Department/Office.";
    }

    header("Location: /app/modules/user/views/profile.php");
    exit;
}