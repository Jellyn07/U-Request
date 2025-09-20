<?php
// filepath: app/models/PersonnelModel.php
require_once __DIR__ . '/../core/BaseModel.php';

class PersonnelModel extends BaseModel {

    // Get all personnel
    public function getAllPersonnel() {
        $sql = "SELECT staff_id, firstName, lastName, CONCAT (firstName, ' ' ,lastName) as full_name, department, contact,hire_date, unit FROM gsu_personnel ORDER BY full_name ASC";
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
        $stmt = $this->db->prepare("CALL spAddGsuPersonnel (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", 
            $data['staff_id'],
            $data['firstName'],
            $data['lastName'],
            $data['department'],
            $data['contact'],
            $data['hire_date'],
            $data['unit']
        );
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    

    // Update personnel
    public function updatePersonnel($data) {
        $stmt = $this->db->prepare("CALL spUpdateGsuPersonnel (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss",
            $data['staff_id'],
            $data['firstName'],
            $data['lastName'],
            $data['department'],
            $data['contact'],
            $data['hire_date'],
            $data['unit']
        );
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Delete personnel
    public function deletePersonnel($staff_id) {
        $stmt = $this->db->prepare("DELETE FROM gsu_personnel WHERE staff_id = ?");
        $stmt->bind_param("i", $staff_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
