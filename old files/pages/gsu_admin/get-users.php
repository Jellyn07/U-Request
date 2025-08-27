<?php
$conn = new mysqli("localhost", "root", "", "utrms_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sortOption = $_GET['sort'] ?? 'id';
$orderBy = "requester_id ASC";

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filterOption = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';

// Build the base query
$sql = "SELECT DISTINCT * FROM vw_userAccount WHERE 1=1";

// Add search condition if search term exists
if (!empty($searchTerm)) {
    $sql .= " AND (
        requester_id LIKE '%$searchTerm%' OR 
        full_name LIKE '%$searchTerm%' OR 
        email LIKE '%$searchTerm%' OR 
        officeOrDept LIKE '%$searchTerm%' OR 
        status LIKE '%$searchTerm%'
    )";
}

// Add filter condition
if ($filterOption === 'have_pending') {
    $sql .= " AND status = 'Pending Request'";
} elseif ($filterOption === 'no_pending') {
    $sql .= " AND status = 'No Pending Request'";
} elseif ($filterOption === 'student') {
    $sql .= " AND officeOrDept LIKE '%Student%'";
} elseif ($filterOption === 'staff') {
    $sql .= " AND officeOrDept NOT LIKE '%Student%'";
}

// Add sorting
if ($sortOption === 'az') {
    $sql .= " ORDER BY full_name ASC";
} elseif ($sortOption === 'za') {
    $sql .= " ORDER BY full_name DESC";
} else {
    $sql .= " ORDER BY requester_id ASC";
}

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Generate table rows
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['requester_id']) . '</td>';
    echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
    echo '<td>' . (empty($row['officeOrDept']) ? '<span style="color: #ED2939;">Undefined</span>' : htmlspecialchars($row['officeOrDept'])) . '</td>';
    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
    echo '<td class="no-print">
            <button style="padding:0.3vw; background-color:transparent;" class="view-btn" 
                    data-id="' . $row['requester_id'] . '" 
                    data-name="' . htmlspecialchars($row['full_name']) . '" 
                    data-email="' . htmlspecialchars($row['email']) . '" 
                    data-officeordep="' . (empty($row['officeOrDept']) ? 'Undefined' : htmlspecialchars($row['officeOrDept'])) . '" 
                    data-status="' . htmlspecialchars($row['status']) . '">
                <img src="../../assets/icon/more.png" alt="More" style="width:23px; background:white;">
            </button>
          </td>';
    echo '</tr>';
}

$conn->close();
?> 