<?php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/encryption.php';

class ActivityLogsModel extends BaseModel {

   public function getLogs($sourceFilter = 'all', $actionFilter = 'all', $dateFilter = 'all', $limit = 100)
{
    // 🔹 STEP 1: Detect access level
    $role = 'gsu'; // default
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

    // 🔹 STEP 2: Role mapping
    switch ($accessLevelId) {
        case 1: $role = 'super'; break;
        case 2: $role = 'gsu'; break;
        case 3: $role = 'motorpool'; break;
        default: $role = 'gsu';
    }

    // 🔹 STEP 3: Define what each role can access
    $accessRules = [
        'super' => [
            'gsu personnel', 'materials', 'request', 'request status',
            'assigned personnel', 'administrator', 'campus location',
            'driver', 'vehicle', 'vehicle request', 'vehicle request assignment', 'requester'
        ],

        'gsu' => [
            'gsu personnel', 'materials', 'request', 'request status',
            'assigned personnel', 'campus location'
        ],

        'motorpool' => [
            'driver', 'vehicle', 'vehicle request', 'vehicle request assignment'
        ]
    ];

    // Allowed modules for logged-in user
    $allowedSources = $accessRules[$role] ?? [];

    // 🔹 STEP 4: Build query
    $query = "
        SELECT 
            changed_at AS timestamp,
            source,
            action AS action_type,
            name AS affected_item,
            description AS details
        FROM activity_logs
    ";

    $conditions = [];

    // ❗ Role limitation: user only sees allowed modules
    if (!empty($allowedSources)) {
        $escaped = array_map([$this->db, 'real_escape_string'], $allowedSources);
        $conditions[] = "source IN ('" . implode("','", $escaped) . "')";
    }

    // Filter by source
    if ($sourceFilter !== 'all') {
        $conditions[] = "source = '" . $this->db->real_escape_string($sourceFilter) . "'";
    }

    // Filter by action
    if ($actionFilter !== 'all') {
        $conditions[] = "action = '" . $this->db->real_escape_string($actionFilter) . "'";
    }

    // Filter by date
    $dateCondition = $this->buildDateCondition($dateFilter, 'changed_at');
    if ($dateCondition) {
        $conditions[] = $dateCondition;
    }

    // Apply conditions
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY timestamp DESC LIMIT $limit";

    // 🔹 Execute
    $result = $this->db->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


    // 🔹 Helper for date filter
    private function buildDateCondition($dateFilter, $column = 'changed_at') {
        switch ($dateFilter) {
            case 'today': return "DATE($column) = CURDATE()";
            case 'yesterday': return "DATE($column) = CURDATE() - INTERVAL 1 DAY";
            case '7': return "$column >= NOW() - INTERVAL 7 DAY";
            case '14': return "$column >= NOW() - INTERVAL 14 DAY";
            case '30': return "$column >= NOW() - INTERVAL 30 DAY";
            default: return "";
        }
    }

    // Get profile data by email
    public function getProfileByEmail($admin_email) {

    $encrypted_email = encrypt($admin_email);

    $stmt = $this->db->prepare("
        SELECT profile_picture
        FROM administrator
        WHERE email = ?
    ");

    $stmt->bind_param("s", $encrypted_email);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

}
