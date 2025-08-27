<?php
session_start();
$conn = new mysqli("localhost", "root", "", "utrms_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter values
$tableFilter = isset($_GET['table']) ? $_GET['table'] : 'all';
$actionFilter = isset($_GET['action']) ? $_GET['action'] : 'all';

// Base query
$sql = "";

if ($tableFilter == 'all' || $tableFilter == 'gsu_personnel') {
    $sql .= "SELECT 
        action_date as timestamp,
        'GSU Personnel' as source,
        action_type,
        CONCAT(firstName, ' ', lastName) as affected_item,
        department as details
    FROM gsu_personnel_audit";
    
    if ($actionFilter != 'all') {
        $sql .= " WHERE action_type = '" . mysqli_real_escape_string($conn, $actionFilter) . "'";
    }
}

if ($tableFilter == 'all' || $tableFilter == 'materials') {
    if ($sql != "") {
        $sql .= " UNION ALL ";
    }
    $sql .= "SELECT 
        action_date as timestamp,
        'Materials' as source,
        action_type,
        material_desc as affected_item,
        CONCAT('Quantity: ', qty) as details
    FROM materials_audit";
    
    if ($actionFilter != 'all') {
        $sql .= " WHERE action_type = '" . mysqli_real_escape_string($conn, $actionFilter) . "'";
    }
}

$sql .= " ORDER BY timestamp DESC LIMIT 100";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $actionClass = "action-" . strtolower($row['action_type']);
        echo "<tr>
                <td>" . date('Y-m-d H:i:s', strtotime($row['timestamp'])) . "</td>
                <td>" . htmlspecialchars($row['source']) . "</td>
                <td class='{$actionClass}'>" . htmlspecialchars($row['action_type']) . "</td>
                <td>" . htmlspecialchars($row['affected_item']) . "</td>
                <td>" . htmlspecialchars($row['details']) . "</td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='5' class='no-logs'>No logs found.</td></tr>";
}

$conn->close();
?> 