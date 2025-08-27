<?php include 'auth-check.php'; ?>
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>GSU System</title>
    <link rel="icon" href="../../assets/icon/logo.png" type="icon">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-menu.css">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-global.css">
    <link rel="stylesheet" type="text/css" href="../../css/GSUAdmin/personnel.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div id="admin-menu"></div>
    <script src="../../js/admin-menu.js"></script>

    <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "utrms_db");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sortOption = $_GET['sort'] ?? 'id';
        
        // Prepare search query if search term is provided
        $searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
        $filterStatus = isset($_GET['filter']) ? mysqli_real_escape_string($conn, $_GET['filter']) : '';

        // Debug output
        echo "<!-- Filter Status: " . $filterStatus . " -->";

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

        // Debug the sort option
        error_log("Sort Option: " . $sortOption);

        // Add ordering with explicit column names
        if ($sortOption === 'az') {
            $sql .= " ORDER BY full_name ASC";
        } elseif ($sortOption === 'za') {
            $sql .= " ORDER BY full_name DESC";  // This should make Z-A work
        } else {
            $sql .= " ORDER BY staff_id ASC";
        }

        // Debug the final SQL query
        error_log("Final SQL Query: " . $sql);

        $result = mysqli_query($conn, $sql);
        
        // Check if query was successful
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Debug output
        echo "<!-- Number of rows: " . mysqli_num_rows($result) . " -->";
        // Start the HTML structure
        ?>


    <div class="main">
        <p class="type">GSU Personnel</p>

        <div class="toolbar">

            <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;" id="searchForm">
                <input type="search" placeholder="Search Personnel" name="search" id="gsusearch" value="<?php echo htmlspecialchars($searchTerm); ?>" oninput="handleRealTimeSearch()">
                <select class="filter" name="filter" id="filterSelect" onchange="handleRealTimeSearch()">
                    <option value="" <?php echo empty($_GET['filter']) ? 'selected' : ''; ?>>All</option>
                    <option value="Fixing" <?php echo ($_GET['filter'] ?? '') === 'Fixing' ? 'selected' : ''; ?>>Fixing</option>
                    <option value="Available" <?php echo ($_GET['filter'] ?? '') === 'Available' ? 'selected' : ''; ?>>Available</option>
                </select>
                <select class="sorting" name="sort" id="sortSelect" onchange="handleRealTimeSearch()">
                    <option value="id" <?php echo empty($_GET['sort']) || $_GET['sort'] === 'id' ? 'selected' : ''; ?>>Sort by ID</option>
                    <option value="az" <?php echo ($_GET['sort'] ?? '') === 'az' ? 'selected' : ''; ?>>Sort A-Z</option>
                    <option value="za" <?php echo ($_GET['sort'] ?? '') === 'za' ? 'selected' : ''; ?>>Sort Z-A</option>
                </select>
            </form>
            <div style="display: flex; gap: 10px;">
                <button onclick="printSection('gsupersonnel')" class="print"><img src="../../assets/icon/printing.png">&nbsp;Print</button>
                <button class="addpersonnel" id="addpersonnel"><img src="../../assets/icon/add.png">&nbsp;Add Personnel</button>
            </div>
        </div>

        <div class="gsupersonnel">
            <div id="tablegsu">
                <table>
                    <thead>
                    <tr>
                        <td><b>ID</b></td>
                        <td><b>Name</b></td>
                        <td><b>Department</b></td>
                        <td><b>Contact</b></td>
                        <td><b>Hire Date</b></td>
                        <td><b>Unit</b></td>
                        <td><b>Status</b></td>
                        <td><b>Actions</b></td>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
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
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Add Personnel Modal -->
    <div id="addPersonnelModal">
        <div class="modal-content">
            <h2>Add GSU Personnel</h2>
            <form method="POST">
                <label>Staff ID:</label>
                <input type="text" name="staff_id" required>
                
                <div id="namee">
                    <div class="full_name">
                        <label>First Name:</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="full_name">
                        <label>Last Name:</label>
                        <input type="text" name="last_name" required>
                    </div>
                </div>
                
                <label>Unit:</label>
                <select name="unit" id="unit" required>
                    <option value="" disabled selected>Select Unit</option>
                    <option value="Tagum Unit">Tagum Unit</option>
                    <option value="Mabini Unit">Mabini Unit</option>
                </select>

                <label>Department:</label>
                <select name="department" id="department" required>
                    <option value="" disabled selected>Select department</option>
                    <option value="Janitorial">Janitorial</option>
                    <option value="Utility">Utility</option>
                    <option value="Landscaping">Landscaping</option>
                    <option value="Ground Maintenance">Ground Maintenance</option>
                    <option value="Building Repair And Maintenance">Building Repair And Maintenance</option>
                </select>

                <div id="otherDepartmentField" style="display: none;">
                    <label>Please specify:</label>
                    <input type="text" name="other_department" id="other_department">
                </div>

                <label>Contact:</label>
                <input type="text" name="contact" required>

                <label>Hire Date:</label>
                <input type="date" name="hire_date" required max="<?php echo date('Y-m-d'); ?>">

                <input type="hidden" name="add" value="1">
                
                <div id="add_buttons">
                    <button type="submit" id="add">Add</button>
                    <button type="button" onclick="closeAddModal()" id="cancel">Cancel</button>  
                </div>
                
            </form>
        </div>
    </div>

    <!-- Personnel Detail Modal -->
<div id="personnelModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">

    <div style="background:white; width:30vw; margin:10vw auto; padding:3vw; border-radius:0.5vw;">
        <h2>Personnel Details</h2> <button onclick="closeModal()" id="moreExit"><img src="../../assets/icon/exit.png" alt="More" width="26"></button>
        <form id="deleteForm" method="POST">
        <input type="hidden" name="delete_personnel_id" id="deletePersonnelId"><input type="hidden" id="currentStaffId" value="">
        <p><strong>ID:</strong> <span id="modalStaffId"></span></p></form>
        <p><strong>Name:</strong> <span id="modalName"></span></p>
        <p><strong>Department:</strong> <span id="modalDept"></span></p>
        <p><strong>Unit:</strong> <span id="modalUnit"></span></p>
        <p><strong>Contact:</strong> <span id="modalContact"></span></p>
        <p><strong>Hire Date:</strong> <span id="modalHireDate"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>




        <!-- Example History -->
         <br>
        <h3>Work History</h3>
        <div id="modalHistory">
            <!-- <p>Loading history...</p> -->
        </div>

        <br>
        <div style="margin-top: 10px;" id="view_buttons">
                <button type="button" name="edit" id="edit" onclick="openEditModal()" >Edit</button>
                <!-- <button type="submit" name="save" style="display:none;" id="saveBtn">Save</button> -->
                <button type="button" name="deletePersonnel" id="modalDeleteBtn">Delete</button>
        </div>
    </div>
</div>

<!-- Edit Personnel Modal -->
<div id="editPersonnelModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:white; width:30vw; margin:3vw auto; padding:2vw; border-radius:0.5vw;">
        <h2>Edit GSU Personnel</h2> 
        <button onclick="closeEditModal()" id="editExitBtn">×</button>
        <form method="POST" action="">
            <input type="hidden" name="staff_id" id="editStaffId">
            <label>First Name:</label>
            <input type="text" name="first_name" id="editFirstName" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" id="editLastName" required>

            <label>Department:</label>
            <select name="department" id="editDepartment" required>
                <option value="Janitorial">Janitorial</option>
                <option value="Utility">Utility</option>
                <option value="Landscaping">Landscaping</option>
                <option value="Ground Maintenance">Ground Maintenance</option>
                <option value="Building Repair And Maintenance">Building Repair And Maintenance</option>
                <!-- <option value="Others">Others</option> -->
            </select>

            <div id="editOtherDepartmentField" style="display: none;">
                <label>Please specify:</label>
                <input type="text" name="other_department" id="editOtherDepartment">
            </div>

            <label>Unit:</label>
            <select name="unit" id="editUnit" required>
                <option value="Tagum Unit">Tagum Unit</option>
                <option value="Mabini Unit">Mabini Unit</option>
            </select>

            <label>Contact:</label>
            <input type="text" name="contact" id="editContact" required>

            <label>Hire Date:</label>
            <input type="date" name="hire_date" id="editHireDate" required max="<?php echo date('Y-m-d'); ?>">
            
            <div style="margin-top: 10px;" id="view_buttons">
                <button type="submit" name="save" value="Save Changes">Save</button>
                <button type="button" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
// Add this at the beginning of your script section
let searchTimeout;

function handleRealTimeSearch() {
    clearTimeout(searchTimeout);
    
    // Set a timeout to prevent too many requests while typing
    searchTimeout = setTimeout(() => {
        const searchTerm = document.getElementById('gsusearch').value;
        const filterValue = document.getElementById('filterSelect').value;
        const sortValue = document.getElementById('sortSelect').value;
        
        // Create URL with search parameters
        const url = `get-personnel.php?search=${encodeURIComponent(searchTerm)}&filter=${encodeURIComponent(filterValue)}&sort=${encodeURIComponent(sortValue)}`;
        
        // Fetch updated results
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.querySelector('#tablegsu tbody').innerHTML = html;
                // Reattach event listeners to new view buttons
                attachViewButtonListeners();
            })
            .catch(error => console.error('Error:', error));
    }, 300); // 300ms delay
}

function attachViewButtonListeners() {
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const staff = {
                staff_id: btn.dataset.staffid,
                full_name: btn.dataset.name,
                department: btn.dataset.dept,
                unit: btn.dataset.unit,
                contact: btn.dataset.contact,
                hire_date: btn.dataset.hiredate,
                status: btn.dataset.status
            };
            showPersonnelModal(staff);
        });
    });
}

// Consolidated JavaScript code
function openEditModal() {
    // Copy values from the personnel modal to the edit modal
    document.getElementById("editStaffId").value = document.getElementById("modalStaffId").innerText;
    document.getElementById("editFirstName").value = document.getElementById("modalName").innerText.split(' ')[0]; // Assuming first name is before space
    document.getElementById("editLastName").value = document.getElementById("modalName").innerText.split(' ')[1]; // Assuming last name is after space
    document.getElementById("editDepartment").value = document.getElementById("modalDept").innerText;
    document.getElementById("editUnit").value = document.getElementById("modalUnit").innerText;

    if (document.getElementById("modalDept").innerText === "Others") {
        document.getElementById("editOtherDepartmentField").style.display = "block";
        document.getElementById("editOtherDepartment").value = document.getElementById("modalDept").innerText;
    } else {
        document.getElementById("editOtherDepartmentField").style.display = "none";
    }

    document.getElementById("editContact").value = document.getElementById("modalContact").innerText;
    document.getElementById("editHireDate").value = document.getElementById("modalHireDate").innerText;

    // Show the edit modal
    document.getElementById("editPersonnelModal").style.display = "block";
    document.getElementById("personnelModal").style.display = "none";
}

function closeModal() {
    document.getElementById("personnelModal").style.display = "none";
}

function closeEditModal() {
    document.getElementById("editPersonnelModal").style.display = "none";
}

function closeAddModal() {
    document.getElementById("addPersonnelModal").style.display = "none";
}

function confirmDelete() {
    const staffId = document.getElementById("modalStaffId").textContent.trim();

    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ED2939',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deletePersonnelId').value = staffId;
            document.getElementById('deleteForm').submit();
        }
    });
}

function printSection() {
    const headerHtml = `
        <div style="text-align:center;">
            <img src="../../assets/icon/usep.png" style="height:90px;"><br>
            <h2 class="print-header">University of Southeastern Philippines</h2>
            <h3>General Services Unit - Personnel Report</h3>
            <p>Printed on ${new Date().toLocaleString()}</p>
        </div>
    `;

    const footerHtml = `
        <img src="../../assets/icon/footer.png" alt="Footer Logo" id="footer">
    `;

    const style = `
        <style>
            body {
                font-family: Arial, sans-serif; margin: 0; padding: 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            h3 {
                font-size: 16px;
                margin: 5px 0;
            }
            .print-header {
                font-family: 'Old English Text MT', cursive;
                font-size: 25px;
                margin-bottom: 10px;
            }
            .print-page {
                page-break-after: always;
                padding: 20px;
            }
            #footer {
                margin-top: 30px;
                width: 100%;
                text-align: center;
                position: fixed;
                bottom: 0;
            }
            @media print {
                .print-page {
                    page-break-after: always;
                }
                thead {
                    display: table-header-group;
                }
                .footer-printed {
                    display: none;
                }
            }
        </style>
    `;

    const originalTable = document.getElementById("tablegsu");
    const tableHead = originalTable.querySelector("thead");
    const tableRows = Array.from(originalTable.querySelectorAll("tbody tr"));

    // Remove the "Actions" column from the header (thead)
    const headerRow = tableHead.cloneNode(true);
    const headerCells = Array.from(headerRow.querySelectorAll("td"));
    if (headerCells.length > 0) {
        headerCells[headerCells.length - 1].remove(); // Remove last column (Actions) in header
    }

    // Clone rows and remove "Actions" column (last column) from each row in the body (tbody)
    const cleanedRows = tableRows.map(row => {
        const clone = row.cloneNode(true);
        const cells = clone.querySelectorAll("td");
        if (cells.length > 0) {
            cells[cells.length - 1].remove(); // Remove last column (Actions) in body
        }
        return clone;
    });

    const printWindow = window.open('', '_blank', 'width=900,height=700');
    printWindow.document.open();
    printWindow.document.write('<html><head>' + style + '</head><body>');

    let pageCount = 0;
    // Build each page with 10 rows
    for (let i = 0; i < cleanedRows.length; i += 10) {
        const chunk = cleanedRows.slice(i, i + 10);
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
                ${pageCount === Math.ceil(cleanedRows.length / 10) - 1 ? footerHtml : ''}
            </div>
        `;

        printWindow.document.write(pageHtml);
        pageCount++;
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

function showPersonnelModal(staff) {
    // Fill in static data
    document.getElementById('modalStaffId').innerText = staff.staff_id;
    document.getElementById('modalName').innerText = staff.full_name;
    document.getElementById('modalDept').innerText = staff.department;
    document.getElementById('modalUnit').innerText = staff.unit;
    document.getElementById('modalContact').innerText = staff.contact;
    document.getElementById('modalHireDate').innerText = staff.hire_date;
    document.getElementById('modalStatus').innerText = staff.status || 'Available';
    
    // Set the delete form's hidden input value
    document.getElementById('deletePersonnelId').value = staff.staff_id;
    
    document.getElementById('personnelModal').style.display = 'block';

    // Clear previous work history
    const historyContainer = document.getElementById('modalHistory');
    historyContainer.innerHTML = "<p>Loading history...</p>";

    const staffId = parseInt(staff.staff_id); // ensure it's an integer

// Load work history
fetch('get-work-history.php?staff_id=' + staffId)
    .then(response => response.json())
    .then(data => {
        const historyContainer = document.getElementById('modalHistory');
        if (data.length === 0) {
            historyContainer.innerHTML = "<p>No work history found.</p>";
        } else {
            historyContainer.innerHTML = "";
            data.forEach(item => {
                historyContainer.innerHTML += `
                    <p><strong>Request ID:</strong> ${item.request_id} <br>
                    <strong>Type:</strong> ${item.request_Type} <br>
                    <strong>Date Finished:</strong> ${item.date_status}</p>
                    <p>---------------------------------------------</p><hr>`;
            });
        }
    })
    .catch(err => {
        document.getElementById('modalHistory').innerHTML = "<p>Error loading history.</p>";
        console.error("Error fetching history:", err);
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    const modal1 = document.getElementById('personnelModal');
    const addBtn = document.getElementById("addpersonnel");
    const modal = document.getElementById("addPersonnelModal");

    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const staff = {
                staff_id: btn.dataset.staffid,
                full_name: btn.dataset.name,
                department: btn.dataset.dept,
                unit: btn.dataset.unit,
                contact: btn.dataset.contact,
                hire_date: btn.dataset.hiredate,
                status: btn.dataset.status
            };
            showPersonnelModal(staff);
        });
    });

    addBtn.addEventListener("click", () => {
        modal.style.display = "block";
    });

    window.onclick = function(event) {
        if (event.target === modal1) {
            modal1.style.display = 'none';
        }
        if (event.target === modal) {
            closeModal();
        }
    };

    document.getElementById('modalDeleteBtn').addEventListener('click', function() {
        const staffId = document.getElementById('modalStaffId').textContent;
        const staffName = document.getElementById('modalName').textContent;

        Swal.fire({
            title: 'Delete Personnel',
            html: `Are you sure you want to delete <br><strong>${staffName}</strong> (ID: ${staffId})?<br><br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deletePersonnelId').value = staffId;
                document.getElementById('deleteForm').submit();
            }
        });
    });

    document.getElementById("department").addEventListener("change", function() {
        const otherField = document.getElementById("otherDepartmentField");
        if (this.value === "Others") {
            otherField.style.display = "block";
            document.getElementById("other_department").setAttribute("required", "required");
        } else {
            otherField.style.display = "none";
            document.getElementById("other_department").removeAttribute("required");
        }
    });

    document.getElementById("editDepartment").addEventListener('change', function() {
        if (this.value === 'Others') {
            document.getElementById("editOtherDepartmentField").style.display = "block";
        } else {
            document.getElementById("editOtherDepartmentField").style.display = "none";
        }
    });
});
</script>
</body>
</html>

<?php
$conn = new mysqli("localhost", "root", "", "utrms_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['save'])) {
    $id = intval($_POST['staff_id']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $department = $_POST['department'];
    $contact = $_POST['contact'];
    $unit = $_POST['unit'];
    $hire_date = $_POST['hire_date'];
    $other_department = $_POST['other_department'] ?? '';

    // Check if department is "Others" and use the additional field if needed
    if ($department === 'Others') {
        $department = $other_department;
    }

    $stmt = $conn->prepare("CALL spUpdateGsuPersonnel(?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $id, $first_name, $last_name, $department, $contact, $hire_date, $unit);

    if ($stmt->execute()) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Personnel updated successfully!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'personnel.php';
            });
        </script>
        ";
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: 'Error: " . addslashes($stmt->error) . "',
                confirmButtonText: 'OK'
            });
        </script>
        ";

    }
    $stmt->close();
}
?>

<?php
if (isset($_POST['delete_personnel_id'])) {
    $staff_id = intval($_POST['delete_personnel_id']); // Ensure it's an integer
    
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Log the staff_id being deleted
    error_log("Attempting to delete staff_id: " . $staff_id);

    // Check current status
    $statusQuery = "SELECT 
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM request_assigned_personnel rap 
                INNER JOIN request_assignment ra ON rap.request_id = ra.request_id 
                WHERE rap.staff_id = ? AND ra.req_status IN ('In Progress', 'To Inspect')
            ) THEN 'Busy'
            ELSE 'Available'
        END as status";

    $stmt = $conn->prepare($statusQuery);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $status = $result->fetch_assoc()['status'];
    $stmt->close();

    if ($status === 'Busy') {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Cannot Delete',
                text: 'Only available personnel can be deleted. This personnel is currently busy with assignments.',
                confirmButtonText: 'OK'
            });
        </script>
        ";
        exit;
    }

    try {
        // Start transaction
        $conn->begin_transaction();

        // First delete from request_assigned_personnel
        $deleteAssignments = $conn->prepare("DELETE FROM request_assigned_personnel WHERE staff_id = ?");
        $deleteAssignments->bind_param("i", $staff_id);
        $deleteAssignments->execute();
        error_log("Deleted " . $deleteAssignments->affected_rows . " assignments");
        $deleteAssignments->close();

        // Then use the stored procedure to delete the personnel
        $stmt = $conn->prepare("CALL spDeleteGsuPersonnel(?)");
        if (!$stmt) {
            throw new Exception("Error preparing delete statement: " . $conn->error);
        }

        $stmt->bind_param("i", $staff_id);
        
        if ($stmt->execute()) {
            // Check if any rows were affected
            $checkDelete = $conn->query("SELECT COUNT(*) as count FROM gsu_personnel WHERE staff_id = $staff_id");
            $rowExists = $checkDelete->fetch_assoc()['count'];
            
            if ($rowExists > 0) {
                throw new Exception("Delete failed - record still exists");
            }
            
            // Commit transaction
            $conn->commit();
            
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted',
                    text: 'Personnel deleted successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'personnel.php';
                });
            </script>
            ";
        } else {
            throw new Exception("Error executing delete: " . $stmt->error);
        }
        $stmt->close();

    } catch (Exception $e) {
        // Rollback on exception
        $conn->rollback();
        error_log("Delete failed: " . $e->getMessage());
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Deletion Failed',
                text: 'Error: " . addslashes($e->getMessage()) . "',
                confirmButtonText: 'OK'
            });
        </script>
        ";
    }
}
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Handle form submission at the top
$addMessage = "";
if (isset($_POST['add'])) {
    $conn = new mysqli("localhost", "root", "", "utrms_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!filter_var($_POST['staff_id'], FILTER_VALIDATE_INT)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid ID',
                text: 'Staff ID must be a valid integer.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.history.back();
            });
        </script>
        ";
        exit;
    }

    $staff_id = intval($_POST['staff_id']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $department = $_POST['department'];
    $contact = intval($_POST['contact']);
    $hire_date = $_POST['hire_date'];
    $unit = $_POST['unit'];

    if ($department === "Others") {
        $department = $_POST['other_department'] ?? '';
    }

    // Check if all required fields are filled
    if (
        empty($staff_id) || empty($first_name) || empty($last_name) ||
        empty($department) || empty($contact) || empty($hire_date) || empty($unit)
    ) {
        $addMessage = "Please fill in all required fields.";
    } elseif (!is_numeric($staff_id) || !is_numeric($contact)) {
        $addMessage = "Staff ID and Contact must be valid numbers.";
    } else {
        // Check if the staff_id already exists in the database
        $checkQuery = "SELECT COUNT(*) FROM gsu_personnel WHERE staff_id = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("i", $staff_id);
        $stmtCheck->execute();
        $stmtCheck->bind_result($existingCount);
        $stmtCheck->fetch();
        $stmtCheck->close();

        // If the staff_id already exists
        if ($existingCount > 0) {
            // Set session flag to show SweetAlert error
            $_SESSION['staff_id_exists'] = true;
        } else {
            // Add personnel if the staff_id does not exist
            $stmt = $conn->prepare("CALL spAddGsuPersonnel(?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssss", $staff_id, $first_name, $last_name, $department, $contact, $hire_date, $unit);
            if ($stmt->execute()) {
                $_SESSION['staff_id_exists'] = false; // Success flag
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Personnel added successfully!',
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'personnel.php';
                        }
                    });
                </script>";
            } else {
                $addMessage = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    $conn->close();
}

// Check session flag and show SweetAlert if needed
if (isset($_SESSION['staff_id_exists'])) {
    if ($_SESSION['staff_id_exists']) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script><script>
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Staff ID already exists. Please use a different Staff ID.',
                confirmButtonColor: '#3085d6'
            });
        </script>";
    }
    // Unset the session flag after showing the alert
    unset($_SESSION['staff_id_exists']);
}
?>
