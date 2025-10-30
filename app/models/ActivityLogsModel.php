<?php
require_once __DIR__ . '/../core/BaseModel.php';

class ActivityLogsModel extends BaseModel {

    public function getLogs($tableFilter = 'all', $actionFilter = 'all', $dateFilter = 'all', $limit = 100) {
        $sqlParts = [];

        // 🔹 STEP 1: Detect access level
        $role = 'gsu'; // default fallback
        $accessLevelId = 0;

        if (isset($_SESSION['staff_id'])) {
            $staffId = $_SESSION['staff_id'];
            $stmt = $this->db->prepare("
                SELECT a.accessLevel_id, LOWER(aal.accessLevel_desc) AS role
                FROM administrator AS a
                JOIN admin_access_level AS aal ON a.accessLevel_id = aal.accessLevel_id
                WHERE a.staff_id = ?
            ");
            $stmt->bind_param("s", $staffId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $accessLevelId = (int)$row['accessLevel_id'];
                $role = $row['role'];
            }
            $stmt->close();
        }

        // 🔹 STEP 2: Role mapping (numeric → string)
        switch ($accessLevelId) {
            case 1:
                $role = 'super';
                break;
            case 2:
                $role = 'gsu';
                break;
            case 3:
                $role = 'motorpool';
                break;
            default:
                $role = 'gsu';
        }

        // 🔹 STEP 3: Define what each role can access
        $accessRules = [
            'super' => ['gsu_personnel', 'materials', 'request', 'status', 'assigned_personnel', 'administrator', 'campus_locations', 'driver', 'vehicle', 'vehicle_request', 'vehicle_request_assignment'],
            'gsu' => ['gsu_personnel', 'materials', 'request', 'status', 'assigned_personnel', 'campus_locations'],
            'motorpool' => ['driver', 'vehicle', 'vehicle_request', 'vehicle_request_assignment']
        ];

        // 🔹 STEP 4: Build date condition
        $dateCondition = $this->buildDateCondition($dateFilter);

        // 🔹 STEP 5: Helper for conditional SQL appending
        $addConditions = function (&$query, $conditions) {
            if (!empty($conditions)) {
                $query .= ' WHERE ' . implode(' AND ', $conditions);
            }
        };

        // 🔹 STEP 6: Generate queries only if allowed by role
        $allowedTables = $accessRules[$role] ?? [];

        // GSU Personnel
        if (in_array('gsu_personnel', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'gsu_personnel')) {
            $query = "SELECT 
                        action_date AS timestamp,
                        'GSU Personnel' AS source,
                        action_type,
                        CONCAT(firstName, ' ', lastName) AS affected_item,
                        department AS details
                      FROM gsu_personnel_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action_type = '$actionFilter'";
            if ($dateCondition) $conditions[] = $dateCondition;
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // Materials
        if (in_array('materials', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'materials')) {
            $query = "SELECT 
                        action_date AS timestamp,
                        'Materials' AS source,
                        action_type,
                        material_desc AS affected_item,
                        CONCAT('Quantity: ', qty) AS details
                      FROM materials_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action_type = '$actionFilter'";
            if ($dateCondition) $conditions[] = $dateCondition;
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // Requests
        if (in_array('request', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'request')) {
            $query = "SELECT 
                        action_date AS timestamp,
                        'Request' AS source,
                        action_type,
                        request_type AS affected_item,
                        description AS details
                      FROM request_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action_type = '$actionFilter'";
            if ($dateCondition) $conditions[] = $dateCondition;
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // Request Status
        if (in_array('status', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'status')) {
            $query = "SELECT 
                        action_date AS timestamp,
                        'Request Status' AS source,
                        'UPDATE' AS action_type,
                        CONCAT('Request ID: ', request_id) AS affected_item,
                        CONCAT('From ', old_status, ' to ', new_status) AS details
                      FROM status_audit";
            if ($dateCondition) $query .= " WHERE $dateCondition";
            $sqlParts[] = $query;
        }

        // Assigned Personnel
        if (in_array('assigned_personnel', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'assigned_personnel')) {
            $query = "SELECT 
                        action_date AS timestamp,
                        'Assigned Personnel' AS source,
                        action_type,
                        CONCAT('Request ID: ', request_id) AS affected_item,
                        description AS details
                      FROM request_assigned_personnel_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action_type = '$actionFilter'";
            if ($dateCondition) $conditions[] = $dateCondition;
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // Administrator
        if (in_array('administrator', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'administrator')) {
            $query = "SELECT 
                        changed_at AS timestamp,
                        'Administrator' AS source,
                        action AS action_type,
                        staff_name AS affected_item,
                        description AS details
                      FROM administrator_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action = '$actionFilter'";
            if ($dateCondition) $conditions[] = str_replace('action_date', 'changed_at', $dateCondition);
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // Campus Locations
        if (in_array('campus_locations', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'campus_locations')) {
            $query = "SELECT 
                        changed_at AS timestamp,
                        'Campus Location' AS source,
                        action AS action_type,
                        staff_name AS affected_item,
                        description AS details
                      FROM campus_locations_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action = '$actionFilter'";
            if ($dateCondition) $conditions[] = str_replace('action_date', 'changed_at', $dateCondition);
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // Driver Logs
        if (in_array('driver', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'driver')) {
            $query = "SELECT 
                        changed_at AS timestamp,
                        'Driver' AS source,
                        action AS action_type,
                        staff_name AS affected_item,
                        description AS details
                      FROM driver_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action = '$actionFilter'";
            if ($dateCondition) $conditions[] = str_replace('action_date', 'changed_at', $dateCondition);
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // Vehicle Logs
        if (in_array('vehicle', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'vehicle')) {
            $query = "SELECT 
                        changed_at AS timestamp,
                        'Vehicle' AS source,
                        action AS action_type,
                        staff_name AS affected_item,
                        description AS details
                      FROM vehicle_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action = '$actionFilter'";
            if ($dateCondition) $conditions[] = str_replace('action_date', 'changed_at', $dateCondition);
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // Vehicle Request Logs
        if (in_array('vehicle_request', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'vehicle_request')) {
            $query = "SELECT 
                        changed_at AS timestamp,
                        'Vehicle Request' AS source,
                        action AS action_type,
                        requester_name AS affected_item,
                        description AS details
                      FROM vehicle_request_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action = '$actionFilter'";
            if ($dateCondition) $conditions[] = str_replace('action_date', 'changed_at', $dateCondition);
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // Vehicle Request Assignment
        if (in_array('vehicle_request_assignment', $allowedTables) && ($tableFilter === 'all' || $tableFilter === 'vehicle_request_assignment')) {
            $query = "SELECT 
                        changed_at AS timestamp,
                        'Vehicle Request Assignment' AS source,
                        action AS action_type,
                        staff_name AS affected_item,
                        description AS details
                      FROM vehicle_request_assignment_audit";
            $conditions = [];
            if ($actionFilter !== 'all') $conditions[] = "action = '$actionFilter'";
            if ($dateCondition) $conditions[] = str_replace('action_date', 'changed_at', $dateCondition);
            $addConditions($query, $conditions);
            $sqlParts[] = $query;
        }

        // 🔹 STEP 7: Combine and execute all allowed queries
        if (empty($sqlParts)) return [];

        $finalSql = implode(" UNION ALL ", $sqlParts);
        $finalSql .= " ORDER BY timestamp DESC LIMIT $limit";

        $result = $this->db->query($finalSql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // 🔹 Helper for date filter
    private function buildDateCondition($dateFilter) {
        switch ($dateFilter) {
            case 'today': return "DATE(action_date) = CURDATE()";
            case 'yesterday': return "DATE(action_date) = CURDATE() - INTERVAL 1 DAY";
            case '7': return "DATE(action_date) >= CURDATE() - INTERVAL 7 DAY";
            case '14': return "DATE(action_date) >= CURDATE() - INTERVAL 14 DAY";
            case '30': return "DATE(action_date) >= CURDATE() - INTERVAL 30 DAY";
            default: return "";
        }
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
}
