<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "utrms_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sortOption = $_GET['sort'] ?? 'id';
$orderBy = "material_code ASC";
$searchTerm = isset($_GET['searchTerm']) ? $conn->real_escape_string($_GET['searchTerm']) : '';
$filterOption = isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : '';

// Set the ORDER BY clause based on sort option
if ($sortOption === 'az') {
    $orderBy = "material_desc ASC";
} else if ($sortOption === 'za') {
    $orderBy = "material_desc DESC";
}

// Build the WHERE clause
$conditions = ["1=1"]; // Always true condition as a base

// Add search condition if search term exists
if (!empty($searchTerm)) {
    $conditions[] = "CONCAT(material_code, material_desc) LIKE '%$searchTerm%'";
}

// Add filter condition
if ($filterOption === 'available') {
    $conditions[] = "qty > 0";
} else if ($filterOption === 'oos') {
    $conditions[] = "qty = 0";
}

// Combine all conditions
$whereClause = "WHERE " . implode(" AND ", $conditions);

// Final query
$sql = "SELECT * FROM vw_materials $whereClause ORDER BY $orderBy";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Output only table rows (no header)
while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['material_code']) . '</td>';
    echo '<td>' . htmlspecialchars($row['material_desc']) . '</td>';
    echo '<td>' . htmlspecialchars($row['qty']) . '</td>';
    $status = $row['qty'] > 0 ? 'Available' : 'Out of Stock';
    echo '<td>' . $status . '</td>';
    echo '<td class="no-print">
            <button style="padding:0.3vw; background-color:transparent;" onclick="openEditModal(\'' . $row['material_code'] . '\', ' . $row['qty'] . ')"><img src="../../assets/icon/add.png" style="width:23px"></button>
            <form method="POST" action="" style="display:inline;">
                <input type="hidden" name="delete_code" value="' . $row['material_code'] . '">
                <button style="padding:0.3vw; background-color:transparent;" type="button" onclick="confirmDelete(\'' . $row['material_code'] . '\')"><img src="../../assets/icon/delete.png" style="width:23px" "></button>
            </form>
        </td>';
    echo '</tr>';
}

$conn->close();
?> 