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

    // Check if passenger exists by first and last name
    public function getPassengerByName($first_name, $last_name) {
        $stmt = $this->db->prepare("SELECT passenger_id FROM passengers WHERE firstName = ? AND lastName = ?");
        $stmt->bind_param("ss", $first_name, $last_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $passenger = $result->fetch_assoc();
        $stmt->close();

        return $passenger['passenger_id'] ?? null;
    }

// Fetch all vehicles with their assigned driver_id
public function getVehicles() {
    $sql = "SELECT vehicle_id, vehicle_name, driver_id FROM vehicle ORDER BY vehicle_name ASC";
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

public function saveAssignment($reqAssignment_id, $vehicle_id, $driver_id, $req_status, $approved_by) {
        // Make sure stored procedure exists and parameter order matches
        // Use NULL or empty string handling depending on your SP
        try {
            // prepare
            $stmt = $this->db->prepare("CALL spUpdateVehicleRequestAssignment(?, ?, ?, ?, ?)");
            if (!$stmt) {
                $err = $this->db->error;
                error_log("Prepare failed: $err");
                return ['success' => false, 'message' => "Prepare failed: $err"];
            }

            // If driver_id can be null, convert to null + bind as integer or null appropriately
            // mysqli doesn't handle nulls in bind_param gracefully; pass as int or empty string as needed.
            // We'll bind driver_id as integer (0 if null). If your SP expects NULL, you could use a different approach.
            $bind_driver = ($driver_id === null || $driver_id === '') ? 0 : (int)$driver_id;
            $bind_req_status = $req_status ?? '';
            $bind_approved_by = $approved_by ?? '';

            // bind params: iiiss (int,int,int,string,string)
            if (!$stmt->bind_param('iiiss', $reqAssignment_id, $vehicle_id, $bind_driver, $bind_req_status, $bind_approved_by)) {
                $err = $stmt->error;
                error_log("Bind failed: $err");
                return ['success' => false, 'message' => "Bind failed: $err"];
            }

            if (!$stmt->execute()) {
                $err = $stmt->error;
                error_log("Execute failed: $err");
                return ['success' => false, 'message' => "Execute failed: $err"];
            }

            // optionally check affected rows
            $affected = $stmt->affected_rows;
            $stmt->close();
            return ['success' => true, 'message' => "Saved. Rows affected: $affected"];
        } catch (Exception $e) {
            error_log("saveAssignment exception: " . $e->getMessage());
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
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
