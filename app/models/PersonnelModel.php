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
