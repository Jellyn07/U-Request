<?php
require_once __DIR__ . '/../core/BaseModel.php'; 

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


    // 🟢 Add new location
    public function addLocation($unit, $building, $exact_location) {
    // Check if the location already exists
        $checkStmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM campus_locations 
            WHERE unit = ? AND building = ? AND exact_location = ?
        ");
        $checkStmt->bind_param("sss", $unit, $building, $exact_location);
        $checkStmt->execute();
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
    public function updateLocation($id, $building, $exact_location) {
        $stmt = $this->db->prepare("
            UPDATE campus_locations
            SET building = ?, exact_location = ?
            WHERE location_id = ?
        ");
        $stmt->bind_param("ssi", $building, $exact_location, $id);
        return $stmt->execute();
    }

    // 🔴 Delete location
    public function deleteLocation($id) {
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
