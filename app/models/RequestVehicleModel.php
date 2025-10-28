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
    // Fetch all vehicles
    public function getVehicles() {
        $sql = "SELECT vehicle_id, vehicle_name FROM vehicle ORDER BY vehicle_name ASC";
        $res = $this->db->query($sql);
        $vehicles = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $vehicles[] = $row;
            }
        }
        return $vehicles;
    }

    // Fetch all personnel (drivers)
    public function getDriver() {
        $sql = "SELECT driver_id, CONCAT(firstName, ' ', lastName) AS full_name FROM driver ORDER BY firstName ASC";
        $res = $this->db->query($sql);
        $personnels = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $personnels[] = $row;
            }
        }
        return $personnels;
    }

    // Optional: save assignment
    public function assignVehicle($request_id, $vehicle_id, $staff_id, $priority_status) {
        $stmt = $this->db->prepare("
            INSERT INTO vehicle_request_assignment (request_id, vehicle_id, staff_id, priority_status)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                vehicle_id = VALUES(vehicle_id),
                staff_id = VALUES(staff_id),
                priority_status = VALUES(priority_status)
        ");
        $stmt->bind_param("iiis", $request_id, $vehicle_id, $staff_id, $priority_status);
        return $stmt->execute();
    }
    public function getLastError() {
    return $this->lastError;
}

}
