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
        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
            exit;
        }

        // ✅ Check if user exists in requester or administrator
        $table = null;

        $stmt1 = $db->db->prepare("SELECT requester_id FROM requester WHERE email = ?");
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        if ($result1->num_rows > 0) {
            $table = 'requester';
        }
        $stmt1->close();

        if (!$table) {
            $stmt2 = $db->db->prepare("SELECT staff_id FROM administrator WHERE email = ?");
            $stmt2->bind_param("s", $email);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if ($result2->num_rows > 0) {
                $table = 'administrator';
            }
            $stmt2->close();
        }

        if (!$table) {
            echo json_encode(['success' => false, 'message' => 'No account found with that email.']);
            exit;
        }

        // ✅ Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $email;
        $_SESSION['otp_expire'] = time() + 300; // valid for 5 minutes
        $_SESSION['otp_table'] = $table;

        // ✅ Send Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jonagujol@gmail.com'; // ⚠️ Change this
            $mail->Password = 'wqhb eszj mxiz rmmh'; // ⚠️ Change this
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('jonagujol@gmail.com', 'U-Request System');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your U-Request Password Reset OTP';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif;'>
                    <h2 style='color:#800000;'>U-Request Password Reset</h2>
                    <p>Hello,</p>
                    <p>Your One-Time Password (OTP) is:</p>
                    <h1 style='color:#D32F2F;'>$otp</h1>
                    <p>This code will expire in <b>5 minutes</b>.</p>
                    <p>If you didn’t request this, please ignore this email.</p>
                </div>
            ";

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'OTP sent successfully to your email.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to send OTP: ' . $mail->ErrorInfo]);
        }
        break;

    // ──────────────── STEP 2: VERIFY OTP ────────────────
    case 'verify_otp':
        $email = $_POST['email'] ?? '';
        $otp = $_POST['otp'] ?? '';

        if (
            !isset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expire']) ||
            time() > $_SESSION['otp_expire']
        ) {
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
        $email = $_POST['email'] ?? '';
        $new_password = $_POST['new_password'] ?? '';

        if (strlen($new_password) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long.']);
            exit;
        }

        if (!isset($_SESSION['otp_table'])) {
            echo json_encode(['success' => false, 'message' => 'Session expired or invalid.']);
            exit;
        }

        $table = $_SESSION['otp_table'];
        $encrypt = encrypt($new_password);

        if ($table === 'requester') {
            $stmt = $db->db->prepare("UPDATE requester SET pass = ? WHERE email = ?");
        } else {
            $stmt = $db->db->prepare("UPDATE administrator SET password = ? WHERE email = ?");
        }

        $stmt->bind_param("ss", $encrypt, $email);
        $stmt->execute();
        $stmt->close();

        unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expire'], $_SESSION['otp_table']);
        echo json_encode(['success' => true, 'message' => 'Password has been reset successfully.']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        break;
}
