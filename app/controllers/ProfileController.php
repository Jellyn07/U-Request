<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/ProfileModel.php';
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/constants.php';

class ProfileController extends BaseModel
{
    private $model;

    public function __construct()
    {
        $this->model = new ProfileModel();
    }

    // Load profile info
    public function getProfile($requester_email)
    {
        return $this->model->getProfileByEmail($requester_email);
    }

    // Save department/office change
    public function saveOfficeOrDept($requester_email, $officeOrDept)
    {
        return $this->model->updateOfficeOrDept($requester_email, $officeOrDept);
    }

    // Save new profile picture
    public function saveProfilePicture($code, $filenameOnly, $originalFileName)
    {
        return $this->model->updateProfilePicture($code, $filenameOnly, $originalFileName);
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

    // Update contact number with validation
    public function saveContact($req_id, $contact)
    {
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

            // Full path for the new file
            $destPath = $uploadDir . $newFileName;
            // Relative URL for the file (for use in the browser)
            $relativePath = '/public/uploads/profile_pics/' . $newFileName;

            $profileController = new ProfileController();

            // Fetch current profile picture filename from DB
            $currentProfilePic = $profileController->getProfile($code)['profile_pic'] ?? null;

            // If the profile picture already exists, use the existing one (skip upload)
            if ($currentProfilePic && file_exists($uploadDir . $currentProfilePic)) {
                // Use the existing profile picture without re-uploading
                $_SESSION['success'] = "Profile picture is already set.";
                header("Location: /app/modules/user/views/profile.php");
                exit();
            }

            // If the file doesn't exist in the directory, move the uploaded file
            if (move_uploaded_file($fileTmpPath, $destPath)) {

                // Save the filename in the database
                $filenameOnly = basename($newFileName); // Just the file name without the directory

                // Optionally, delete the old profile picture if it exists
                if ($currentProfilePic && !str_contains($currentProfilePic, 'user-default.png')) {
                    $oldFilePath = $uploadDir . $currentProfilePic;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath); // Delete the old file
                    }
                }

                // Save the new profile picture in DB
                if ($profileController->saveProfilePicture($email, $filenameOnly, $newFileName) && $profileController->saveProfilePicture($code, $filenameOnly, $newFileName)) {
                    $_SESSION['success'] = "Profile picture updated successfully.";
                } else {
                    $_SESSION['error'] = "Failed to update profile picture in the database.";
                    unlink($destPath); // Rollback by deleting the uploaded file
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
