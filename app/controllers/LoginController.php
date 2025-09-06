<?php
session_start();

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/UserModel.php';

$login_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = $_POST['email'] ?? '';
    $input_pass = $_POST['password'] ?? '';

    $userModel = new UserModel();
    $user = $userModel->getUserByEmail($email);

    if ($user && $userModel->verifyPassword($input_pass, $user['pass'])) {
        $req = $userModel->getRequesterId($email);

        if ($req && isset($req['req_id'])) {
            $_SESSION['req_id'] = $req['req_id'];
            $_SESSION['email'] = $email;

            header("Location: ../modules/user/views/request.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Failed to fetch user ID.";
            header("Location: ../views/login.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: ../views/login.php");
        exit;
    }
}
