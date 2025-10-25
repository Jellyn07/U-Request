<?php
require_once __DIR__ . '/../core/BaseModel.php'; 
require_once __DIR__ . '/../config/db_helpers.php';

class VehicleRequestModel extends BaseModel {
    public $lastError = null;

    // 1. Add Vehicle Request
    public function addVehicleRequest(
        $req_id,
        $tracking_id,
        $trip_purpose,
        $travel_destination,
        $travel_date,
        $return_date,
        $departure_time,
        $return_time,
        $source_of_fuel,
        $source_of_oil,
        $source_of_repair_maintenance,
        $source_of_driver_assistant_per_diem) {
        // Step 1: Add Vehicle Request
    
    if (isset($_SESSION['req_id'])) {
            setCurrentRequester($this->db); // Use model's connection
        }

    $stmt = $this->db->prepare("CALL spAddVehicleRequest(?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "isssssss",
        $req_id,
        $tracking_id,
        $trip_purpose,
        $travel_destination,
        $travel_date,
        $return_date,
        $departure_time,
        $return_time
    );

        if (!$stmt->execute()) {
            $this->lastError = "VehicleRequest Execute Error: " . $stmt->error;
            $stmt->close();
            return false;
        }

        // Fetch the control_no
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        $control_no = $row['control_no'] ?? null;
        if (!$control_no) {
            $this->lastError = "Failed to retrieve control_no";
            return false;
        }

        // Step 2: Add Source of Fund
        $stmt2 = $this->db->prepare("CALL spAddSourceOfFund(?, ?, ?, ?, ?)");
        if (!$stmt2) {
            $this->lastError = "SourceOfFund Prepare Error: " . $this->db->error;
            return false;
        }

        $stmt2->bind_param(
            "issss",
            $control_no,
            $source_of_fuel,
            $source_of_oil,
            $source_of_repair_maintenance,
            $source_of_driver_assistant_per_diem
        );

        if (!$stmt2->execute()) {
            $this->lastError = "SourceOfFund Execute Error: " . ($stmt2->error ?: $this->db->error);
            $stmt2->close();
            return false;
        }

        $stmt2->close();

        return $control_no;
    }

    // 2. Add Passenger
    public function addPassenger($firstName, $lastName) {
        $stmt = $this->db->prepare("
            CALL spAddPassenger(?, ?)
        ");
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }

        $stmt->bind_param("ss", $firstName, $lastName);

        if (!$stmt->execute()) {
            $this->lastError = $stmt->error ?: $this->db->error;
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$result || empty($result['passenger_id'])) {
            $this->lastError = 'Failed to retrieve passenger_id';
            return false;
        }

        return $result['passenger_id'];
    }

    // 3. Link Passenger to Vehicle Request
    public function linkPassenger($control_no, $passenger_id) {
        $stmt = $this->db->prepare("
            CALL spVehicleRequestPassengers(?, ?)
        ");
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }

        $stmt->bind_param("ii", $control_no, $passenger_id);

        if (!$stmt->execute()) {
            $this->lastError = $stmt->error ?: $this->db->error;
            $stmt->close();
            return false;
        }

        $stmt->close();
        return true; // ✅ success
    }
    public function getLastError() {
    return $this->lastError;
}

}
