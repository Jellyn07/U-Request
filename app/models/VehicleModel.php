<?php
require_once __DIR__ . '/../core/BaseModel.php'; 
require_once __DIR__ . '/../config/db_helpers.php';
class VehicleModel extends BaseModel {
    // Get all drivers
    public function getDrivers() {
        $sql = "SELECT driver_id, CONCAT(firstName, ' ', lastName) AS driver_name FROM driver ORDER BY driver_name ASC;";
        $result = $this->db->query($sql);
        $drivers = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $drivers[] = $row;
            }
        }
        return $drivers;
    }

    // Check if plate number exists
    public function isPlateExists($plate_no) {
        $stmt = $this->db->prepare("SELECT vehicle_id FROM vehicle WHERE plate_no = ?");
        $stmt->bind_param("s", $plate_no);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Add new vehicle
    public function addVehicle($data) {
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
        $stmt = $this->db->prepare("INSERT INTO vehicle (vehicle_name, plate_no, capacity, vehicle_type, driver_id, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisis", 
            $data['vehicle_name'], 
            $data['plate_no'], 
            $data['capacity'], 
            $data['vehicle_type'], 
            $data['driver_id'], 
            $data['photo']
        );
        return $stmt->execute();
    }

    public function getVehicles() {
        $query = "SELECT 
                    v.vehicle_id,
                    v.vehicle_name,
                    v.plate_no,
                    v.capacity,
                    v.vehicle_type,
                    v.photo,
                    v.status,
                    v.driver_id,
                    CONCAT(d.firstName, ' ', d.lastName) AS driver_name
                  FROM vehicle v
                  LEFT JOIN driver d ON v.driver_id = d.driver_id
                  ORDER BY v.vehicle_name ASC";

        $result = mysqli_query($this->db, $query);
        $vehicles = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $vehicles[] = $row;
            }
        }

        return $vehicles;
    }

    public function getVehicle($control_no, $travel_date, $return_date) {
        $query = "
            SELECT 
                v.vehicle_id,
                v.vehicle_name,
                v.driver_id,
                CONCAT(d.firstName, ' ', d.lastName) AS driver_name
            FROM vehicle v
            LEFT JOIN driver d ON v.driver_id = d.driver_id
            WHERE v.status NOT IN ('Under Maintenance', 'In Use', 'Out of Use')
            AND v.vehicle_id NOT IN (
                SELECT va.vehicle_id
                FROM vehicle_request_assignment va
                JOIN vehicle_request vr ON va.control_no = vr.control_no
                WHERE NOT (
                    vr.return_date < ? OR vr.travel_date > ?  -- no overlap
                )
                AND vr.control_no != ?  -- exclude current request
            )
            ORDER BY v.vehicle_name ASC;
        ";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->db->error);
            return [];
        }

        // Bind the travel_date and return_date for overlap check, and current control_no
        $stmt->bind_param("ssi", $travel_date, $return_date, $control_no);
        $stmt->execute();
        $result = $stmt->get_result();

        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            $vehicles[] = $row;
        }

        return $vehicles;
    }

    public function updateVehicle($data) {
        $photo = $data['photo'] ?? null;

        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db);
        }

        // Step 1: Check if new vehicle name already exists for another vehicle
        $stmtCheckName = $this->db->prepare("SELECT vehicle_id FROM vehicle WHERE vehicle_name = ? AND vehicle_id != ?");
        if (!$stmtCheckName) {
            error_log("Prepare failed (name check): " . $this->db->error);
            return ['success' => false, 'error' => 'Database error.'];
        }
        $stmtCheckName->bind_param("si", $data['vehicle_name'], $data['vehicle_id']);
        $stmtCheckName->execute();
        $resultName = $stmtCheckName->get_result();
        if ($resultName->num_rows > 0) {
            return ['success' => false, 'error' => 'Vehicle name already exists.'];
        }
        $stmtCheckName->close();

        // Step 2: Check if new plate number already exists for another vehicle
        $stmtCheckPlate = $this->db->prepare("SELECT vehicle_id FROM vehicle WHERE plate_no = ? AND vehicle_id != ?");
        if (!$stmtCheckPlate) {
            error_log("Prepare failed (plate check): " . $this->db->error);
            return ['success' => false, 'error' => 'Database error.'];
        }
        $stmtCheckPlate->bind_param("si", $data['plate_no'], $data['vehicle_id']);
        $stmtCheckPlate->execute();
        $resultPlate = $stmtCheckPlate->get_result();
        if ($resultPlate->num_rows > 0) {
            return ['success' => false, 'error' => 'Plate number already exists.'];
        }
        $stmtCheckPlate->close();

        // Step 3: Fetch current values
        $stmtCheck = $this->db->prepare("SELECT vehicle_name, plate_no, capacity, vehicle_type, driver_id, status, photo FROM vehicle WHERE vehicle_id = ?");
        if (!$stmtCheck) {
            error_log("Prepare failed (fetch current): " . $this->db->error);
            return ['success' => false, 'error' => 'Database error.'];
        }
        $stmtCheck->bind_param("i", $data['vehicle_id']);
        $stmtCheck->execute();
        $current = $stmtCheck->get_result()->fetch_assoc();
        $stmtCheck->close();

        // Step 4: Check for redundant update
        if (
            $current['vehicle_name'] === $data['vehicle_name'] &&
            $current['plate_no'] === $data['plate_no'] &&
            (int)$current['capacity'] === (int)$data['capacity'] &&
            $current['vehicle_type'] === $data['vehicle_type'] &&
            ((int)$current['driver_id'] === (int)$data['driver_id']) &&
            $current['status'] === $data['status'] &&
            (($photo === null) || $current['photo'] === $photo)
        ) {
            return ['success' => false, 'error' => 'No changes detected.'];
        }

        // Step 5: Perform update
        $query = "UPDATE vehicle 
                SET vehicle_name = ?, 
                    plate_no = ?, 
                    capacity = ?, 
                    vehicle_type = ?, 
                    driver_id = ?, 
                    status = ?, 
                    photo = COALESCE(?, photo)
                WHERE vehicle_id = ?";

        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed (update): " . $this->db->error);
            return ['success' => false, 'error' => 'Database error.'];
        }

        $stmt->bind_param(
            "ssisissi",
            $data['vehicle_name'],
            $data['plate_no'],
            $data['capacity'],
            $data['vehicle_type'],
            $data['driver_id'],
            $data['status'],
            $photo,
            $data['vehicle_id']
        );

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            error_log("Execute failed: " . $stmt->error);
            return ['success' => false, 'error' => 'Update failed.'];
        }
    }
    
    public function getVehicleTravelHistory($vehicle_id, $type = 'history') {
        $today = date('Y-m-d');

        $query = "
            SELECT 
                vra.reqAssignment_id,
                vra.control_no,
                vra.req_id,
                vra.vehicle_id,
                vra.driver_id,
                vra.req_status,
                vra.approved_by,
                CONCAT(d.firstName, ' ', d.lastName) AS driver_name,
                vr.trip_purpose,
                vr.travel_date
            FROM vehicle_request_assignment vra
            INNER JOIN vehicle_request vr 
                ON vra.control_no = vr.control_no
            LEFT JOIN driver d 
                ON vra.driver_id = d.driver_id
            WHERE vra.vehicle_id = ?
        ";

        if ($type === 'history') {
            $query .= " AND LOWER(vra.req_status) = 'completed' AND vr.travel_date <= ? ORDER BY vr.travel_date DESC";
        } elseif ($type === 'schedule') {
            $query .= " AND LOWER(vra.req_status) = 'approved' AND vr.travel_date > ? ORDER BY vr.travel_date ASC";
        }

        $stmt = $this->db->prepare($query);
        if (!$stmt) die(json_encode(['error' => 'Prepare failed: ' . $this->db->error]));

        $stmt->bind_param("is", $vehicle_id, $today);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $result;
    }

    public function getLastMaintenance($vehicleName) {
        $targetStatus = 'Under Maintenance';
        $pattern = "%" . $vehicleName . "%$targetStatus%";

        $query = "SELECT changed_at 
                FROM activity_logs 
                WHERE description LIKE ?
                ORDER BY changed_at DESC
                LIMIT 1";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $pattern);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['changed_at'];
        }

        return null;
    }
}
