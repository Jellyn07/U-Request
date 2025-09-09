<?php
require_once __DIR__ . '/../core/BaseModel.php'; 

class VehicleRequestModel extends BaseModel {
    public $lastError = null;

    // 1. Add Vehicle Request
    public function addVehicleRequest($req_id, $tracking_id, $trip_purpose, $travel_destination, $travel_date, $return_date, $departure_time, $return_time) {
        $stmt = $this->db->prepare("
            CALL spAddVehicleRequest(?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) {
            $this->lastError = $this->db->error;
            return false;
        }

        // ✅ Now includes req_id (int)
        $stmt->bind_param(
            "isssssss",
            $req_id, $tracking_id, $trip_purpose, $travel_destination,
            $travel_date, $return_date, $departure_time, $return_time
        );

        if (!$stmt->execute()) {
            $this->lastError = $stmt->error ?: $this->db->error;
            $stmt->close();
            return false;
        }

        // Fetch control_no from stored procedure
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$result || empty($result['control_no'])) {
            $this->lastError = 'Failed to retrieve control_no';
            return false;
        }

        return $result['control_no'];
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
}
