<?php
// filepath: app/models/PersonnelModel.php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/db_helpers.php';
require_once __DIR__ . '/../config/encryption.php';

class DriverModel extends BaseModel {

    // Get all driver
    public function getAllDriver() {
       $sql = "
            SELECT 
                d.driver_id, 
                d.firstName, 
                d.lastName, 
                CONCAT(d.firstName, ' ', d.lastName) AS full_name, 
                d.contact, 
                d.hire_date, 
                d.profile_picture,
                'Available' AS status
            FROM driver d
            ORDER BY full_name ASC
        ";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get driver by ID
    public function getDriverById($staff_id) {
        $stmt = $this->db->prepare("SELECT * FROM driver WHERE driver_id = ?");
        $stmt->bind_param("i", $staff_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    // Add new driver
    public function addDriver($data) {
        // Check duplicate staff_id
        // $checkStaff = $this->db->prepare("SELECT COUNT(*) as cnt FROM driver WHERE driver_id = ?");
        // $checkStaff->bind_param("i", $data['driver_id']);
        // $checkStaff->execute();
        // $staffResult = $checkStaff->get_result()->fetch_assoc();
        // $checkStaff->close();
    
        // if ($staffResult['cnt'] > 0) {
        //     $_SESSION['driver_error'] = "Driver ID already exists!";
        //     return false;
        // }
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
        // Check duplicate contact number
        $checkContact = $this->db->prepare("SELECT COUNT(*) as cnt FROM driver WHERE contact = ?");
        $checkContact->bind_param("s", $data['contact']);
        $checkContact->execute();
        $contactResult = $checkContact->get_result()->fetch_assoc();
        $checkContact->close();
    
        if ($contactResult['cnt'] > 0) {
            $_SESSION['driver_error'] = "Contact number already exists!";
            return false;
        }
    
        // ✅ If no duplicates, proceed with insert
        $stmt = $this->db->prepare("CALL spAddDriver (?, ?, ?, ?, ?)");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed (AddDriver): " . $this->db->error;
            return false;
        }
        $stmt->bind_param("sssss", 
            $data['firstName'],
            $data['lastName'],
            $data['contact'],
            $data['hire_date'],
            $data['profile_picture']  // must match
        );

        $ok = $stmt->execute();
        if (!$ok) {
            $_SESSION['db_error'] = "Execute failed (AddDriver): " . ($stmt->error ?: $this->db->error);
        }
        $stmt->close();
        return $ok;
    }
    

    // Update driver
   public function updateDriver($data) {
        if (isset($_SESSION['staff_id'])) {
                setCurrentStaff($this->db); // Use model's connection
        }
        // ✅ Require driver_id (the record to update)
        if (empty($data['driver_id'])) {
            $_SESSION['driver_error'] = "Missing driver identifier.";
            return false;
        }

        // Normalize values
        $driverId = (int)$data['driver_id'];
        $contact = isset($data['contact']) ? trim($data['contact']) : '';

        // ✅ Check for duplicate contact numbers
        if ($contact !== '') {
            $checkContact = $this->db->prepare(
                "SELECT COUNT(*) AS cnt FROM driver WHERE contact = ? AND driver_id != ?"
            );
            if ($checkContact) {
                $checkContact->bind_param("si", $contact, $driverId);
                $checkContact->execute();
                $contactRes = $checkContact->get_result()->fetch_assoc();
                $checkContact->close();

                if (!empty($contactRes['cnt']) && $contactRes['cnt'] > 0) {
                    $_SESSION['driver_error'] = "Contact number already exists!";
                    return false;
                }
            } else {
                $_SESSION['driver_error'] = "DB error (contact check): " . $this->db->error;
                return false;
            }
        }

        // ✅ Call stored procedure
        $stmt = $this->db->prepare("CALL spUpdateDriver(?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            $_SESSION['driver_error'] = "Prepare failed (Update): " . $this->db->error;
            return false;
        }

        $stmt->bind_param(
            "isssss",
            $driverId,
            $data['firstName'],
            $data['lastName'],
            $contact,
            $data['hire_date'],
            $data['profile_picture']
        );

        $ok = $stmt->execute();
        if ($ok) {
            $_SESSION['driver_success'] = "Driver successfully updated.";
        } else {
            $_SESSION['driver_error'] = "Failed to update driver. " . ($stmt->error ?: $this->db->error);
        }

        $stmt->close();
        return $ok;
    }

    public function updateProfilePicture($staff_id, $filename) {
        if (isset($_SESSION['staff_id'])) {
                setCurrentStaff($this->db); // Use model's connection
        }
        $stmt = $this->db->prepare("UPDATE driver SET profile_picture = ? WHERE driver_id = ?");
        if (!$stmt) {
            return false;
        }

        $staff_id = (int) $staff_id;
        $stmt->bind_param("si", $filename, $staff_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            $stmt->close();
            return false; // NOTHING UPDATED
        }

        $stmt->close();
        return true;
    }

    // Delete personnel
    public function deleteDriver($staff_id) {
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
        $stmt = $this->db->prepare("DELETE FROM driver WHERE driver_id = ?");
        $stmt->bind_param("i", $staff_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Get profile data by email
    public function getProfileByEmail($admin_email){
        $encrypted_email = encrypt($admin_email);
        $stmt = $this->db->prepare("
            SELECT profile_picture
            FROM administrator
            WHERE email = ?
        ");
        $stmt->bind_param("s", $encrypted_email);
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
