<?php

require_once __DIR__ . '/../core/BaseModel.php'; 
require_once __DIR__ . '/../config/encryption.php';
require_once __DIR__ . '/../config/db_helpers.php';

class AdministratorModel extends BaseModel {

    // ADD ADMINISTRATOR
    public function addAdministrator($staff_id, $email, $first_name, $last_name, $contact_no, $access_level, $password, $profile_picture) {
        $encrypted_pass = encrypt($password);
        $encrypted_email = encrypt($email);

        // Optional: record who added this admin
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }

        // Begin transaction to ensure both inserts succeed
        $this->db->begin_transaction();

        try {
            // 1️⃣ Insert into administrator table
            $stmt = $this->db->prepare("CALL spAddAdministrator(?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }

            $stmt->bind_param("sssssiss", $staff_id, $encrypted_email, $first_name, $last_name, $contact_no, $access_level, $encrypted_pass, $profile_picture);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            $stmt->close();

            // 2️⃣ Insert into add_admin_access table
            // Super Admin (access_level=1) gets is_enabled=1, others get 0
            $is_enabled = ($access_level == 1) ? 1 : 0;

            $stmt2 = $this->db->prepare("INSERT INTO add_admin_access (staff_id, is_enabled) VALUES (?, ?)");
            if (!$stmt2) {
                throw new Exception("Prepare failed for add_admin_access: " . $this->db->error);
            }

            $stmt2->bind_param("si", $staff_id, $is_enabled);
            if (!$stmt2->execute()) {
                throw new Exception("Execute failed for add_admin_access: " . $stmt2->error);
            }
            $stmt2->close();

            // ✅ Commit transaction
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            // ❌ Rollback if anything fails
            $this->db->rollback();
            $_SESSION['db_error'] = $e->getMessage();
            return false;
        }
    }

    // GET ADMIN BY EMAIL (FOR LOGIN OR DUPLICATE CHECKING)
    public function getAdminByEmail($email) {
        $encrypted_email = encrypt($email);
        $stmt = $this->db->prepare("CALL spGetAdminByEmail(?)");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return null;
        }

        $stmt->bind_param("s", $encrypted_email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }  
    // CHECK IF EMAIL EXISTS
    public function emailExists($email) {
        $encrypted_email = encrypt($email);
        $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM vw_administrator WHERE email = ?");
        $stmt->bind_param("s", $encrypted_email);
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
    // public function verifyPassword($input_pass, $stored_encrypted_pass) {
    //     return $input_pass === decrypt($stored_encrypted_pass);
    // }

    // public function getAdministrators($currentAccessLevel = 1) {
    //     // Base query
    //     $query = "SELECT * FROM vw_administrator";
    //     $params = [];
        
    //     // Apply filter for non-superadmins
    //     if ($currentAccessLevel == 2) {
    //         // GSU Admin sees only GSU admins
    //         $query .= " WHERE accessLevel_id = ?";
    //         $params[] = 2;
    //     } elseif ($currentAccessLevel == 3) {
    //         // Motorpool Admin sees only Motorpool admins
    //         $query .= " WHERE accessLevel_id = ?";
    //         $params[] = 3;
    //     }

    //     $stmt = $this->db->prepare($query);
        
    //     if (!$stmt) {
    //         $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
    //         return [];
    //     }

    //     // Bind parameters if needed
    //     if (!empty($params)) {
    //         $stmt->bind_param(str_repeat('i', count($params)), ...$params);
    //     }

    //     $stmt->execute();
    //     $result = $stmt->get_result();
    //     $admins = $result->fetch_all(MYSQLI_ASSOC);

    //     $stmt->close();
    //     return $admins;
    // }

    // --- EMAIL DECRYPTION VERSION (FUTURE) ---
    public function getAdministrators($currentAccessLevel = 1) {
        // Base query: fetch all columns including the encrypted email
        $query = "SELECT * FROM vw_administrator";
        $params = [];

        // Apply filter for non-superadmins
        if ($currentAccessLevel == 2) {
            $query .= " WHERE accessLevel_id = ?";
            $params[] = 2;
        } elseif ($currentAccessLevel == 3) {
            $query .= " WHERE accessLevel_id = ?";
            $params[] = 3;
        }

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return [];
        }

        if (!empty($params)) {
            $stmt->bind_param(str_repeat('i', count($params)), ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $admins = [];

        while ($row = $result->fetch_assoc()) {
            // Decrypt email
            if (!empty($row['email'])) {
                $row['email'] = decrypt($row['email']);
            }
            $admins[] = $row;
        }

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
            'status' => ['col' => 'status', 'type' => 's'],
            'profile_picture' => ['col' => 'profile_picture', 'type' => 's'],
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
        $values[] = encrypt($data['admin_email']);

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
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
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
        $values[] = encrypt($data['email']);
    
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

    public function isRequesterIdExists($requester_id, $email) {
        $sql = "SELECT COUNT(*) AS count FROM requester 
                WHERE requester_id = ? 
                AND email != ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $requester_id, encrypt($email));
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['count'] > 0;
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
        $email = encrypt($email);
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
        $email = encrypt($currentEmail);
        $sql = "SELECT COUNT(*) as count FROM vw_administrator WHERE staff_id = ? AND email != ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('ss', $staff_id, $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result['count'] > 0;
    }

    // Check email (exclude current admin by staff_id)
    public function isAdminEmailExists($email, $currentStaffId) {
        $encrypted_email = encrypt($email);
        $sql = "SELECT COUNT(*) as count FROM vw_administrator WHERE email = ? AND staff_id != ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param('ss', $email, $encrypted_email);
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
    public function getProfileByEmail($admin_email){
        $admin_email = encrypt($admin_email);
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
        COUNT(f.tracking_id) OVER (PARTITION BY r.req_id) AS total_feedback,
        -- count total requests from VW_rqtrack
        (SELECT COUNT(*) 
        FROM VW_rqtrack v
        WHERE v.req_id = r.req_id
        ) AS total_requests
    FROM VW_feedback AS f 
    JOIN request AS r 
        ON f.tracking_id = r.tracking_id
    JOIN requester AS rq 
        ON r.req_id = rq.req_id
    WHERE f.tracking_id NOT LIKE '%VR%'
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

/////////////////////////////////////////////motorpool feedback///////////////////////////////////////////
    public function getAllMotorpoolFeedbacks() {
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
        COUNT(f.tracking_id) OVER (PARTITION BY r.req_id) AS total_feedback,
        COUNT(r.req_id) OVER (PARTITION BY r.req_id) AS total_requests
    FROM VW_feedback AS f
    LEFT JOIN request AS r ON f.tracking_id = r.tracking_id
    LEFT JOIN requester AS rq ON r.req_id = rq.req_id
    WHERE f.tracking_id LIKE '%VR%'
    ORDER BY f.submitted_at DESC;
    ";

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

    ///////////////////////////////////////////////////////Superadmin Feedback Controller/////////////////////////////////////////////////////
    public function getOverallFeedbacks() {
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
            COUNT(f.tracking_id) OVER (PARTITION BY r.req_id) AS total_feedback,
            
            -- Count total requests from VW_rqtrack
            (
                SELECT COUNT(*) 
                FROM VW_rqtrack v
                WHERE v.req_id = r.req_id
            ) AS total_requests

        FROM VW_feedback AS f
        LEFT JOIN request AS r 
            ON f.tracking_id = r.tracking_id
        LEFT JOIN requester AS rq 
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

    public function toggleAdminMenuAccess($staff_id, $enabled){
        $sql = "REPLACE INTO add_admin_access (staff_id, is_enabled) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $staff_id, $enabled);
        return $stmt->execute();
    }

    public function getAdminMenuAccess($staff_id){
        $sql = "SELECT is_enabled FROM add_admin_access WHERE staff_id = ?";
        $stmt = $this->db->prepare($sql);
        if(!$stmt) return 0;
        $stmt->bind_param("s", $staff_id);
        $stmt->execute();
        $is_enabled = null;
        $stmt->bind_result($is_enabled);
        $fetched = $stmt->fetch(); // returns true if a row exists
        $stmt->close();

        return ($fetched && $is_enabled !== null) ? (int)$is_enabled : 0;
    }
}


