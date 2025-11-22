<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/encryption.php'; 
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/helpers.php';

$action = $_GET['action'] ?? '';

if ($action === 'toggleAdminMenu') {
    $controller = new AdminController();
    $controller->toggleAdminMenu();
    exit;
}

$login_error = "";


// ------------------------------------------------------------
// ✅ CORRECT: Initialize only ONCE
// ------------------------------------------------------------
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lock_time'] = null;
}


// ------------------------------------------------------------
// ✅ CORRECT: Check if locked BEFORE processing login
// ------------------------------------------------------------
if ($_SESSION['lock_time'] !== null && time() < $_SESSION['lock_time']) {
    $remaining = $_SESSION['lock_time'] - time();
    $_SESSION['login_error'] = "Too many failed attempts. Please wait {$remaining} seconds before trying again.";
    header("Location: ../modules/shared/views/admin_login.php");
    exit();
}


// ------------------------------------------------------------
// 🔐 LOGIN PROCESSING 
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {

    $email = trim($_POST['email'] ?? '');
    $input_pass = trim($_POST['password'] ?? '');

    $userModel = new UserModel();
    $admin = $userModel->getAdminUserByEmail($email);

    // Admin record found + stored procedure success
    if ($admin && isset($admin['result']) && $admin['result'] === 'success') {

        if ($userModel->verifyPassword($input_pass, $admin['password'])) {

            // 🎉 LOGIN SUCCESS → reset attempts
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lock_time'] = null;

            // session
            $staff_id = $admin['staff_id'];
            $_SESSION['staff_id'] = $staff_id;
            $_SESSION['email'] = $admin['email'];
            $_SESSION['access_level'] = $admin['accessLevel_id'];
            $_SESSION['full_name'] = $admin['first_name'] . ' ' . $admin['last_name'];

            $adminModel = new AdministratorModel();
            $menuAccess = $adminModel->getAdminMenuAccess($staff_id);
            $_SESSION['canSeeAdminManagement'] = ($menuAccess == 1);

            // Redirect based on access level
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
            // ❌ wrong password
            $_SESSION['login_attempts']++;
            $_SESSION['login_error'] = "Invalid password. Attempt {$_SESSION['login_attempts']} of 3.";

            // lock after 3 attempts
            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['lock_time'] = time() + 60;
                $_SESSION['login_error'] .= " Login locked for 60 seconds.";
            }
        }

    } else {
        // ❌ invalid email or inactive
        $_SESSION['login_attempts']++;

        $_SESSION['login_error'] = $admin['error_message'] ?? "Invalid email or password.";

        if ($_SESSION['login_attempts'] >= 3) {
            $_SESSION['lock_time'] = time() + 60;
            $_SESSION['login_error'] .= " Login locked for 60 seconds.";
        }
    }

    $_SESSION['old_email'] = $email;
    $_SESSION['old_password'] = $input_pass;

    header("Location: ../modules/shared/views/admin_login.php");
    exit;
}


// ----------------------------------------------
// (Everything below is untouched, same as yours)
// ----------------------------------------------
