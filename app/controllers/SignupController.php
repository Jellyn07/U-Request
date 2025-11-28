<?php
session_start();

require_once __DIR__ . '/../models/UserModel.php';

function saveFormData($ssid, $email, $fn, $ln, $pass, $rpass) {
    $_SESSION['form_data'] = [
        'ssid' => $ssid,
        'email' => $email,
        'fn' => $fn,
        'ln' => $ln,
        'pass' => $pass,
        'rpass' => $rpass
    ];
}

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

    // ✅ Function to validate password strength
    function isValidPassword($password) {
        return preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password);
        // Explanation:
        // (?=.*[a-z]) → at least one lowercase
        // (?=.*[A-Z]) → at least one uppercase
        // .{8,} → at least 8 characters total
    }

    // ✅ First & Last name validation
    if (!isValidName($fn) || !isValidName($ln)) {
        $_SESSION['signup_error'] = "Names cannot contain numbers or invalid characters.";
        saveFormData($ssid, $email, $fn, $ln, $pass, $rpass);
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    // ✅ Email domain validation
    if (!isValidUsepEmail($email)) {
        $_SESSION['signup_error'] = "Only @usep.edu.ph email addresses are allowed.";
        saveFormData($ssid, $email, $fn, $ln, $pass, $rpass);
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    // ✅ Password match validation
    if ($pass !== $rpass) {
        $_SESSION['signup_error'] = "Passwords do not match.";
        saveFormData($ssid, $email, $fn, $ln, $pass, $rpass);
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    // ✅ Password strength validation
    if (!isValidPassword($pass)) {
        $_SESSION['signup_error'] = "Password must be at least 8 characters long and contain uppercase, lowercase letters, special characters and numbers.";
        saveFormData($ssid, $email, $fn, $ln, $pass, $rpass);
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    // ✅ Check duplicates
    if ($userModel->emailExists($email)) {
        $_SESSION['signup_error'] = "Email already exists.";
        saveFormData($ssid, $email, $fn, $ln, $pass, $rpass);
        header("Location: ../modules/user/views/signup.php");
        exit;
    }

    if ($userModel->studentIdExists($ssid)) {
        $_SESSION['signup_error'] = "Student ID already exists.";
        saveFormData($ssid, $email, $fn, $ln, $pass, $rpass);
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
