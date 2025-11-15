<?php
require_once __DIR__ . '/../core/BaseModel.php'; 
require_once __DIR__ . '/../config/db_helpers.php';

class LocationModel extends BaseModel{
    // 🟢 Fetch all locations
    public function getAllLocations() {
        $stmt = $this->db->prepare("SELECT * FROM campus_locations ORDER BY date_added DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllBuildings() {
        $stmt = $this->db->prepare("SELECT DISTINCT building FROM campus_locations WHERE building IS NOT NULL ORDER BY building ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get buildings by unit
    public function getBuildingsByUnit($unit) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT building 
            FROM campus_locations 
            WHERE unit = ? AND building IS NOT NULL
            ORDER BY building ASC
        ");
        $stmt->bind_param("s", $unit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // 🟢 Add new location
    public function addLocation($unit, $building, $exact_location) {
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
        // Check if the location already exists
        $checkStmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM campus_locations 
            WHERE unit = ? AND building = ? AND exact_location = ?
        ");
        $checkStmt->bind_param("sss", $unit, $building, $exact_location);
        $checkStmt->execute();
        $count = 0;
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();
        if ($count > 0) {
            // Location already exists
            return 'exists';
        }

        // Insert the new location
        $stmt = $this->db->prepare("
            INSERT INTO campus_locations (unit, building, exact_location)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sss", $unit, $building, $exact_location);

        return $stmt->execute() ? 'success' : 'error';
    }


    // 🟡 Update location
    public function updateLocation($id, $unit, $building, $exact_location) {
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // optional: track staff
        }

        // 1️⃣ Check for duplicate (same unit + building + exact_location, excluding current record)
        $checkStmt = $this->db->prepare("
            SELECT COUNT(*) AS count
            FROM campus_locations
            WHERE unit = ? AND building = ? AND exact_location = ? AND location_id != ?
        ");
        $checkStmt->bind_param("sssi", $unit, $building, $exact_location, $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result()->fetch_assoc();

        if ($result['count'] > 0) {
            // Duplicate exists
            return ['status' => false, 'message' => 'This exact location already exists for the same unit and building.'];
        }

        // 2️⃣ Proceed with update
        $stmt = $this->db->prepare("
            UPDATE campus_locations
            SET building = ?, exact_location = ?
            WHERE location_id = ?
        ");
        $stmt->bind_param("ssi", $building, $exact_location, $id);

        if ($stmt->execute()) {
            return ['status' => true, 'message' => 'Location updated successfully.'];
        } else {
            return ['status' => false, 'message' => 'Failed to update location.'];
        }
    }

    // 🔴 Delete location
    public function deleteLocation($id) {
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
        $stmt = $this->db->prepare("DELETE FROM campus_locations WHERE location_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // 🔍 Get specific location by ID
    public function getLocationById($id) {
        $stmt = $this->db->prepare("SELECT * FROM campus_locations WHERE location_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
