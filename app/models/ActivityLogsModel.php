<?php
// filepath: app/models/ActivityLogsModel.php

require_once __DIR__ . '/../core/BaseModel.php';

class ActivityLogsModel extends BaseModel {

    // filepath: app/models/ActivityLogsModel.php

public function getLogs($tableFilter = 'all', $actionFilter = 'all', $dateFilter = 'all', $limit = 100) {
    $sqlParts = [];

    // function to generate date condition
    $dateCondition = $this->buildDateCondition($dateFilter);

    // ✅ GSU Personnel
    if ($tableFilter === 'all' || $tableFilter === 'gsu_personnel') {
        $query = "SELECT 
                    action_date as timestamp,
                    'GSU Personnel' as source,
                    action_type,
                    CONCAT(firstName, ' ', lastName) as affected_item,
                    department as details
                  FROM gsu_personnel_audit";
        $conditions = [];
        if ($actionFilter !== 'all') {
            $conditions[] = "action_type = '$actionFilter'";
        }
        if ($dateCondition) {
            $conditions[] = $dateCondition;
        }
        if ($conditions) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $sqlParts[] = $query;
    }

    // ✅ Materials
    if ($tableFilter === 'all' || $tableFilter === 'materials') {
        $query = "SELECT 
                    action_date as timestamp,
                    'Materials' as source,
                    action_type,
                    material_desc as affected_item,
                    CONCAT('Quantity: ', qty) as details
                  FROM materials_audit";
        $conditions = [];
        if ($actionFilter !== 'all') {
            $conditions[] = "action_type = '$actionFilter'";
        }
        if ($dateCondition) {
            $conditions[] = $dateCondition;
        }
        if ($conditions) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $sqlParts[] = $query;
    }

    // ✅ Requests
    if ($tableFilter === 'all' || $tableFilter === 'request') {
        $query = "SELECT 
                    action_date as timestamp,
                    'Request' as source,
                    action_type,
                    request_type as affected_item,
                    description as details
                  FROM request_audit";
        $conditions = [];
        if ($actionFilter !== 'all') {
            $conditions[] = "action_type = '$actionFilter'";
        }
        if ($dateCondition) {
            $conditions[] = $dateCondition;
        }
        if ($conditions) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $sqlParts[] = $query;
    }

    // ✅ Request Status
    if ($tableFilter === 'all' || $tableFilter === 'status') {
        $query = "SELECT 
                    action_date as timestamp,
                    'Request Status' as source,
                    'UPDATE' as action_type,
                    CONCAT('Request ID: ', request_id) as affected_item,
                    CONCAT('From ', old_status, ' to ', new_status) as details
                  FROM status_audit";
        if ($dateCondition) {
            $query .= " WHERE $dateCondition";
        }
        $sqlParts[] = $query;
    }

    // ✅ Assigned Personnel
    if ($tableFilter === 'all' || $tableFilter === 'assigned_personnel') {
        $query = "SELECT 
                    action_date as timestamp,
                    'Assigned Personnel' as source,
                    action_type,
                    CONCAT('Request ID: ', request_id) as affected_item,
                    description as details
                  FROM request_assigned_personnel_audit";
        $conditions = [];
        if ($actionFilter !== 'all') {
            $conditions[] = "action_type = '$actionFilter'";
        }
        if ($dateCondition) {
            $conditions[] = $dateCondition;
        }
        if ($conditions) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $sqlParts[] = $query;
    }

    if (empty($sqlParts)) return [];

    $finalSql = implode(" UNION ALL ", $sqlParts);
    $finalSql .= " ORDER BY timestamp DESC LIMIT $limit";

    $result = $this->db->query($finalSql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// helper for date ranges
private function buildDateCondition($dateFilter) {
    switch ($dateFilter) {
        case 'today':
            return "DATE(action_date) = CURDATE()";
        case 'yesterday':
            return "DATE(action_date) = CURDATE() - INTERVAL 1 DAY";
        case '7':
            return "DATE(action_date) >= CURDATE() - INTERVAL 7 DAY";
        case '14':
            return "DATE(action_date) >= CURDATE() - INTERVAL 14 DAY";
        case '30':
            return "DATE(action_date) >= CURDATE() - INTERVAL 30 DAY";
        default:
            return "";
    }
}

}
