<?php include 'auth-check.php'; ?>
<?php
// session_start();
$conn = new mysqli("localhost", "root", "", "utrms_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSU System</title>
    <link rel="icon" href="../../assets/icon/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/shared/admin-menu.css">
    <link rel="stylesheet" href="../../css/shared/admin-global.css">
    <link rel="stylesheet" href="../../css/GSUAdmin/activity_logs.css">
</head>
<body>
    <div id="admin-menu"></div>
    <script src="../../js/admin-menu.js"></script>

    <div class="main">
        <p class="type">Activity Logs</p>
        
        <div class="toolbar">
            <select id="tableFilter" class="filter-select" onchange="filterLogs()">
                <option value="all">All Tables</option>
                <option value="gsu_personnel">GSU Personnel</option>
                <option value="materials">Materials</option>
                <!-- <option value="request">Request</option>
                <option value="status">Request Status</option> -->
            </select>

            <select id="actionFilter" class="filter-select" onchange="filterLogs()">
                <option value="all">All Actions</option>
                <option value="INSERT">Added</option>
                <option value="UPDATE">Modified</option>
                <option value="DELETE">Removed</option>
            </select>
        </div>

        <div class="logs-container">
            <table class="logs-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Source</th>
                        <th>Action</th>
                        <th>Item</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody id="logsTableBody">
                    <?php 
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

                    if ($tableFilter == 'all' || $tableFilter == 'request') {
                        if ($sql != "") {
                            $sql .= " UNION ALL ";
                        }
                        $sql .= "SELECT 
                            action_date as timestamp,
                            'Request' as source,
                            action_type,
                            request_type as affected_item,
                            description as details
                        FROM request_audit";
                    
                        if ($actionFilter != 'all') {
                            $sql .= " WHERE action_type = '" . mysqli_real_escape_string($conn, $actionFilter) . "'";
                        }
                    }      
                    
                    if ($tableFilter == 'all' || $tableFilter == 'status') {
                        if ($sql != "") {
                            $sql .= " UNION ALL ";
                        }
                        $sql .= "SELECT 
                            action_date as timestamp,
                            'Request Status' as source,
                            'UPDATE' as action_type,
                            CONCAT('Request ID: ', request_id) as affected_item,
                            CONCAT('From ', old_status, ' to ', new_status) as details
                        FROM status_audit";
                    
                        if ($actionFilter != 'all' && $actionFilter == 'UPDATE') {
                            $sql .= " WHERE 1"; // Optional: all records here are UPDATEs
                        }
                    }
                    if ($tableFilter == 'all' || $tableFilter == 'assigned_personnel') {
                        if ($sql != "") {
                            $sql .= " UNION ALL ";
                        }
                        $personnelSql = "SELECT 
                            action_date as timestamp,
                            'Assigned Personnel' as source,
                            action_type,
                            CONCAT('Request ID: ', request_id) as affected_item,
                            description as details
                        FROM request_assigned_personnel_audit";
                    
                        if ($actionFilter != 'all') {
                            $actionFilterEscaped = mysqli_real_escape_string($conn, $actionFilter);
                            $personnelSql .= " WHERE action_type = '{$actionFilterEscaped}'";
                        }
                    
                        $sql .= $personnelSql;
                    }

                    $sql .= " ORDER BY timestamp DESC";
                    
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
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function filterLogs() {
            const tableFilter = document.getElementById('tableFilter').value;
            const actionFilter = document.getElementById('actionFilter').value;
            const currentUrl = new URL(window.location.href);

            // Update URL parameters
            currentUrl.searchParams.set('table', tableFilter);
            currentUrl.searchParams.set('action', actionFilter);

            // Update browser history without reloading
            window.history.pushState({}, '', currentUrl);

            // Fetch filtered data
            fetch(`get-filtered-logs.php?table=${tableFilter}&action=${actionFilter}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('logsTableBody').innerHTML = data || 
                        '<tr><td colspan="5" class="no-logs">No logs found.</td></tr>';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('logsTableBody').innerHTML = 
                        '<tr><td colspan="5" class="no-logs">Error loading logs.</td></tr>';
                });
        }

        // Set initial filter values from URL parameters
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tableFilter = urlParams.get('table');
            const actionFilter = urlParams.get('action');

            if (tableFilter) {
                document.getElementById('tableFilter').value = tableFilter;
            }
            if (actionFilter) {
                document.getElementById('actionFilter').value = actionFilter;
            }
        });

        // Auto-refresh logs every minute
        setInterval(filterLogs, 60000);
    </script>
</body>
</html> 