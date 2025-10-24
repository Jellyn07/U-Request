<?php
require_once __DIR__ . '/../core/BaseModel.php'; 
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
}
