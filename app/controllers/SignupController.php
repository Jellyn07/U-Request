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

    // ✅ Function to validate names (no numbers allowed)
    function isValidName($name) {
        return preg_match("/^[a-zA-Z\s'-]+$/", $name);
    }

    // ✅ Function to validate USeP email
    function isValidUsepEmail($email) {
        return preg_match("/^[a-zA-Z0-9._%+-]+@usep\.edu\.ph$/", $email);
    }

    // ✅ First & Last name validation
    if (!isValidName($fn) || !isValidName($ln)) {
        $_SESSION['signup_error'] = "Names cannot contain numbers or invalid characters.";
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    // ✅ Email domain validation
    if (!isValidUsepEmail($email)) {
        $_SESSION['signup_error'] = "Only @usep.edu.ph email addresses are allowed.";
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    // ✅ Password check
    if ($pass !== $rpass) {
        $_SESSION['signup_error'] = "Passwords do not match.";
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    if ($userModel->emailExists($email)) {
        $_SESSION['signup_error'] = "Email already exists.";
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    if ($userModel->studentIdExists($ssid)) {
        $_SESSION['signup_error'] = "Student ID already exists.";
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    // ✅ Create new user
    $created = $userModel->createUser($ssid, $email, $fn, $ln, $pass);

    if ($created) {
        $_SESSION['signup_success'] = "✅ Account created successfully! Please login.";
        header("Location: ../modules/user/views/login.php");
        exit;
    } else {
        $_SESSION['signup_error'] = "❌ Failed to create account. Please try again.";
        header("Location: ../modules/user/views/signup.php");
        exit;
    }
}
