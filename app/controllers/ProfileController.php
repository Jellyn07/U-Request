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

    // Update contact number with validation
    public function saveContact($req_id, $contact) {
        //  Check uniqueness across other tables
        if ($this->model->contactExistsElsewhere($contact)) {
            $_SESSION['error'] = "Contact number already exists in the system.";
            return false;
        }

        // 3. Update contact
        return $this->model->updateContact($req_id, $contact);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {

    $email = $_SESSION['email'];
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $controller = new ProfileController();

    // 1. Check if old password is correct
    if (!$controller->savePassword($email, $oldPassword, $oldPassword)) {
        $_SESSION["alert"] = [
            "title" => "Failed",
            "message" => "Old password is incorrect.",
            "icon" => "error"
        ];
    }
    // 2. Check if new password matches confirmation
    elseif ($newPassword !== $confirmPassword) {
        $_SESSION["alert"] = [
            "title" => "Failed",
            "message" => "New passwords do not match.",
            "icon" => "error"
        ];
    }
    // 3. Update password
    else {
        if ($controller->savePassword($email, $oldPassword, $newPassword)) {
            $_SESSION["alert"] = [
                "title" => "Success",
                "message" => "Password changed successfully.",
                "icon" => "success"
            ];
        } else {
            $_SESSION["alert"] = [
                "title" => "Failed",
                "message" => "Unable to update password. Try again.",
                "icon" => "error"
            ];
        }
    }

    header("Location: /app/modules/user/views/profile.php");
    exit;
}

//Update Pic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_picture') {

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {

        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExts)) {

            // Generate unique filename
            $baseName = pathinfo($fileName, PATHINFO_FILENAME);
            $newFileName = $baseName . '_' . time() . '.' . $fileExtension;

            // Upload directory (server filesystem path)
            $uploadDir = __DIR__ . '/../../public/uploads/profile_pics/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $destPath = $uploadDir . $newFileName;
            $relativePath = '/public/uploads/profile_pics/' . $newFileName; // browser-accessible path

            if (move_uploaded_file($fileTmpPath, $destPath)) {

                $profileController = new ProfileController();

                // Optionally get old profile picture path to delete old file
                $oldPic = $profileController->getProfile($email);
                if (!empty($oldPic) && !str_contains($oldPic, 'user-default.png')) {
                    $oldFile = $_SERVER['DOCUMENT_ROOT'] . $oldPic;
                    if (file_exists($oldFile)) unlink($oldFile);
                }

                // Save new profile picture in DB
                if ($profileController->saveProfilePicture($email, $baseName)) {
                    $_SESSION['success'] = "Profile picture updated successfully.";
                } else {
                    $_SESSION['error'] = "Failed to update profile picture in database.";
                    unlink($destPath); // rollback
                }

            } else {
                $_SESSION['error'] = "Failed to move uploaded file.";
            }

        } else {
            $_SESSION['error'] = "Invalid file type. Allowed types: " . implode(", ", $allowedExts);
        }

    } else {
        $_SESSION['error'] = "No file uploaded or upload error.";
    }

    // Redirect back to profile page
    header("Location: /app/modules/user/views/profile.php");
    exit();
}


if (isset($_POST['action'])) {

    $controller = new ProfileController();
    $email = $_SESSION['email'];
    $req_id = $_SESSION['req_id'];

    $messages = [];
    $icon = "success";

    switch ($_POST['action']) {

        case 'update_contact':
            $office = $_POST['officeOrDept'];
            $contact = $_POST['contact_no'];

            // Get current profile for comparison
            $profile = $controller->getProfile($email);

            // Only update office if it changed
            if ($office !== $profile['officeOrDept']) {
                if ($controller->saveOfficeOrDept($email, $office)) {
                    $messages[] = "Program/Office updated successfully.";
                } else {
                    $messages[] = "Failed to update Program/Office.";
                    $icon = "error";
                }
            }

            // Only update contact if it changed
            if ($contact !== $profile['contact']) {
                if ($controller->saveContact($req_id, $contact)) {
                    $messages[] = "Contact number updated successfully.";
                } else {
                    $messages[] = "Contact number already exists.";
                    $icon = "error";
                }
            }

            if (empty($messages)) {
                $_SESSION["alert"] = [
                    "title" => "No Changes",
                    "message" => "No updates were made.",
                    "icon" => "info"
                ];
            } else {
                $_SESSION["alert"] = [
                    "title" => "Update Result",
                    "message" => implode(" ", $messages),
                    "icon" => $icon
                ];
            }

            break;
    }

    header("Location: /app/modules/user/views/profile.php");
    exit;
}

