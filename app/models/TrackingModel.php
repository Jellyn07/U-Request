<?php
require_once __DIR__ . '/../core/BaseModel.php';

class TrackingModel extends BaseModel {

    // Get all tracking requests by email (merged: repair + vehicle)
    public function getTrackingByEmail($email) {
        $tracking = [];

        // --- REPAIR (vw_rqtrack) ---
        $sqlRepair = "
            SELECT 
                t.tracking_id,
                t.request_Type AS nature_request,
                t.location,
                t.req_status,
                t.date_finished,
                t.req_id,
                t.request_desc
            FROM vw_rqtrack t
            INNER JOIN requester r ON t.req_id = r.req_id
            WHERE r.email = ?
        ";
        $stmt = $this->db->prepare($sqlRepair);
        if ($stmt === false) {
            error_log("getTrackingByEmail prepare (repair) failed: " . $this->db->error);
            return []; // safe fallback
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $tracking[] = $row;
        }
        $stmt->close();

        // --- VEHICLE REQUEST (vehicle_request) ---
        // NOTE: vehicle_request must have req_id column to join with requester
        $sqlVehicle = "
            SELECT 
                v.tracking_id,
                'Vehicle Request' AS nature_request,
                v.travel_destination AS location,
                COALESCE(v.req_status, 'Pending') AS req_status,
                v.return_date AS date_finished,
                v.req_id,
                NULL AS request_desc,
                v.trip_purpose,
                v.travel_destination,
                v.travel_date,
                v.return_date
            FROM vehicle_request v
            INNER JOIN requester r ON v.req_id = r.req_id
            WHERE r.email = ?
        ";
        $stmt2 = $this->db->prepare($sqlVehicle);
        if ($stmt2 === false) {
            error_log("getTrackingByEmail prepare (vehicle) failed: " . $this->db->error);
            // return repair rows if vehicle query fails
            usort($tracking, function($a,$b){ return strcmp($b['tracking_id'], $a['tracking_id']); });
            return $tracking;
        }
        $stmt2->bind_param("s", $email);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        while ($row = $res2->fetch_assoc()) {
            $tracking[] = $row;
        }
        $stmt2->close();

        // Sort combined results by tracking_id DESC (string compare)
        usort($tracking, function ($a, $b) {
            return strcmp($b['tracking_id'], $a['tracking_id']);
        });

        return $tracking;
    }

    // Get repair tracking requests by email
    public function getRepairTrackingByEmail($email) {
        $sqlRepair = "
            SELECT 
                t.tracking_id,
                t.request_Type AS nature_request,
                t.location,
                t.req_status,
                t.date_finished,
                t.req_id,
                t.request_desc,
                t.request_date,
                NULL AS trip_purpose,
                NULL AS travel_destination,
                NULL AS travel_date,
                NULL AS return_date
            FROM vw_rqtrack t
            INNER JOIN requester r ON t.req_id = r.req_id
            WHERE r.email = ?
        ";
        $stmt = $this->db->prepare($sqlRepair);
        if ($stmt === false) {
            error_log("getRepairTrackingByEmail prepare failed: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $tracking = [];
        while ($row = $res->fetch_assoc()) {
            $tracking[] = $row;
        }
        $stmt->close();

        // Sort by tracking_id DESC
        usort($tracking, function ($a, $b) {
            return strcmp($b['tracking_id'], $a['tracking_id']);
        });

        return $tracking;
    }

    // Get vehicle tracking requests by email
    public function getVehicleTrackingByEmail($email) {
        $sqlVehicle = "
            SELECT 
                v.*, 
                vr.req_status,
                vr.reason
            FROM vehicle_request v
            INNER JOIN requester r ON v.req_id = r.req_id
            LEFT JOIN vehicle_request_assignment vr ON v.control_no = vr.control_no
            WHERE r.email = ?
        ";
        $stmt = $this->db->prepare($sqlVehicle);
        if ($stmt === false) {
            error_log("getVehicleTrackingByEmail prepare failed: " . $this->db->error);
            return [];
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $tracking = [];
        while ($row = $res->fetch_assoc()) {
            $tracking[] = $row;
        }
        $stmt->close();

        // Sort by tracking_id DESC
        usort($tracking, function ($a, $b) {
            return strcmp($b['tracking_id'], $a['tracking_id']);
        });

        return $tracking;
    }

    // Get single tracking details by email + tracking_id
    public function getTrackingDetails($tracking_id, $email) {
        // 1) try repair/vw_rqtrack
        $sqlRepair = "
            SELECT 
                t.tracking_id,
                t.request_Type AS nature_request,
                t.location,
                t.req_status,
                t.date_finished,
                t.req_id,
                t.request_desc,
                r2.image_path
            FROM vw_rqtrack t
            INNER JOIN requester r ON t.req_id = r.req_id
            LEFT JOIN request r2 ON t.tracking_id = r2.tracking_id
            WHERE r.email = ? AND t.tracking_id = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sqlRepair);
        if ($stmt === false) {
            error_log("getTrackingDetails prepare (repair) failed: " . $this->db->error);
            // fall through to vehicle
        } else {
            $stmt->bind_param("ss", $email, $tracking_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $repair = $res->fetch_assoc();
            $stmt->close();
            if ($repair) {
                return $repair;
            }
        }

        // 2) try vehicle_request
            $sqlVehicle = "
            SELECT 
                v.*
            FROM vehicle_request v
            INNER JOIN requester r ON v.req_id = r.req_id
            WHERE r.email = ? AND v.tracking_id = ?
            LIMIT 1
            ";
            $stmt2 = $this->db->prepare($sqlVehicle);
            if ($stmt2 === false) {
            error_log("getTrackingDetails prepare (vehicle) failed: " . $this->db->error);
            return null;
            }
            $stmt2->bind_param("ss", $email, $tracking_id);
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $vehicle = $res2->fetch_assoc();
            $stmt2->close();

        // fetch passengers for this control_no
        $sqlPassengers = "
            SELECT p.passenger_id, p.firstName AS first_name, p.lastName AS last_name
            FROM vehicle_request_passengers vrp
            INNER JOIN passengers p ON vrp.passenger_id = p.passenger_id
            WHERE vrp.control_no = ?
        ";
        $stmt3 = $this->db->prepare($sqlPassengers);
        if ($stmt3 === false) {
            error_log("getTrackingDetails prepare (passengers) failed: " . $this->db->error);
            $vehicle['passengers'] = [];
            return $vehicle;
        }
        $stmt3->bind_param("i", $vehicle['control_no']);
        $stmt3->execute();
        $res3 = $stmt3->get_result();
        $passengers = [];
        while ($row = $res3->fetch_assoc()) {
            $passengers[] = $row;
        }
        $stmt3->close();

        $vehicle['passengers'] = $passengers;
        return $vehicle;
    }

}
