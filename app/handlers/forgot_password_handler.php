<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../core/Model.php';
require_once __DIR__ . '/../config/encryption.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$db = new BaseModel();
$action = $_POST['action'] ?? '';

switch ($action) {

    // ──────────────── STEP 1: SEND OTP ────────────────
    case 'send_otp':

        $plainEmail = trim($_POST['email'] ?? '');

        // Validate RAW email
        if (!filter_var($plainEmail, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
            exit;
        }

        $encryptedEmail = encrypt($plainEmail);

        // Check if user exists
        $table = null;

        $stmt1 = $db->db->prepare("SELECT requester_id FROM requester WHERE email = ?");
        $stmt1->bind_param("s", $encryptedEmail);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        if ($result1->num_rows > 0) $table = 'requester';
        $stmt1->close();

        if (!$table) {
            $stmt2 = $db->db->prepare("SELECT staff_id FROM administrator WHERE email = ?");
            $stmt2->bind_param("s", $encryptedEmail);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if ($result2->num_rows > 0) $table = 'administrator';
            $stmt2->close();
        }

        if (!$table) {
            echo json_encode(['success' => false, 'message' => 'No account found with that email.']);
            exit;
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $plainEmail; // store RAW email
        $_SESSION['otp_expire'] = time() + 300; // 5 minutes
        $_SESSION['otp_table'] = $table;

        // Send OTP email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jonagujol@gmail.com';  
            $mail->Password = 'wqhb eszj mxiz rmmh';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('jonagujol@gmail.com', 'U-Request System');
            $mail->addAddress($plainEmail);
            $mail->isHTML(true);
            $mail->Subject = 'Your U-Request Password Reset OTP';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif;'>
                    <h2 style='color:#800000;'>U-Request Password Reset</h2>
                    <p>Hello,</p>
                    <p>Your OTP is:</p>
                    <h1 style='color:#D32F2F;'>$otp</h1>
                    <p>Expires in <b>5 minutes</b>.</p>
                </div>
            ";

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'OTP sent successfully to your email.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to send OTP.']);
        }
        break;

    // ──────────────── STEP 2: VERIFY OTP ────────────────
    case 'verify_otp':

        $email = trim($_POST['email'] ?? '');
        $otp = trim($_POST['otp'] ?? '');

        if (!isset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expire']) ||
            time() > $_SESSION['otp_expire']) {
            echo json_encode(['success' => false, 'message' => 'OTP expired or not found.']);
            exit;
        }

        if ($email !== $_SESSION['otp_email'] || $otp != $_SESSION['otp']) {
            echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'OTP verified successfully.']);
        break;

    // ──────────────── STEP 3: RESET PASSWORD ────────────────
    case 'reset_password':

        if (!isset($_SESSION['otp_email'], $_SESSION['otp_table'])) {
            echo json_encode(['success' => false, 'message' => 'Session expired.']);
            exit;
        }

        $plainEmail = $_SESSION['otp_email']; // use email from session
        $table = $_SESSION['otp_table'];

        $new_password = $_POST['new_password'] ?? '';

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_password)) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters, include uppercase, lowercase, number, and special character.']);
            exit;
        }

        if (!isset($_SESSION['otp_table'], $_SESSION['otp_email'])) {
            echo json_encode(['success' => false, 'message' => 'Session expired.']);
            exit;
        }

        $table = $_SESSION['otp_table'];
        $encryptedEmail = encrypt($plainEmail);
        $encryptedPass  = encrypt($new_password);

        if ($table === 'requester') {
            $stmt = $db->db->prepare("UPDATE requester SET pass = ? WHERE email = ?");
        } else {
            $stmt = $db->db->prepare("UPDATE administrator SET password = ? WHERE email = ?");
        }

        $stmt->bind_param("ss", $encryptedPass, $encryptedEmail);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'No account updated.']);
            exit;
        }

        $stmt->close();

        // Clear OTP session
        unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expire'], $_SESSION['otp_table']);

        echo json_encode(['success' => true, 'message' => 'Password reset successful.']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        break;
}
