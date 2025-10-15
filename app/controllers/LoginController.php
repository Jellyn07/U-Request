<?php
session_start();

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/UserModel.php';

$login_error = "";

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lock_time'] = null;
}

if (isset($_SESSION['lock_time']) && time() < $_SESSION['lock_time']) {
    $_SESSION['login_error'] = "Too many failed attempts. Please wait 60 seconds before trying again.";
    header("Location: ../modules/user/views/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = trim($_POST['email'] ?? '');
    $input_pass = trim($_POST['password'] ?? '');

    $userModel = new UserModel();
    $user = $userModel->getUserByEmail($email);

    if ($user && $userModel->verifyPassword($input_pass, $user['pass'])) {
        // ✅ SUCCESS: reset attempts
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lock_time'] = null;

        $req = $userModel->getRequesterId($email);

        if ($req && isset($req['req_id'])) {
            $_SESSION['req_id'] = $req['req_id'];
            $_SESSION['email'] = $email;

            header("Location: ../modules/user/views/request.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Failed to fetch user ID.";
            $_SESSION['old_email'] = $email;
            header("Location: ../modules/user/views/login.php");
            exit;
        }
    } else {
        // ❌ FAILED LOGIN ATTEMPT
        $_SESSION['login_attempts']++;

        if ($_SESSION['login_attempts'] >= 3) {
            $_SESSION['lock_time'] = time() + 60; // Lock for 60 seconds
            $_SESSION['login_error'] = "Too many failed attempts. Login locked for 60 seconds.";
        } else {
            $_SESSION['login_error'] = "Invalid email or password. Attempt {$_SESSION['login_attempts']} of 3.";
        }

        // ✅ Preserve the email (and password temporarily)
        $_SESSION['old_email'] = $email;
        $_SESSION['old_password'] = $input_pass; // Optional (see note below)

        header("Location: ../modules/user/views/login.php");
        exit;
    }
}
