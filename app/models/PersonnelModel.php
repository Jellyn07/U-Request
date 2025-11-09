<?php
// filepath: app/models/PersonnelModel.php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/db_helpers.php';

class PersonnelModel extends BaseModel {

    // Get all personnel
    public function getAllPersonnel() {
        $sql = "
            WITH personnel_status AS (
                SELECT p.*, 
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM request_assigned_personnel rap 
                            INNER JOIN request_assignment ra 
                                ON rap.request_id = ra.request_id 
                            WHERE rap.staff_id = p.staff_id 
                            AND ra.req_status = 'In Progress'
                        ) THEN 'Fixing'
                        ELSE 'Available'
                    END AS status
                FROM gsu_personnel p
            )
            SELECT 
                staff_id, 
                firstName, 
                lastName, 
                CONCAT(firstName, ' ', lastName) AS full_name, 
                department, 
                contact, 
                hire_date, 
                unit, 
                profile_picture,
                status
            FROM personnel_status
            ORDER BY full_name ASC
        ";
    
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get personnel by ID
    public function getPersonnelById($staff_id) {
        $stmt = $this->db->prepare("SELECT * FROM gsu_personnel WHERE staff_id = ?");
        $stmt->bind_param("i", $staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    // Add new personnel
    public function addPersonnel($data) {
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
        // Check duplicate staff_id
        $checkStaff = $this->db->prepare("SELECT COUNT(*) as cnt FROM gsu_personnel WHERE staff_id = ?");
        $checkStaff->bind_param("i", $data['staff_id']);
        $checkStaff->execute();
        $staffResult = $checkStaff->get_result()->fetch_assoc();
        $checkStaff->close();
    
        if ($staffResult['cnt'] > 0) {
            $_SESSION['personnel_error'] = "Staff ID already exists!";
            return false;
        }
    
        // Check duplicate contact number
        $checkContact = $this->db->prepare("SELECT COUNT(*) as cnt FROM gsu_personnel WHERE contact = ?");
        $checkContact->bind_param("s", $data['contact']);
        $checkContact->execute();
        $contactResult = $checkContact->get_result()->fetch_assoc();
        $checkContact->close();
    
        if ($contactResult['cnt'] > 0) {
            $_SESSION['personnel_error'] = "Contact number already exists!";
            return false;
        }
    
        // ✅ If no duplicates, proceed with insert
        $stmt = $this->db->prepare("CALL spAddGsuPersonnel (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed (AddPersonnel): " . $this->db->error;
            return false;
        }
        $stmt->bind_param("isssssss", 
            $data['staff_id'],
            $data['firstName'],
            $data['lastName'],
            $data['department'],
            $data['contact'],
            $data['hire_date'],
            $data['unit'],
            $data['profile_picture']  // must match
        );

        $ok = $stmt->execute();
        if (!$ok) {
            $_SESSION['db_error'] = "Execute failed (AddPersonnel): " . ($stmt->error ?: $this->db->error);
        }
        $stmt->close();
        return $ok;
    }
    

    // Update personnel
    public function updatePersonnel($data) {
        // require staff_id (the record to update)
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }

        if (empty($data['staff_id'])) {
            $_SESSION['personnel_error'] = "Missing staff identifier.";
            return false;
        }

        // Normalize values
        $staffId = (int)$data['staff_id'];
        $contact = isset($data['contact']) ? trim($data['contact']) : '';

        
        if ($contact !== '') {
            $checkContact = $this->db->prepare(
                "SELECT COUNT(*) AS cnt FROM gsu_personnel WHERE contact = ? AND staff_id != ?"
            );
            if ($checkContact) {
                $checkContact->bind_param("si", $contact, $staffId);
                $checkContact->execute();
                $contactRes = $checkContact->get_result()->fetch_assoc();
                $checkContact->close();
                if (!empty($contactRes['cnt']) && $contactRes['cnt'] > 0) {
                    $_SESSION['personnel_error'] = "Contact number already exists!";
                    return false;
                }
            } else {
                $_SESSION['personnel_error'] = "DB error (contact check): " . $this->db->error;
                return false;
            }
        }
        if (isset($data['new_staff_id']) && $data['new_staff_id'] !== '' && (int)$data['new_staff_id'] !== $staffId) {
            $newId = (int)$data['new_staff_id'];
            $checkStaff = $this->db->prepare("SELECT COUNT(*) AS cnt FROM gsu_personnel WHERE staff_id = ?");
            if ($checkStaff) {
                $checkStaff->bind_param("i", $newId);
                $checkStaff->execute();
                $staffRes = $checkStaff->get_result()->fetch_assoc();
                $checkStaff->close();
                if (!empty($staffRes['cnt']) && $staffRes['cnt'] > 0) {
                    $_SESSION['personnel_error'] = "Staff ID already exists!";
                    return false;
                }
            } else {
                $_SESSION['personnel_error'] = "DB error (staff_id check): " . $this->db->error;
                return false;
            }
        }     
        $stmt = $this->db->prepare("CALL spUpdateGsuPersonnel (?, ?, ?, ?, ?, ?, ?, ?)"); 
        if (!$stmt) {
            $_SESSION['personnel_error'] = "Prepare failed (Update): " . $this->db->error;
            return false;
        }
        $stmt->bind_param(
            "isssssss",
            $staffId,
            $data['firstName'],
            $data['lastName'],
            $data['department'],
            $contact,
            $data['hire_date'],
            $data['unit'],
            $data['profile_picture']
        );
        $ok = $stmt->execute();
        if (!$ok) {
            $_SESSION['personnel_error'] = "Failed to update personnel. " . ($stmt->error ?: $this->db->error);
        }
        $stmt->close();
        return $ok;
    }

    // Delete personnel
    public function deletePersonnel($staff_id) {
        $stmt = $this->db->prepare("DELETE FROM gsu_personnel WHERE staff_id = ?");
        $stmt->bind_param("i", $staff_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
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

    // Get GSU Personnel Work History
    public function getWorkHistory($staff_id) {
    $sql = "SELECT request_id, request_Type, date_finished 
            FROM vw_work_history 
            WHERE staff_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

}
