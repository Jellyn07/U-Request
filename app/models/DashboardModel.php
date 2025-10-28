<?php
// filepath: app/models/DashboardModel.php

require_once __DIR__ . '/../core/BaseModel.php';

class DashboardModel extends BaseModel  {

    // SUMMARY - Total Requests, Pending, Approved, Admins, Users
    public function getSummary($year) {
        $sql = "
            SELECT 
                (
                    (SELECT COUNT(*) FROM request WHERE YEAR(request_date) = ?)
                ) AS total_rrequests,

                (
                    (SELECT COUNT(*) FROM vehicle_request WHERE YEAR(date_request) = ?)
                ) AS total_vrequests,

                (
                    (SELECT COUNT(*) FROM gsu_personnel)
                ) AS totalgPersonnel,

                (
                    (SELECT COUNT(*) FROM requester)
                ) AS total_user,

                (
                    (SELECT COUNT(*) FROM administrator)
                ) AS total_admin,

                (
                   SELECT COUNT(*) FROM vw_requests WHERE req_status IN ('To Inspect', 'In Progress')
                ) AS total_pending,

                (
                   SELECT COUNT(*) FROM driver
                ) AS totalDrivers,

                (
                   SELECT COUNT(*) FROM vehicle_request vr INNER JOIN vehicle_request_assignment vra ON vr.req_id = vra.req_id WHERE vra.req_status = 'Pending'
                ) AS total_vrequests_p

        ";
    
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed (Summary): " . $this->db->error;
            return null;
        }
    
        // 2 placeholders only
        $stmt->bind_param("ii", $year, $year);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    
        return $result;
    }
    

    // MONTHLY - Facility vs Vehicle Requests
    public function getMonthlyRequests($year) {
        // Facility requests
        $sqlFacility = "
            SELECT MONTH(request_date) AS month, COUNT(*) AS total
            FROM vw_requests
            WHERE YEAR(request_date) = ?
            GROUP BY MONTH(request_date)
        ";

        $stmt = $this->db->prepare($sqlFacility);
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed (Facility): " . $this->db->error;
            return ['facility' => [], 'vehicle' => []];
        }
        $stmt->bind_param("i", $year);
        $stmt->execute();
        $rowsFacility = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Vehicle requests
        $sqlVehicle = "
            SELECT MONTH(date_request) AS month, COUNT(*) AS total
            FROM vehicle_request
            WHERE YEAR(date_request) = ?
            GROUP BY MONTH(date_request)
        ";

        $stmt = $this->db->prepare($sqlVehicle);
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed (Vehicle): " . $this->db->error;
            return ['facility' => [], 'vehicle' => []];
        }
        $stmt->bind_param("i", $year);
        $stmt->execute();
        $rowsVehicle = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Initialize 12 months (1–12) with 0
        $facility = array_fill(1, 12, 0);
        $vehicle  = array_fill(1, 12, 0);

        foreach ($rowsFacility as $row) {
            $facility[(int)$row['month']] = (int)$row['total'];
        }
        foreach ($rowsVehicle as $row) {
            $vehicle[(int)$row['month']] = (int)$row['total'];
        }

        return [
            'facility' => array_values($facility),
            'vehicle'  => array_values($vehicle)
        ];
    }

    // Get profile data by email
    public function getProfileByEmail($admin_email)
    {
        $stmt = $this->db->prepare("
            SELECT profile_picture
            FROM administrator
            WHERE email = ?
        ");
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns single row
    }

    // Get number of requests per building (matching by building name)
    public function getBuildingRequestsData() {
        $sql = "
         SELECT cl.building, COUNT(r.request_id) AS total_requests FROM campus_locations cl
            LEFT JOIN request r ON r.location LIKE CONCAT('%', cl.building, '%') GROUP BY cl.building ORDER BY total_requests DESC;
        ";
        $result = $this->db->query($sql);
        $rows = [];
        if ($result) {
            while ($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }
        }
        return $rows;
    }

    public function getWorkloadData() {
        $sql = "
             SELECT 
            rp.firstName,
            rp.lastName,
            r.request_Type,
            COUNT(ra.request_id) AS total
        FROM gsu_personnel rp
        LEFT JOIN request_assigned_personnel ra
            ON rp.staff_id = ra.staff_id
        LEFT JOIN request r
            ON ra.request_id = r.request_id
        GROUP BY rp.staff_id, r.request_Type
        ORDER BY rp.firstName, r.request_Type;
        ";

        $result = $this->db->query($sql);
        $rows = [];
        if ($result) {
            while ($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }
        }
        return $rows;
    }


    //Vehicle Request Pie
    public function getVehicleRequestStatusCounts() {
        $sql = "
            SELECT
                (SELECT COUNT(*) FROM vehicle_request vr
                    INNER JOIN vehicle_request_assignment vra ON vr.req_id = vra.req_id
                    WHERE vra.req_status = 'Pending') AS pending,
                (SELECT COUNT(*) FROM vehicle_request vr
                    INNER JOIN vehicle_request_assignment vra ON vr.req_id = vra.req_id
                    WHERE vra.req_status = 'Approved') AS approved,
                (SELECT COUNT(*) FROM vehicle_request vr
                    INNER JOIN vehicle_request_assignment vra ON vr.req_id = vra.req_id
                    WHERE vra.req_status = 'In Progress') AS in_progress,
                (SELECT COUNT(*) FROM vehicle_request vr
                    INNER JOIN vehicle_request_assignment vra ON vr.req_id = vra.req_id
                    WHERE vra.req_status = 'Completed') AS completed,
                (SELECT COUNT(*) FROM vehicle_request vr
                    INNER JOIN vehicle_request_assignment vra ON vr.req_id = vra.req_id
                    WHERE vra.req_status IN ('Rejected', 'Cancelled')) AS rejected_cancelled
        ";

        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }

    public function getVehicleUsageData() {
        // Fetch all vehicles
        $vehicles = [];
        $sql = "SELECT vehicle_id, vehicle_name FROM vehicle ORDER BY vehicle_name ASC";
        $res = $this->db->query($sql);
        if ($res) {
            while ($r = $res->fetch_assoc()) $vehicles[] = $r;
        }

        // Fetch trip counts per vehicle from vehicle_request table
        $counts = [];
        $sql2 = "SELECT vehicle_id, COUNT(request_id) AS trips FROM vehicle_request GROUP BY vehicle_id";
        $res2 = $this->db->query($sql2);
        if ($res2) {
            while ($r = $res2->fetch_assoc()) {
                $counts[$r['vehicle_id']] = $r['trips'];
            }
        }

        // Merge names and counts, default 0 if no trips
        $data = [];
        foreach ($vehicles as $v) {
            $data[] = [
                'vehicle_name' => $v['vehicle_name'],
                'trips' => isset($counts[$v['vehicle_id']]) ? (int)$counts[$v['vehicle_id']] : 0
            ];
        }
        return $data;
    }

    // Destructor
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
