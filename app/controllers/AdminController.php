<?php
session_start();

require_once __DIR__ . '/../config/constants.php';
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
