<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "utrms_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search parameters
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filterStatus = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';
$sortOption = $_GET['sort'] ?? 'id';

// Base SQL query with status subquery
$sql = "WITH personnel_status AS (
    SELECT p.*, 
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM request_assigned_personnel rap 
                INNER JOIN request_assignment ra ON rap.request_id = ra.request_id 
                WHERE rap.staff_id = p.staff_id 
                AND ra.req_status = 'In Progress'
            ) THEN 'Fixing'
            ELSE 'Available'
        END as status
    FROM vw_gsu_personnel p
)
SELECT * FROM personnel_status WHERE 1=1";

// Add search condition if search term exists
if (!empty($searchTerm)) {
    $sql .= " AND CONCAT(staff_id, full_name, department, contact, hire_date, unit) LIKE '%$searchTerm%'";
}

// Add filter condition if filter is selected
if (!empty($filterStatus)) {
    $sql .= " AND status = '$filterStatus'";
}

// Add ordering
if ($sortOption === 'az') {
    $sql .= " ORDER BY full_name ASC";
} elseif ($sortOption === 'za') {
    $sql .= " ORDER BY full_name DESC";
} else {
    $sql .= " ORDER BY staff_id ASC";
}

$result = mysqli_query($conn, $sql);

// Generate table rows
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['staff_id']) . '</td>';
    echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
    echo '<td>' . htmlspecialchars($row['department']) . '</td>';
    echo '<td>' . htmlspecialchars($row['contact']) . '</td>';
    echo '<td>' . htmlspecialchars($row['hire_date']) . '</td>';
    echo '<td>' . htmlspecialchars($row['unit']) . '</td>';
    echo '<td>' . htmlspecialchars($row['status'] ?? 'Available') . '</td>';
    echo '<td>
            <button class="view-btn" 
                data-staffid="' . $row['staff_id'] . '" 
                data-name="' . htmlspecialchars($row['full_name']) . '" 
                data-dept="' . htmlspecialchars($row['department']) . '" 
                data-unit="' . htmlspecialchars($row['unit']) . '" 
                data-contact="' . htmlspecialchars($row['contact']) . '" 
                data-hiredate="' . htmlspecialchars($row['hire_date']) . '"
                data-status="' . htmlspecialchars($row['status']) . '">
                <img src="../../assets/icon/more.png" alt="More" width="26" style="background:white;">
            </button>
          </td>';
    echo '</tr>';
}

$conn->close();
?> 