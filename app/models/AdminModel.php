<?php

require_once __DIR__ . '/../core/BaseModel.php'; 
require_once __DIR__ . '/../config/encryption.php';

class AdministratorModel extends BaseModel {

    // ADD ADMINISTRATOR
    public function addAdministrator($staff_id, $email, $first_name, $last_name, $contact_no, $access_level, $password, $profile_picture) {
        $encrypted_pass = encrypt($password);

        $stmt = $this->db->prepare("CALL spAddAdministrator(?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return false;
        }

        $stmt->bind_param("sssssiss", $staff_id, $email, $first_name, $last_name, $contact_no, $access_level, $encrypted_pass, $profile_picture);
        $result = $stmt->execute();

        if (!$result) {
            $_SESSION['db_error'] = "Execute failed: " . $stmt->error;
            $stmt->close();
            return false;
        }

        $stmt->close();
        return $result;
    }

    // GET ADMIN BY EMAIL (FOR LOGIN OR DUPLICATE CHECKING)
    public function getAdminByEmail($email) {
        $stmt = $this->db->prepare("CALL spGetAdminByEmail(?)");
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

    // CHECK IF EMAIL EXISTS
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM vw_administrator WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['cnt'] > 0;
    }

    // CHECK IF STAFF ID EXISTS
    public function staffIdExists($staff_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM vw_administrator WHERE staff_id = ?");
        $stmt->bind_param("s", $staff_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['cnt'] > 0;
    }

    // VERIFY PASSWORD (DECRYPT AND COMPARE)
    public function verifyPassword($input_pass, $stored_encrypted_pass) {
        return $input_pass === decrypt($stored_encrypted_pass);
    }

    public function getAdministrators() {
        $stmt = $this->db->prepare("SELECT * FROM vw_administrator");
        
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return [];
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $admins = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
        return $admins;
    }
    

    // Destructor
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
