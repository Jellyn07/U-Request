<?php
// =========================
// CONFIG
// =========================
define('ENCRYPT_METHOD', 'aes-256-cbc');
define('SECRET_KEY', '12345678901234567890123456789012');
define('SECRET_IV', '1234567890123456');

function encrypt($string) {
    return base64_encode(openssl_encrypt($string, ENCRYPT_METHOD, SECRET_KEY, 0, SECRET_IV));
}

function decrypt($string) {
    return openssl_decrypt(
        base64_decode($string),
        ENCRYPT_METHOD,
        SECRET_KEY,
        0,
        SECRET_IV
    );
}

// =========================
// MODEL
// =========================
class UserModel {
    private $mysqli;

    public function __construct() {
        $this->mysqli = new mysqli("localhost", "root", "", "utrms_db");
        if ($this->mysqli->connect_errno) {
            // Instead of die(), return error
            $_SESSION['db_error'] = "Database connection failed: " . $this->mysqli->connect_error;
        }
    }

    // -------------------------
    // SIGNUP - Create User
    // -------------------------
    public function createUser($ssid, $email, $fn, $ln, $pass) {
        $encrypted_pass = encrypt($pass);

        $stmt = $this->mysqli->prepare("CALL spAddRequester(?, ?, ?, ?, ?)");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->mysqli->error;
            return false;
        }

        $stmt->bind_param("sssss", $ssid, $fn, $ln, $encrypted_pass, $email);
        $result = $stmt->execute();

        if (!$result) {
            $_SESSION['db_error'] = "Execute failed: " . $stmt->error;
            $stmt->close();
            return false;
        }

        $stmt->close();
        return $result;
    }

    // -------------------------
    // LOGIN - Get User by Email
    // -------------------------
    public function getUserByEmail($email) {
        $stmt = $this->mysqli->prepare("SELECT pass FROM REQUESTER WHERE email = ?");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->mysqli->error;
            return null;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    // -------------------------
    // LOGIN - Get Requester ID
    // -------------------------
    public function getRequesterId($email) {
        $stmt = $this->mysqli->prepare("SELECT fnGetRequesterIdByEmail(?) AS req_id");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->mysqli->error;
            return null;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    // -------------------------
    // LOGIN - Verify Password
    // -------------------------
    public function verifyPassword($input_pass, $stored_pass) {
        return $input_pass === decrypt($stored_pass);
    }

    // -------------------------
    // CHECK if Email Exists
    // -------------------------
    public function emailExists($email) {
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS cnt FROM vw_requesters WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['cnt'] > 0;
    }

    // -------------------------
    // CHECK if Student ID Exists
    // -------------------------
    public function studentIdExists($ssid) {
        $stmt = $this->mysqli->prepare("SELECT COUNT(*) AS cnt FROM vw_requesters WHERE requester_id = ?");
        $stmt->bind_param("s", $ssid);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['cnt'] > 0;
    }


    // -------------------------
    // Destructor
    // -------------------------
    public function __destruct() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }
}
