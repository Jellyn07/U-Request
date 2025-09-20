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

     // Update admin details
     public function updateAdminDetails($data) {
        // Map incoming keys to DB columns and types
        $allowedFields = [
            'staff_id' => ['col' => 'staff_id', 'type' => 's'],
            'firstName' => ['col' => 'first_name', 'type' => 's'],
            'lastName' => ['col' => 'last_name', 'type' => 's'],
            'contact_no' => ['col' => 'contact_no', 'type' => 's'],
            'accessLevel_id' => ['col' => 'accessLevel_id', 'type' => 'i'],
        ];

        $setParts = [];
        $types = '';
        $values = [];

        foreach ($allowedFields as $key => $meta) {
            if (isset($data[$key]) && $data[$key] !== '' && $data[$key] !== null) {
                $setParts[] = $meta['col'] . ' = ?';
                $types .= $meta['type'];
                $values[] = $data[$key];
            }
        }

        if (empty($setParts) || empty($data['admin_email'])) {
            $_SESSION['db_error'] = 'No fields to update or missing admin_email.';
            return false;
        }

        $sql = 'UPDATE administrator SET ' . implode(', ', $setParts) . ' WHERE email = ?';

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            $_SESSION['db_error'] = 'Prepare failed: ' . $this->db->error;
            return false;
        }

        // Append WHERE parameter
        $types .= 's';
        $values[] = $data['admin_email'];

        // bind_param requires references
        $bindParams = [];
        $bindParams[] = & $types;
        foreach ($values as $idx => $val) {
            $bindParams[] = & $values[$idx];
        }

        call_user_func_array([$stmt, 'bind_param'], $bindParams);

        $result = $stmt->execute();
        if (!$result) {
            $_SESSION['db_error'] = 'Execute failed: ' . $stmt->error;
        }
        $stmt->close();
        return $result;
    }

    public function updateUserDetails($data) {
        $allowedFields = [
            'requester_id' => ['col' => 'requester_id', 'type' => 's'],
            'firstName' => ['col' => 'firstName', 'type' => 's'],
            'lastName' => ['col' => 'lastName', 'type' => 's'],
            'officeOrDept' => ['col' => 'officeOrDept', 'type' => 's'],
        ];
    
        $setParts = [];
        $types = '';
        $values = [];
    
        foreach ($allowedFields as $key => $meta) {
            if (isset($data[$key]) && $data[$key] !== '' && $data[$key] !== null) {
                $setParts[] = $meta['col'] . ' = ?';
                $types .= $meta['type'];
                $values[] = $data[$key];
            }
        }
    
        if (empty($setParts) || empty($data['email'])) return false;
    
        $sql = 'UPDATE requester SET ' . implode(', ', $setParts) . ' WHERE email = ?';
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
    
        $types .= 's';
        $values[] = $data['email'];
    
        $bindParams = [];
        $bindParams[] = & $types;
        foreach ($values as $idx => $val) {
            $bindParams[] = & $values[$idx];
        }
    
        call_user_func_array([$stmt, 'bind_param'], $bindParams);
    
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Check if requester_id already exists (excluding the current user)
    public function isRequesterIdExists($requester_id, $currentEmail) {
        $sql = "SELECT COUNT(*) as count FROM vw_requesters WHERE requester_id = ? AND email != ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('ss', $requester_id, $currentEmail);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['count'] > 0;
    }

    public function isAdminIdExists($staff_id, $currentEmail) {
        $sql = "SELECT COUNT(*) as count FROM vw_administrator WHERE staff_id = ? AND email != ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('ss', $staff_id, $currentEmail);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['count'] > 0;
    }


    // Destructor
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
