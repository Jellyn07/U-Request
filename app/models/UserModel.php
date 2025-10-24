<?php

require_once __DIR__ . '/../core/BaseModel.php'; 
require_once __DIR__ . '/../config/encryption.php';

class UserModel extends BaseModel  {

    // SIGNUP - Create User
    public function createUser($ssid, $email, $fn, $ln, $pass) {
        $encrypted_pass = encrypt($pass);

        $stmt = $this->db->prepare("CALL spAddRequester(?, ?, ?, ?, ?)");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
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

    // LOGIN - Get User by Email
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT pass FROM REQUESTER WHERE email = ?");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return null;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    // LOGIN - Get Requester ID
    public function getRequesterId($email) {
        $stmt = $this->db->prepare("SELECT fnGetRequesterIdByEmail(?) AS req_id");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return null;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    // LOGIN - Verify Password
    public function verifyPassword($input_pass, $stored_pass) {
        return $input_pass === decrypt($stored_pass);
    }

    // CHECK if Email Exists
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM vw_requesters WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['cnt'] > 0;
    }

    // CHECK if Student ID Exists
    public function studentIdExists($ssid) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM vw_requesters WHERE requester_id = ?");
        $stmt->bind_param("s", $ssid);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['cnt'] > 0;
    }

    // GET ADMIN User
    public function getAdminUserByEmail($email) {
        $stmt = $this->db->prepare("CALL spGetAdminByEmail(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function getRequestHistory($requester_id) {
        $records = [];
        $requester_id = strval($requester_id);

        $query = "
            SELECT 
                v.tracking_id,
                v.request_Type,
                v.request_desc,
                v.location,
                v.req_status,
                v.date_finished
            FROM vw_rqtrack v
            WHERE v.req_id IN (
                SELECT req_id FROM requester WHERE requester_id = ?
            )
            ORDER BY v.date_finished DESC
        ";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("s", $requester_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }

            $stmt->close();
        }

        return $records;
    }

    // Destructor
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
