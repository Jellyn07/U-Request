<?php
session_start();

require_once __DIR__ . '/../models/UserModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ssid = $_POST['ssid'] ?? '';
    $email = $_POST['email'] ?? '';
    $fn = $_POST['fn'] ?? '';
    $ln = $_POST['ln'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $rpass = $_POST['rpass'] ?? '';

    $userModel = new UserModel();

    // ✅ Password check
    if ($pass !== $rpass) {
        $_SESSION['signup_error'] = "Passwords do not match.";
        header("Location: ../views/signup.php");
        exit;
    }

    if ($userModel->emailExists($email)) {
        $_SESSION['signup_error'] = "Email already exists.";
        header("Location: ../views/signup.php");
        exit;
    }

    if ($userModel->studentIdExists($ssid)) {
        $_SESSION['signup_error'] = "Student ID already exists.";
        header("Location: ../views/signup.php");
        exit;
    }


    // ✅ Create new user
    $created = $userModel->createUser($ssid, $email, $fn, $ln, $pass);

    if ($created) {
        $_SESSION['signup_success'] = "✅ Account created successfully! Please login.";
        header("Location: ../views/login.php");
        exit;
    } else {
        $_SESSION['signup_error'] = "❌ Failed to create account. Please try again.";
        header("Location: ../views/signup.php");
        exit;
    }
}
