<?php

require_once __DIR__ . '/../core/BaseModel.php'; 
require_once __DIR__ . '/../config/encryption.php';
require_once __DIR__ . '/../config/db_helpers.php';

class AdministratorModel extends BaseModel {

    // ADD ADMINISTRATOR
    public function addAdministrator($staff_id, $email, $first_name, $last_name, $contact_no, $access_level, $password, $profile_picture) {
        $encrypted_pass = encrypt($password);
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
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
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
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

    // For Add: Staff ID
    public function isAdminIdExistsOnAdd($staff_id) {
        $sql = "SELECT COUNT(*) as count FROM vw_administrator WHERE staff_id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('s', $staff_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['count'] > 0;
    }

    // For Add: Email
    public function isAdminEmailExistsOnAdd($email) {
        $sql = "SELECT COUNT(*) as count FROM vw_administrator WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['count'] > 0;
    }

    // For Add: Contact
    public function isAdminContactExistsOnAdd($contact) {
        $sql = "SELECT COUNT(*) as count FROM vw_administrator WHERE contact_no = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('s', $contact);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['count'] > 0;
    }


    // Check staff_id (exclude current admin by email)
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

    // Check email (exclude current admin by staff_id)
    public function isAdminEmailExists($email, $currentStaffId) {
        $sql = "SELECT COUNT(*) as count FROM vw_administrator WHERE email = ? AND staff_id != ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('ss', $email, $currentStaffId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['count'] > 0;
    }

    // Check contact (exclude current admin by staff_id)
    public function isAdminContactExists($contact, $currentStaffId) {
        $sql = "SELECT COUNT(*) as count FROM vw_administrator WHERE contact_no = ? AND staff_id != ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('ss', $contact, $currentStaffId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['count'] > 0;
    }

    public function getRequestHistory($requester_id) {
        $sql = "
            SELECT 'Facility' AS type, request_id AS id, req_status AS status, date_requested
            FROM request
            WHERE requester_id = ?
            UNION ALL
            SELECT 'Vehicle' AS type, vehicle_request_id AS id, status, date_requested
            FROM vehicle_request
            WHERE requester_id = ?
            ORDER BY date_requested DESC
        ";
    
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
    
        $stmt->bind_param("ss", $requester_id, $requester_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    
        $stmt->close();
        return $rows;
    }

    public function increaseQuantity($material_id, $quantity)
    {
        $stmt = $this->db->prepare("
            UPDATE materials
            SET quantity = quantity + ?
            WHERE material_id = ?
        ");
        $stmt->bind_param("ii", $quantity, $material_id);
        return $stmt->execute();
    }

    // Get profile data by email
    public function getProfileByEmail($admin_email)
    {
        $stmt = $this->db->prepare("
            SELECT profile_picture
            FROM administrator
            WHERE email = ?
        ");
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns single row
    }
    
    // Destructor
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }

        ///////////////////////////////// FOR FEEDBACK ///////////////////////////////////////////

    public function getAllFeedbacks() {
        $sql = "SELECT 
            f.tracking_id,
            f.ratings_A,
            f.ratings_B,
            f.ratings_C,
            f.overall_rating,
            f.suggest_process,
            f.suggest_frontline,
            f.suggest_facility,
            f.suggest_overall,
            f.submitted_at,
            r.req_id,
            rq.profile_pic,
            COUNT(f.tracking_id) OVER (PARTITION BY r.req_id) AS total_feedback
        FROM feedback AS f
        JOIN request AS r 
            ON f.tracking_id = r.tracking_id
        JOIN requester AS rq 
            ON r.req_id = rq.req_id
        ORDER BY f.submitted_at DESC";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $feedbacks = [];
        while ($row = $result->fetch_assoc()) {
            $feedbacks[] = $row;
        }

        $stmt->close();
        return $feedbacks;
    }
}


