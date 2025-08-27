<?php include 'auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>GSU System</title>
    <link rel="icon" href="../../assets/icon/logo.png" type="icon">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-menu.css">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-global.css">
    <link rel="stylesheet" type="text/css" href="../../css/GSUAdmin/inventory.css">
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    <div id="admin-menu"></div>
    <script src="../../js/admin-menu.js"></script>

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

// For debugging
error_log("SQL Query: " . $sql);
error_log("Filter Option: " . $filterOption);
error_log("Sort Option: " . $sortOption);

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

?>


    <div class="main">
        <p class="type">Inventory</p>
        <div class="gsuInventory">
            <div class="toolbar">
                <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;" id="searchForm">
                    <input type="search" placeholder="Search Materials" name="searchTerm" id="invsearch" value="<?php echo htmlspecialchars($searchTerm); ?>" oninput="handleRealTimeSearch()">
                    <select class="filter" name="filter" id="filterSelect" onchange="handleRealTimeSearch()">
                        <option value="" <?php echo empty($filterOption) ? 'selected' : ''; ?>>All</option>
                        <option value="available" <?php echo $filterOption === 'available' ? 'selected' : ''; ?>>Available</option>
                        <option value="oos" <?php echo $filterOption === 'oos' ? 'selected' : ''; ?>>Out of Stock</option>
                    </select>
                    <select class="sorting" name="sort" id="sortSelect" onchange="handleRealTimeSearch()">
                        <option value="id" <?php echo empty($sortOption) || $sortOption === 'id' ? 'selected' : ''; ?>>Sort by ID</option>
                        <option value="az" <?php echo $sortOption === 'az' ? 'selected' : ''; ?>>Sort A-Z</option>
                        <option value="za" <?php echo $sortOption === 'za' ? 'selected' : ''; ?>>Sort Z-A</option>
                    </select>
                </form>
                <div style="display: flex; gap: 10px;">
                    <button onclick="printSection('tableinv')" class="print"><img src="../../assets/icon/printing.png">&nbsp;Print</button>
                    <button class="addmaterials" onclick="openMaterialModal()"><img src="../../assets/icon/add.png">&nbsp;Add Materials</button>
                </div>
            </div>

            <div class="tableinv" id="tableinv">
                <table class="inv">
                    <thead>
                        <tr>
                            <td><b>Code</b></td>
                            <td><b>Description</b></td>
                            <td><b>Quantity</b></td>
                            <td><b>Status</b></td>
                            <td class="no-print"><b>Action</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
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
                        ?> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Material Modal -->
<div id="materialModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h2>Add Material</h2>
        <form method="POST" action="">
            <label for="material_code">Material Code:</label><br>
            <input type="text" id="material_code" name="material_code" required><br>

            <label for="description">Description:</label><br>
            <input type="text" id="description" name="description" required><br>

            <label for="quantity">Quantity:</label><br>
            <input type="number" id="quantity" name="quantity" min="1" required><br>

            <div class="button-group">
                <button type="submit" name="addMaterial">Confirm</button>
                <button type="button" class="cancel-btn" onclick="closeMaterialModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Quantity Modal -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h2>Add Quantity</h2>
        <br>
        <form method="POST" action="">
            <input type="hidden" id="edit_code" name="edit_code">
            <input type="number" id="new_qty" name="new_qty" min="0" required><br>
            <div class="button-group">
                <button type="submit" name="updateqty">Add</button>
                <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>


    <script>
        let searchTimeout;

        function handleRealTimeSearch() {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                const searchTerm = document.getElementById('invsearch').value;
                const filterValue = document.getElementById('filterSelect').value;
                const sortValue = document.getElementById('sortSelect').value;
                
                const url = `get-inventory.php?searchTerm=${encodeURIComponent(searchTerm)}&filter=${encodeURIComponent(filterValue)}&sort=${encodeURIComponent(sortValue)}`;
                
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector('.inv tbody').innerHTML = html;
                    })
                    .catch(error => console.error('Error:', error));
            }, 300);
        }

        function confirmDelete(code) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_code';
                    input.value = code;
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function openMaterialModal() {
            document.getElementById("materialModal").style.display = "block";
        }

        function closeMaterialModal() {
            document.getElementById("materialModal").style.display = "none";
        }

        function openEditModal(code, qty) {
            document.getElementById("edit_code").value = code;
            document.getElementById("new_qty").value = qty;
            document.getElementById("editModal").style.display = "block";
        }

        function closeEditModal() {
            document.getElementById("editModal").style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById("materialModal")) closeMaterialModal();
            if (event.target == document.getElementById("editModal")) closeEditModal();
        }
        function printSection(sectionId) {
            const headerHtml = `
                <div style="text-align:center;">
                    <img src="../../assets/icon/usep.png" style="height:80px;"><br>
                    <h2 class="print-header">University of Southeastern Philippines</h2>
                    <h3>General Services Unit - Inventory Report</h3>
                    <p>Printed on ${new Date().toLocaleString()}</p>
                </div>
            `;

            const footerHtml = `
                <div>
                    <img style="position: fixed; bottom: 0; width: 100%; text-align: center;" src="../../assets/icon/footer.png" alt="Footer Logo" style="max-height: 80px; width: auto;">
                </div>
            `;

            const style = `
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 10px;
                        margin-bottom: 90px;
                    }
                    th, td {
                        border: 1px solid #000;
                        padding: 6px;
                        text-align: left;
                        font-size: 12px;
                    }
                    h3 {
                        font-size: 16px;
                        margin: 5px 0;
                    }
                    .print-header {
                        font-family: 'Old English Text MT', cursive;
                        font-size: 24px;
                        margin: 5px 0;
                    }
                    p {
                        margin: 5px 0;
                        font-size: 12px;
                    }
                    .print-page {
                        padding: 20px;
                        position: relative;
                    }
                    @media print {
                        .print-page {
                            page-break-after: always;
                        }
                        .print-page:last-child {
                            page-break-after: avoid;
                        }
                        thead {
                            display: table-header-group;
                        }
                        @page {
                            margin: 0.5cm;
                            size: portrait;
                        }
                    }
                </style>
            `;

            const originalTable = document.querySelector(".inv");
            const tableHead = originalTable.querySelector("tr");
            const tableRows = Array.from(originalTable.querySelectorAll("tr")).slice(1);

            // Remove the "Action" column from the header
            const headerRow = tableHead.cloneNode(true);
            const headerCells = Array.from(headerRow.querySelectorAll("td"));
            if (headerCells.length > 0) {
                headerCells[headerCells.length - 1].remove(); // Remove last column (Actions)
            }

            // Clone rows and remove "Action" column from each row
            const cleanedRows = tableRows.map(row => {
                const clone = row.cloneNode(true);
                const cells = clone.querySelectorAll("td");
                if (cells.length > 0) {
                    cells[cells.length - 1].remove(); // Remove last column (Actions)
                }
                return clone;
            });

            const printWindow = window.open('', '_blank', 'width=900,height=700');
            printWindow.document.open();
            printWindow.document.write('<html><head>' + style + '</head><body>');

            // Adjusted to 15 items per page based on available space
            const itemsPerPage = 15;
            const pages = Math.ceil(cleanedRows.length / itemsPerPage);

            for (let i = 0; i < cleanedRows.length; i += itemsPerPage) {
                const chunk = cleanedRows.slice(i, i + itemsPerPage);
                const isLastPage = Math.floor(i / itemsPerPage) === pages - 1;

                const tableHtml = `
                    <table>
                        ${headerRow.outerHTML}
                        <tbody>
                            ${chunk.map(r => r.outerHTML).join('')}
                        </tbody>
                    </table>
                `;

                const pageHtml = `
                    <div class="print-page">
                        ${headerHtml}
                        ${tableHtml}
                        ${isLastPage ? footerHtml : ''}
                    </div>
                `;

                printWindow.document.write(pageHtml);
            }

            printWindow.document.write('</body></html>');
            printWindow.document.close();

            printWindow.onload = function () {
                setTimeout(() => {
                    printWindow.focus();
                    printWindow.print();
                }, 500);
            };
        }
    </script>
</body>
</html>

<?php

$conn = mysqli_connect("localhost","root","","utrms_db");
// Handle delete material
if (isset($_POST['delete_code'])) {
    $deleteCode = $_POST['delete_code'];
    $stmt = $conn->prepare("DELETE FROM vw_materials WHERE material_code = ?");
    $stmt->bind_param("s", $deleteCode);
    if ($stmt->execute()) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Deleted',
            text: 'Material deleted successfully!',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href = window.location.href;
        });
        </script>";
    } else {
        echo "<script>alert('Error deleting material.');</script>";
    }
    $stmt->close();
}
?>

<?php
$conn = mysqli_connect("localhost","root","","utrms_db");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateqty'], $_POST['new_qty'])) {
    $conn = new mysqli("localhost", "root", "", "gsu_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $editCode = $conn->real_escape_string($_POST['edit_code']);
    $newQty = intval($_POST['new_qty']);

    if ($newQty >= 0) {
        $stmt = $conn->prepare("UPDATE vw_materials SET qty = qty + ? WHERE material_code = ? AND (qty + ?) >= 0");
        $stmt->bind_param("isi", $newQty, $editCode, $newQty);
        if ($stmt->execute()) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Quantity Added',
                    text: 'The material quantity has been successfully added.',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    window.location.href = window.location.href;
                });
            </script>";
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'An error occurred while updating the quantity.',
                    confirmButtonColor: '#d33'
                });
            </script>";
        }
        $stmt->close();
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Quantity',
                text: 'Quantity must be zero or more.',
                confirmButtonColor: '#f27474'
            });
        </script>";
    }

    $conn->close();
}
?>

<?php
// Handle material form submission
if (isset($_POST['addMaterial'])) {
    $conn = new mysqli("localhost", "root", "", "utrms_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['material_code']) && isset($_POST['description']) && isset($_POST['quantity'])) {
        $materialCode = trim($_POST['material_code']);
        $description = trim($_POST['description']);
        $quantity = intval($_POST['quantity']);

        // Validate input
        if (!ctype_digit($materialCode)) {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid Code',
                text: 'Material code must be an integer.',
                confirmButtonColor: '#d33'
            });
            </script>";
        } else if (!empty($materialCode) && !empty($description) && $quantity > 0) {

            // Check if material already exists
            $checkStmt = $conn->prepare("SELECT * FROM vw_materials WHERE material_code = ? OR material_desc = ?");
            $checkStmt->bind_param("ss", $materialCode, $description);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Entry',
                    text: 'Material code or description already exists.',
                    confirmButtonColor: '#d33'
                });
                </script>";
            } else {
                // Insert new material
                $stmt = $conn->prepare("INSERT INTO vw_materials (material_code, material_desc, qty) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $materialCode, $description, $quantity);
                if ($stmt->execute()) {
                    echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Material added successfully!',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        window.location.href = window.location.href;
                    });
                    </script>";
                }
                $stmt->close();
            }

            $checkStmt->close();
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'All fields are required and quantity must be greater than 0.',
                confirmButtonColor: '#f27474'
            });
            </script>";
        }
    }

    $conn->close();
}
?>
