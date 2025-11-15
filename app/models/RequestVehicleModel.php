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
        $source_of_driver_assistant_per_diem ) {

        try {

            if (isset($_SESSION['req_id'])) {
                setCurrentRequester($this->db); // Use model's connection
            }
            // Call the stored procedure
            $stmt = $this->db->prepare("CALL spAddVehicleRequest(?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }

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
                throw new Exception("Execute failed: " . $stmt->error);
            }

            // ✅ Fetch control_no from result
            $result = $stmt->get_result();
            if (!$result) {
                throw new Exception("No result returned from spAddVehicleRequest");
            }

            $row = $result->fetch_assoc();
            $control_no = $row['control_no'] ?? null;

            $stmt->close();

            if (!$control_no) {
                throw new Exception("No control_no returned from procedure");
            }

            // ✅ Continue with source of fund
            $stmt2 = $this->db->prepare("CALL spAddSourceOfFund(?, ?, ?, ?, ?)");
            $stmt2->bind_param(
                "issss",
                $control_no,
                $source_of_fuel,
                $source_of_oil,
                $source_of_repair_maintenance,
                $source_of_driver_assistant_per_diem
            );

            if (!$stmt2->execute()) {
                throw new Exception("Execute failed for spAddSourceOfFund: " . $stmt2->error);
            }

            $stmt2->close();

            return $control_no;

        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log("VehicleRequestModel Error: " . $this->lastError);
            return false;
        }
    }

    public function addAssignment($control_no, $req_id, $vehicle_id = null, $driver_id = null, $req_status = 'Pending', $approved_by = null) {
        try {
            // 🧹 Clear any previous stored procedure results
            while ($this->db->more_results() && $this->db->next_result()) {;}

            // 🧠 Debug log
            error_log("📥 addAssignment() called with: control_no=$control_no, req_id=$req_id, status=$req_status");

            // Prepare stored procedure
            $stmt = $this->db->prepare("CALL spAddVehicleRequestAssignment(?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }

            // Bind parameters
            $stmt->bind_param("iiiiss", $control_no, $req_id, $vehicle_id, $driver_id, $req_status, $approved_by);

            // Execute procedure
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            // Close and clear
            $stmt->close();
            $this->db->next_result();

            error_log("✅ addAssignment() success for control_no=$control_no");
            return true;

        } catch (Exception $e) {
            error_log("❌ addAssignment() ERROR: " . $e->getMessage());
            return false;
        }
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
    
    public function updateRequestStatusByToken($token, $status) {
        $sql = "UPDATE vehicle_request SET req_status = ? WHERE approval_token = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $status, $token);
        return $stmt->execute();
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
public function getVehicleRequestByControlNo($controlNo) {
    $sql = "SELECT vr.*, CONCAT(r.firstName, ' ', r.lastName) AS requester_name
            FROM vehicle_request vr
            JOIN requester r ON vr.req_id = r.req_id
            WHERE vr.control_no = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $controlNo);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

    public function updateAssignment($control_no, $vehicle_id = null, $req_status = null, $approved_by = null, $reason = null) {
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db);
        }

        try {
            $driver_id = null;

            if (!empty($vehicle_id)) {
                $driverQuery = "SELECT driver_id FROM vehicle WHERE vehicle_id = ?";
                $stmtDriver = $this->db->prepare($driverQuery);
                $stmtDriver->bind_param("i", $vehicle_id);
                $stmtDriver->execute();
                $driverResult = $stmtDriver->get_result();

                if ($driverRow = $driverResult->fetch_assoc()) {
                    $driver_id = $driverRow['driver_id'];
                }
                $stmtDriver->close();
            }

            $fields = [];
            $params = [];
            $types = "";

            if (!empty($vehicle_id)) {
                $fields[] = "vehicle_id = ?";
                $params[] = $vehicle_id;
                $types .= "i";
            }

            if (!is_null($driver_id)) {
                $fields[] = "driver_id = ?";
                $params[] = $driver_id;
                $types .= "i";
            }

            if (!empty($req_status)) {
                $fields[] = "req_status = ?";
                $params[] = $req_status;
                $types .= "s";
            }

            if (!empty($approved_by)) {
                $fields[] = "approved_by = ?";
                $params[] = $approved_by;
                $types .= "s";
            }

            // NEW: Add Reason
            if (!empty($reason)) {
                $fields[] = "reason = ?";
                $params[] = $reason;
                $types .= "s";
            }

            if (empty($fields)) {
                throw new Exception("No fields to update.");
            }

            $query = "UPDATE vehicle_request_assignment SET " . implode(", ", $fields) . " WHERE control_no = ?";
            $params[] = $control_no;
            $types .= "s";

            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();

            return $stmt->affected_rows > 0;

        } catch (Exception $e) {
            error_log("Update Error: " . $e->getMessage());
            return false;
        }
    }
}
