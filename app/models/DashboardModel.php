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
                ) AS total_pending
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

    // Destructor
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
