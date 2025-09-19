<?php
session_start();

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../models/UserModel.php';

$login_error = "";

// ✅ Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lock_time'] = null;
}

// ✅ Check if locked
if (isset($_SESSION['lock_time']) && time() < $_SESSION['lock_time']) {
    $_SESSION['login_error'] = "Too many failed attempts. Please wait 60 seconds before trying again.";
    header("Location: ../modules/shared/views/admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = $_POST['email'] ?? '';
    $input_pass = $_POST['password'] ?? '';

    $userModel = new UserModel();
    $admin = $userModel->getAdminUserByEmail($email);

    if ($admin && ($input_pass == $admin['password'])) {
        // ✅ SUCCESS: reset attempts
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lock_time'] = null;

        $_SESSION['staff_id'] = $admin['staff_id'];
        $_SESSION['email'] = $admin['email'];
        $_SESSION['access_level'] = $admin['accessLevel_id'];

        // 🔀 Redirect based on access level
        switch ($admin['accessLevel_id']) {
            case 1:
                header("Location: ../modules/superadmin/views/dashboard.php");
                break;
            case 2:
                header("Location: ../modules/gsu_admin/views/dashboard.php");
                break;
            case 3:
                header("Location: ../modules/motorpool_admin/views/dashboard.php");
                break;
            default:
                $_SESSION['login_error'] = "Unknown access level.";
                header("Location: ../modules/shared/views/admin_login.php");
        }

        exit;
    } else {
        // ❌ FAILED: increment
        $_SESSION['login_attempts']++;

        if ($_SESSION['login_attempts'] >= 3) {
            $_SESSION['lock_time'] = time() + 60; // lock for 60s
            $_SESSION['login_error'] = "Too many failed attempts. Login locked for 60 seconds.";
        } else {
            $_SESSION['login_error'] = "Invalid email or password. Attempt {$_SESSION['login_attempts']} of 3.";
        }

        $_SESSION['old_email'] = $email;
        header("Location: ../modules/shared/views/admin_login.php");
        exit;
    }
}


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_admin'])) {
    $adminModel = new AdministratorModel(); // ✅ This must be inside the condition

    $staff_id       = $_POST['staff_id'] ?? '';
    $email          = $_POST['email'] ?? '';
    $first_name     = $_POST['first_name'] ?? '';
    $last_name      = $_POST['last_name'] ?? '';
    $access_level   = $_POST['access_level'] ?? '';
    $password_raw   = $_POST['password'] ?? '';
    $confirm_pass   = $_POST['confirm_password'] ?? '';

    if ($password_raw !== $confirm_pass) {
        $_SESSION['admin_error'] = "Passwords do not match.";
        header("Location: ../modules/superadmin/views/manage_admin.php");
        exit;
    }

    $encrypted_pass = encrypt($password_raw);

    // Upload profile picture
    $profile_picture_path = null;

    if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . "/../../public/uploads/profile_pics";

        // Create directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $filename = basename($_FILES["profile_picture"]["name"]);
        $target_file = $upload_dir . '/' . $filename;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture_path = "/public/uploads/profile_pics/" . $filename;
        } else {
            $_SESSION['admin_error'] = "Failed to upload profile picture.";
            header("Location: ../modules/superadmin/views/manage_admin.php");
            exit;
        }
    }


    // ✅ Now use the model
    $result = $adminModel->addAdministrator(
        $staff_id,
        $email,
        $first_name,
        $last_name,
        $access_level,
        $encrypted_pass,
        $filename
    );

    if ($result) {
        $_SESSION['admin_success'] = "Administrator successfully added.";
        header("Location: ../modules/superadmin/views/manage_admin.php");
        exit;
    } else {
        $_SESSION['admin_error'] = $_SESSION['db_error'] ?? "Unknown error occurred.";
        header("Location: ../modules/superadmin/views/manage_admin.php");
        exit;
    }
}