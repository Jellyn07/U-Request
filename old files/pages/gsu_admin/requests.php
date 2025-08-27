<?php include 'auth-check.php'; ?>
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "utrms_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_GET['request_id'])) {
    $id = intval($_GET['request_id']);
    $stmt = $conn->prepare("SELECT image_name FROM REQUEST WHERE request_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($image_name);
        $stmt->fetch();

        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $mime = match (strtolower($ext)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            default => 'application/octet-stream',
        };

        header("Content-Type: $mime");

        // Display the image from the uploads directory (ensure the file exists)
        $imagePath = "uploads/" . $image_name;
        if (file_exists($imagePath)) {
            readfile($imagePath);
        } else {
            echo "Image not found.";
        }
    } else {
        echo "Image not found.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>GSU System</title>
    <link rel="icon" href="../../assets/icon/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/shared/admin-menu.css">
    <link rel="stylesheet" href="../../css/shared/admin-global.css">
    <link rel="stylesheet" href="../../css/GSUAdmin/request.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="../../js/request-gsu.js"></script>
</head>
<body>
    <div id="admin-menu"></div>
    <script src="../../js/admin-menu.js"></script>

    <div class="main">
        <p class="type">Requests</p>
        <div class="toolbar">
            <div class="search-container">
                <input type="search" placeholder="Search Request" id="searchInput" oninput="filterTable(this.value)">
            </div>
            <select class="sorting" id="sortSelect" onchange="sortTable()">
                <option value="id">Sort by ID</option>
                <option value="name">Sort A-Z (Name)</option>
                <option value="type">Sort by Type (A-Z)</option>
                <option value="date">Sort by Date</option>
            </select>
            <button id="printButton" class="print" type="button" onclick="printSection()">
                <img src="../../assets/icon/printing.png" alt="Printing">&nbsp;Print
            </button>
            <form method="post"><button class="refresh" name="refresh"><img src="../../assets/icon/refresh.png" alt="Refresh">Refresh</button></form>
            <!-- <button class="addrequest" onclick="openAddRequestModal()">Add Request&nbsp; <img src="../../assets/icon/add.png" style="width: 1vw;"></button> -->
        </div>

        <div class="status_bar">
            <button type="button" class="button active-inspect" id="backBtn">To Inspect</button>
            <button type="button" class="addrequest" id="inProgress">In Progress</button>
            <button type="button" class="addrequest" id="completedBtn">Completed</button>            
        </div>

        <!-- to inspect Requests Section (default visible) -->
        <div class="requests" id="allRequests" style="display: block;">
            <div class="tablereq">
                <table id="requestTable">
                    <thead>
                        <tr class="th-first-child">
                            <th>Request ID</th>
                            <th>Name</th>
                            <th>Request Type</th>
                            <th>Location</th>
                            <th>Date Request</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "utrms_db");
                        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

                        $sql = "SELECT * FROM vw_requests where req_status = 'To Inspect'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $request_id = $row['request_id'];
                        

                        $row_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                        $selected_to_inspect = ($row['req_status'] == 'To Inspect') ? 'selected' : '';
                        $selected_in_progress = ($row['req_status'] == 'In Progress') ? 'selected' : '';
                        $selected_completed = ($row['req_status'] == 'Completed') ? 'selected' : '';
                        
                        echo <<<HTML
                        <tr onclick='showDetails({$row_json})'>
                            <td>{$row['request_id']}</td>
                            <td>{$row['Name']}</td>
                            <td>{$row['request_Type']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['request_date']}</td>
                            <td class='status-cell'>
                                <select onchange='updateStatus({$row['request_id']}, this.value)' onclick='event.stopPropagation()' data-status='{$row['req_status']}'>
                                    <option value='To Inspect' {$selected_to_inspect}>To Inspect</option>
                                    <option value='In Progress' {$selected_in_progress}>In Progress</option>
                                    <option value='Completed' {$selected_completed}>Completed</option>
                                </select>
                            </td>
                        </tr>
HTML;
                        }
                        } else {
                            echo "<tr><td colspan='6'>No requests found.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- In Progress Requests Section -->
        <div class="requests" id="inProgressRequests" style="display: none;">
            <div class="tablereq">
                <table id="requestInProgressTable">
                    <thead>
                        <tr class="th-first-child">
                            <th style="display:none;">Assignment ID</th>
                            <th>Request ID</th>
                            <th>Name</th>
                            <th>Request Type</th>
                            <th>Location</th>
                            <th>Date Request</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "utrms_db");
                        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

                        $sql = "SELECT DISTINCT r.request_id, r.Name, r.request_Type, r.location, r.request_date, 
                                                ra.req_status, ra.priority_status,
                                                ra.reqAssignment_id,
                                                GROUP_CONCAT(DISTINCT CONCAT(p.firstName, ' ', p.lastName, ' (', p.department, ')') SEPARATOR ', ') as assigned_personnel
                                FROM vw_requests r 
                                INNER JOIN request_assignment ra ON r.request_id = ra.request_id
                                LEFT JOIN request_assigned_personnel rap ON r.request_id = rap.request_id
                                LEFT JOIN gsu_personnel p ON rap.staff_id = p.staff_id
                                WHERE ra.req_status = 'In Progress'
                                GROUP BY r.request_id, r.Name, r.request_Type, r.location, r.request_date, ra.req_status, ra.priority_status, ra.reqAssignment_id"
                                ;
                        
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $request_id = $row['request_id'];
                                $row_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                                
                                $selected_to_inspect = ($row['req_status'] == 'To Inspect') ? 'selected' : '';
                                $selected_in_progress = ($row['req_status'] == 'In Progress') ? 'selected' : '';
                                $selected_completed = ($row['req_status'] == 'Completed') ? 'selected' : '';
                                
                                echo <<<HTML
                                <tr data-reqAssignmentId="{$row['reqAssignment_id']}" onclick='openModalWithDetails({$row_json})'>
                                    <td>{$row['request_id']}</td>
                                    <td>{$row['Name']}</td>
                                    <td>{$row['request_Type']}</td>
                                    <td>{$row['location']}</td>
                                    <td>{$row['request_date']}</td>
                                    <td class='status-cell'>
                                        <select onchange='updateStatus({$row['request_id']}, this.value)' onclick='event.stopPropagation()' data-status="{$row['req_status']}">
                                            <!-- <option value='To Inspect' {$selected_to_inspect}>To Inspect</option> -->
                                            <option value='In Progress' {$selected_in_progress}>In Progress</option>
                                            <option value='Completed' {$selected_completed}>Completed</option>
                                        </select>
                                    </td>
                                </tr>
HTML;
                            }
                        } else {
                            echo "<tr><td colspan='6'>No in-progress requests found.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- <button id="backBtnInProgress" style="margin-left:70vw; margin-top: 1vw; width:80px">Back</button> -->
        </div>

        <!-- Completed Requests Section (initially hidden) -->
    <div class="requests" id="completedRequests" style="display: none;">
        <div class="tablereq">
            <table id="requestcomTable">
                <thead>
                    <tr class="th-first-child">
                    <th style="display:none;">Assignment ID</th>
                        <th>Request ID</th>
                        <th>Name</th>
                        <th>Request Type</th>
                        <th>Location</th>
                        <th>Date Request</th>
                        <th>Date Finished</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "utrms_db");
                    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

                    $sql = "
    SELECT 
        r.request_id, 
        r.Name, 
        r.request_Type, 
        r.location, 
        r.request_date, 
        ra.date_finished, 
        ra.reqAssignment_id,
        GROUP_CONCAT(DISTINCT CONCAT(p.firstName, ' ', p.lastName, ' (', p.department, ')') SEPARATOR ', ') AS assigned_personnel
    FROM vw_completeRequests r
    LEFT JOIN request_assignment ra ON r.request_id = ra.request_id
    LEFT JOIN request_assigned_personnel rap ON r.request_id = rap.request_id
    LEFT JOIN gsu_personnel p ON rap.staff_id = p.staff_id
    WHERE ra.req_status = 'Complete' OR ra.req_status = 'Completed'
    GROUP BY 
        r.request_id, 
        r.Name, 
        r.request_Type, 
        r.location, 
        r.request_date, 
        ra.date_finished, 
        ra.reqAssignment_id
";

                    $result = $conn->query($sql);

                    if($result){
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $assigned_personnel = $row['assigned_personnel'] ? $row['assigned_personnel'] : 'No personnel assigned';
                                echo "<tr 
                                        onclick='showCompletedDetails(this)' 
                                        class='clickable-row' 
                                        style='cursor: pointer;'
                                        data-tracking='{$row['request_id']}'
                                        data-type='{$row['request_Type']}'
                                        data-location='{$row['location']}'
                                        data-status='Completed'
                                        data-personnel='{$row['Name']}'
                                        data-date-finished='{$row['date_finished']}'
                                        data-assigned-personnel='" . htmlspecialchars($assigned_personnel, ENT_QUOTES) . "'
                                        data-reqAssignmentId='{$row['reqAssignment_id']}'
                                    >
                                    <td>{$row['request_id']}</td>
                                    <td>{$row['Name']}</td>
                                    <td>{$row['request_Type']}</td>
                                    <td>{$row['location']}</td>
                                    <td>{$row['request_date']}</td>
                                    <td>{$row['date_finished']}</td>
                                </tr>";
                            }
                        
                        
                        } else {
                            echo "<tr><td colspan='6'>No requests found.</td></tr>";
                        }   
                    } else {
                        echo "<tr><td colspan='6'>Error: " . $conn->error . "</td></tr>";
                    }
                    
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Single Modal for all details -->
    <div id="completedModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 1000;">
        <div class="modal-content" style="position: relative; background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px; border-radius: 0.5vw;">
            <span class="close" onclick="closeCompletedModal()" style="position: absolute; right: 10px; top: 10px; font-size: 15px; font-weight: bold; cursor: pointer;">&times;</span>
            <div id="completedModalContent"></div>
        </div>
    </div>

    <div id="overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 1000; justify-content: center; align-items: center;">
        <div id="result-modal" style="background: white; padding: 20px; border-radius: 0.5vw; width: 80%; max-width: 600px; position: relative;">
            <button id="close-btn" style="position: absolute; right: 10px; top: 10px; border: none; background: none; font-size: 15px; cursor: pointer;">&times;</button>
            <div id="result-content"></div>
        </div>
    </div>

    <script>
    // Remove any existing event listeners
    const existingRows = document.querySelectorAll('#completedRequests .clickable-row');
    existingRows.forEach(row => {
        row.replaceWith(row.cloneNode(true));
    });

    // Add the single click handler for completed requests
    document.getElementById('completedRequests').addEventListener('click', function(event) {
        const row = event.target.closest('.clickable-row');
        if (row) {
            showCompletedDetails(row);
        }
    });

    function showCompletedDetails(row) {
        const tracking = row.getAttribute('data-tracking');
        const type = row.getAttribute('data-type');
        const location = row.getAttribute('data-location');
        const status = row.getAttribute('data-status');
        const personnel = row.getAttribute('data-personnel');
        const dateFinished = row.getAttribute('data-date-finished');
        const assignedPersonnel = row.getAttribute('data-assigned-personnel');
        const materialsList = document.getElementById('materialsUsedList');
        const reqAssignmentId = row.getAttribute('data-reqAssignmentId'); 

        // Show loading state
        document.getElementById('completedModalContent').innerHTML = '<div style="text-align: center; padding: 20px;">Loading...</div>';
        document.getElementById('completedModal').style.display = 'block';

        // Format the date finished
        const formattedDateFinished = dateFinished ? new Date(dateFinished).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) : 'Not recorded';

        // Create the content with the image
        const content = `
            <h3 style="margin-bottom: 20px; text-align: center; color: #000; font-size: 15px;">Request Details</h3>
            <div id="completedModalContent" style="background:#e4f1ff; padding: 15px; border-radius: 0.5vw; margin-bottom: 20px;">
                <p><strong>Request ID:</strong> ${tracking}</p>
                <p><strong>Request Type:</strong> ${type}</p>
                <p><strong>Location:</strong> ${location}</p>
                <p><strong>Status:</strong> <span style="color:rgb(0, 163, 54);">${status}</span></p>
                <p><strong>Requestor:</strong> ${personnel}</p>
                <p><strong>Date Finished:</strong> ${formattedDateFinished}</p>
                <p><strong>Assigned Personnel:</strong> ${assignedPersonnel}</p><br>
                <h4 style="font-size: 13px;">Materials Used:</h4>
                <div id="materialsUsedList" style="font-size: 12px;">
                    <!-- Materials will be listed here dynamically -->
                </div>
            </div>
            <div style="margin-top: 20px;">
                <h4 style="margin-bottom: 10px; font-size: 13px;">Photo Evidence:</h4>
                <div style="text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <img src="view-image.php?request_id=${tracking}" 
                         alt="Photo Evidence" 
                         style="max-width: 70%; height: auto;"
                         onerror="if (!this.hasAttribute('data-fallback-attempted')) { this.setAttribute('data-fallback-attempted', 'true'); this.src='../../assets/icon/no-image.png'; } else { this.parentElement.innerHTML = '<br><em>No image available</em>'; }">
                </div>
            </div>
        `;

        document.getElementById('completedModalContent').innerHTML = content;

        fetch('get-material-used.php?reqAssignment_id=' + reqAssignmentId)
        .then(response => response.json())
        .then(data => {
            const materialsList = document.getElementById('materialsUsedList');
            if (data.success && data.materials.length > 0) {
                const ul = document.createElement('ul');
                data.materials.forEach(material => {
                    const li = document.createElement('li');
                    li.textContent = `${material.material_desc} - Quantity: ${material.quantity_needed}`;
                    ul.appendChild(li);
                });
                // materialsList.innerHTML = '<h4>Materials Used:</h4>';
                materialsList.appendChild(ul);
            } else {
                materialsList.innerHTML = 'No materials used.';
            }
        })
        .catch(error => {
            console.error('Error fetching materials:', error);
            const materialsList = document.getElementById('materialsUsedList');
            if (materialsList) {
                materialsList.innerHTML = 'Failed to load materials.';
            }
        });
    }

    function closeCompletedModal() {
        document.getElementById('completedModal').style.display = 'none';
    }

    // Single event listener for closing modal
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('completedModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    // Single event listener for Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('completedModal');
            if (modal.style.display === 'block') {
                modal.style.display = 'none';
            }
        }
    });
    </script>

    <style>
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .clickable-row:hover {
        background-color: #f5f5f5;
    }

    .modal-content {
        animation: modalSlideIn 0.3s ease-out;
        max-height: 80vh;
        overflow-y: auto;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-100px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    </style>

    <!-- Overlay and Details Modal -->
    <div id="modalOverlay"></div>
    <div id="detailsModal">
        <form method="POST" action="">
            <input type="hidden" name="request_id" id="modalRequestId">
            <h3>Request Details</h3>
            <p><strong>Name:</strong> <span id="modalName"></span></p>
            <p><strong>Request Type:</strong> <span id="modalType"></span></p>
            <p><strong>Location:</strong> <span id="modalLocation"></span></p>
            <p><strong>Date Requested:</strong> <span id="modalDate"></span></p>
            <p><strong>Photo Evidence:</strong></p>
            <img id="modalImage"  src="" alt="Photo Evidence"><br>

            <label>Priority:
                <select name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Average">Average</option>
                    <option value="High">High</option>
                </select>
            </label>

            <label>Status:
                <select name="status" required>
                    <option value="To Inspect">To Inspect</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Complete">Complete</option>
                </select>
            </label>

            <label>Assign Personnel:</label>
            <div id="personnel-container">
                <div class="personnel-entry">
                    <select name="personnel[]" class="personnel-select" required onchange="updateDropdowns()">
                        <option value="" disabled selected>Select personnel</option>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "utrms_db");
                        if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

                        $sql = "SELECT staff_id, full_name, department FROM vw_gsu_personnel";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $value = htmlspecialchars($row['staff_id']); 
                                $display = htmlspecialchars($row['full_name'] . " (" . $row['department'] . ")");
                                echo "<option value='{$value}' data-name='{$row['full_name']}'>{$display}</option>";
                            }
                        } else {
                            echo "<option disabled>No personnel found</option>";
                        }
                        $conn->close();
                        ?>
                    </select>
                    <button type="button" onclick="addPersonnel()">+</button>
                    <button type="button" class="delete-btn" onclick="removePersonnel(this)">×</button>
                </div>
            </div>

            <div class="modal-buttons">
                <button type="submit" name="saveReqAss" id="save">Save</button>
                <button type="button" onclick="closeModal()" id="close">Close</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the necessary elements
            const allRequestsDiv = document.getElementById('allRequests');
            const inProgressDiv = document.getElementById('inProgressRequests');
            const completedDiv = document.getElementById('completedRequests');
            const inProgressBtn = document.getElementById('inProgress');
            const completedBtn = document.getElementById('completedBtn');
            const backBtn = document.getElementById('backBtn');
            const backBtnInProgress = document.getElementById('backBtnInProgress');

            // Button click handlers
            if(inProgressBtn) {
                inProgressBtn.addEventListener('click', function() {
                    allRequestsDiv.style.display = 'none';
                    completedDiv.style.display = 'none';
                    inProgressDiv.style.display = 'block';
                    setActiveButton('progress');
                    refreshInProgressTable();
                });
            }

            if(completedBtn) {
                completedBtn.addEventListener('click', function() {
                    allRequestsDiv.style.display = 'none';
                    inProgressDiv.style.display = 'none';
                    completedDiv.style.display = 'block';
                    setActiveButton('completed');
                });
            }

            if(backBtn) {
                backBtn.addEventListener('click', function() {
                    completedDiv.style.display = 'none';
                    inProgressDiv.style.display = 'none';
                    allRequestsDiv.style.display = 'block';
                    setActiveButton('inspect');
                });
            }

            if(backBtnInProgress) {
                backBtnInProgress.addEventListener('click', function() {
                    inProgressDiv.style.display = 'none';
                    completedDiv.style.display = 'none';
                    allRequestsDiv.style.display = 'block';
                    setActiveButton('inspect');
                });
            }
        });

        function setActiveButton(section) {
            document.getElementById('backBtn').classList.remove('active-inspect', 'active-progress', 'active-completed');
            document.getElementById('inProgress').classList.remove('active-inspect', 'active-progress', 'active-completed');
            document.getElementById('completedBtn').classList.remove('active-inspect', 'active-progress', 'active-completed');

            if (section === 'inspect') {
                document.getElementById('backBtn').classList.add('active-inspect');
            } else if (section === 'progress') {
                document.getElementById('inProgress').classList.add('active-progress');
            } else if (section === 'completed') {
                document.getElementById('completedBtn').classList.add('active-completed');
            }
        }

        function updateStatus(requestId, newStatus) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to change the status to "${newStatus}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('update-status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            request_id: requestId,
                            status: newStatus
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Server responded with an error: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Updated!', 'The status has been updated.', 'success')
                            .then(() => {
                                // refreshInProgressTable();
                                // // Refresh the current view based on which section is visible
                                if (document.getElementById('allRequests').style.display !== 'none') {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire('Error', 'Failed to update status. Please try again later.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'There was an error updating the status. Please try again later.', 'error');
                        console.error('Error:', error);
                    });
                } else {
                    Swal.fire('Cancelled', 'The status update has been cancelled.', 'error');
                    resetStatus(requestId);
                }
            });
        }

        function refreshInProgressTable() {
            const tableBody = document.querySelector('#requestInProgressTable tbody');
            fetch('refresh-in-progress-requests.php')
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading table data:', error);
                });
        }

       

        function resetStatus(requestId) {
            // Reset the status field if the change was canceled
            const selectElement = document.querySelector(`select[onchange="confirmStatusChange(${requestId}, this.value)"]`);
            const currentStatus = selectElement.getAttribute('data-current-status');
            selectElement.value = currentStatus; // Restore previous status
        }

        function openAddRequestModal() {
            document.getElementById('addRequestOverlay').style.display = 'block';
            document.getElementById('addRequestModal').style.display = 'block';
            // Reset form when opening
            document.querySelector('form[name="make-request"]').reset();
            // Reset review section
            document.getElementById('request-info').style.display = 'block';
            document.getElementById('review-section').style.display = 'none';
        }

        function closeAddRequestModal() {
            document.getElementById('addRequestOverlay').style.display = 'none';
            document.getElementById('addRequestModal').style.display = 'none';
            // Reset form when closing
            document.querySelector('form[name="make-request"]').reset();
            // Reset review section
            document.getElementById('request-info').style.display = 'block';
            document.getElementById('review-section').style.display = 'none';
        }

        // Close modal when clicking overlay
        document.getElementById('addRequestOverlay').addEventListener('click', function(event) {
            if (event.target === this) {
                closeAddRequestModal();
            }
        });

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddRequestModal();
            }
        });

        function showDetails(row) {
            document.getElementById('modalRequestId').value = row.request_id;
            document.getElementById('modalName').innerText = row.Name;
            document.getElementById('modalType').innerText = row.request_Type;
            // document.getElementById('modalDesc').innerText = row.request_desc || 'No description available';
            document.getElementById('modalLocation').innerText = row.location;
            document.getElementById('modalDate').innerText = row.request_date;
            document.getElementById("modalImage").src = "view-image.php?request_id=" + row.request_id;
            document.getElementById("modalImage").onerror = function() {
                if (!this.hasAttribute('data-fallback-attempted')) {
                    this.setAttribute('data-fallback-attempted', 'true');
                    this.src = '../../assets/icon/no-image.png';
                } else {
                    this.parentElement.innerHTML = '<br><em>No image available</em>';
                }
            };

            document.getElementById('modalOverlay').style.display = 'block';
            document.getElementById('detailsModal').style.display = 'block';
        }

        function sortTableByDropdown() {
            const order = document.getElementById("sortSelect").value;
            const ascending = (order === "az");
            const table = document.getElementById("requestTable");
            const rows = Array.from(table.rows).slice(1);

            rows.sort((a, b) => {
                const textA = a.cells[1].innerText.toLowerCase();
                const textB = b.cells[1].innerText.toLowerCase();
                return ascending ? textA.localeCompare(textB) : textB.localeCompare(textA);
            });

            rows.forEach(row => table.appendChild(row));
        }
        function showReview(event) {
            event.preventDefault();
            // Get form values
            const natureRequest = document.querySelector('input[name="nature-request"]:checked');
            const description = document.getElementById('descrip').value;
            const unit = document.getElementById('unit').value;
            const exLoc = document.getElementById('exLoc').value;
            const dateNoticed = document.getElementById('dateNoticed').value;
            const image = document.getElementById('img').files[0];

            // Validate form
            if (!natureRequest || !description || !unit || !exLoc || !dateNoticed) {
                alert('Please fill in all required fields');
                return;
            }

            // Update review section
            document.getElementById('review-nature').textContent = natureRequest.value;
            document.getElementById('review-description').textContent = description;
            document.getElementById('review-unit').textContent = unit;
            document.getElementById('review-exLoc').textContent = exLoc;
            document.getElementById('review-date').textContent = dateNoticed;

            // Handle image preview
            const imgPreview = document.getElementById('review-image-preview');
            const imgName = document.getElementById('review-image-name');
            if (image) {
                imgPreview.src = URL.createObjectURL(image);
                imgPreview.style.display = 'block';
                imgName.textContent = image.name;
            } else {
                imgPreview.style.display = 'none';
                imgName.textContent = 'No file chosen';
            }

            // Show review section, hide form
            document.getElementById('request-info').style.display = 'none';
            document.getElementById('review-section').style.display = 'block';
        }
        function backReview() {
            document.getElementById('request-info').style.display = 'block';
            document.getElementById('review-section').style.display = 'none';
        }
        function printSection() {
    const headerHtml = `
        <div style="text-align:center;">
            <img src="../../assets/icon/usep.png" style="height:90px;"><br>
            <h2 class="print-header">University of Southeastern Philippines</h2>
            <h3>General Services Unit - Request Report</h3>
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

    // Detect which section is currently visible
    let tableId = '';
    if (document.getElementById('allRequests').style.display !== 'none') {
        tableId = 'requestTable';
    } else if (document.getElementById('inProgressRequests').style.display !== 'none') {
        tableId = 'requestInProgressTable';
    } else if (document.getElementById('completedRequests').style.display !== 'none') {
        tableId = 'requestcomTable';
    }

    const originalTable = document.getElementById(tableId);
    if (!originalTable) {
        alert("No data to print.");
        return;
    }

    const tableHead = originalTable.querySelector("thead");
    const tableRows = Array.from(originalTable.querySelectorAll("tbody tr"));

    // Clone rows with all columns, including status
    const cleanedRows = tableRows.map(row => {
        const clone = row.cloneNode(true);
        // Remove select dropdowns and convert to text (for "To Inspect" and "In Progress")
        clone.querySelectorAll('select').forEach(select => {
            const selectedText = select.options[select.selectedIndex].text;
            const td = document.createElement('td');
            td.textContent = selectedText;
            select.parentElement.replaceWith(td);
        });
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
                ${tableHead.outerHTML}
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

        function addPersonnel() {
            const container = document.getElementById('personnel-container');
            const newEntry = document.createElement('div');
            newEntry.className = 'personnel-entry';
            newEntry.innerHTML = `
                <select name="personnel[]" class="personnel-select" required onchange="updateDropdowns()">
                    <option value="" disabled selected>Select personnel</option>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "gsu_system");
                    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

                    $sql = "SELECT staff_id, full_name, department FROM vw_gsu_personnel";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $value = htmlspecialchars($row['staff_id']); 
                            $display = htmlspecialchars($row['full_name'] . " (" . $row['department'] . ")");
                            echo "<option value='{$value}' data-name='{$row['full_name']}'>{$display}</option>";
                        }
                    } else {
                        echo "<option disabled>No personnel found</option>";
                    }
                    $conn->close();
                    ?>
                </select>
                <button type="button" onclick="addPersonnel()">+</button>
                <button type="button" class="delete-btn" onclick="removePersonnel(this)">×</button>
            `;
            container.appendChild(newEntry);
        }

        function removePersonnel(button) {
            const container = document.getElementById('personnel-container');
            const entry = button.parentNode;
            if (container.children.length > 1) {
                container.removeChild(entry);
            }
        }

        // Table filtering function
        function filterTable(searchText) {
            searchText = searchText.toLowerCase();
            const tables = document.querySelectorAll('.tablereq table');
            
            tables.forEach(table => {
                const rows = table.getElementsByTagName('tr');
                
                // Start from index 1 to skip the header row
                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let rowText = '';
                    
                    // Concatenate all cell text except the last cell (status/actions)
                    for (let j = 0; j < cells.length - 1; j++) {
                        rowText += cells[j].textContent.toLowerCase() + ' ';
                    }
                    
                    // Show/hide row based on search text
                    if (rowText.includes(searchText)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        // Initialize search if there's a value in the search input
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput.value) {
                filterTable(searchInput.value);
            }
        });

        // Add event listener for the refresh button to clear search
        document.querySelector('.refresh').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            filterTable('');
        });

        // Table sorting function
        function sortTable() {
            const sortSelect = document.getElementById('sortSelect');
            const sortBy = sortSelect.value;
            const activeTable = document.querySelector('.requests[style*="display: block"] table');
            
            if (!activeTable) return;
            
            const rows = Array.from(activeTable.getElementsByTagName('tr'));
            const headerRow = rows.shift(); // Remove and store header row
            
            rows.sort((a, b) => {
                let aValue, bValue;
                
                switch(sortBy) {
                    case 'id':
                        aValue = parseInt(a.cells[0].textContent);
                        bValue = parseInt(b.cells[0].textContent);
                        return aValue - bValue;
                    case 'name':
                        aValue = a.cells[1].textContent.toLowerCase();
                        bValue = b.cells[1].textContent.toLowerCase();
                        return aValue.localeCompare(bValue);
                    case 'type':
                        aValue = a.cells[2].textContent.toLowerCase();
                        bValue = b.cells[2].textContent.toLowerCase();
                        return aValue.localeCompare(bValue);
                    case 'date':
                        aValue = new Date(a.cells[4].textContent);
                        bValue = new Date(b.cells[4].textContent);
                        return aValue - bValue;
                    default:
                        return 0;
                }
            });
            
            // Clear table and re-add rows
            while (activeTable.firstChild) {
                activeTable.removeChild(activeTable.firstChild);
            }
            
            activeTable.appendChild(headerRow);
            rows.forEach(row => activeTable.appendChild(row));
        }

        // Add event listener for tab changes to maintain search/sort
        document.querySelectorAll('.status_bar button').forEach(button => {
            button.addEventListener('click', () => {
                setTimeout(() => {
                    filterTable();
                    sortTable();
                }, 100);
            });
        });
        </script>

        <style>
        .close-modal-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.2vw;
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
        }

        .close-modal-btn:hover {
            color: #000;
        }
        </style>

        <script>
            function handleRowClick(event, row) {
                // Prevent click if target is a dropdown/select
                if (event.target.tagName === 'SELECT' || event.target.closest('.status-cell')) return;

                const rowData = JSON.parse(row.getAttribute('data-row'));

                openModalWithRequest(rowData.request_id);

                // Fill modal fields
                document.getElementById("modalRequestId").value = rowData.request_id;
                document.getElementById("modalName").textContent = rowData.Name;
                document.getElementById('modalDesc').innerText = rowData.req_desc || '';
                document.getElementById("modalType").textContent = rowData.request_Type;
                document.getElementById("modalLocation").textContent = rowData.location;
                document.getElementById("modalDate").textContent = rowData.request_date;

                document.getElementById("modalImage").src = "view-image.php?request_id=" + rowData.request_id;
                document.getElementById("modalImage").onerror = function() {
                    if (!this.hasAttribute('data-fallback-attempted')) {
                        this.setAttribute('data-fallback-attempted', 'true');
                        this.src = '../../assets/icon/no-image.png';
                    } else {
                        this.parentElement.innerHTML = '<br><em>No image available</em>';
                    }
                };

                

                // Show the modal
                document.getElementById("modalOverlay").style.display = "block";
                document.getElementById("detailsModal").style.display = "block";
            }

            function updateStatus(requestId, newStatus) {
                // Show confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to change the status to "${newStatus}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update it!',
                    cancelButtonText: 'No, cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with the update if confirmed
                        fetch('update-status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                request_id: requestId,
                                status: newStatus
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Server responded with an error: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Updated!', 'The status has been updated.', 'success');
                                 refreshInProgressTable();
                            } else {
                                Swal.fire('Error', 'Failed to update status. Please try again later.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'There was an error updating the status. Please try again later.', 'error');
                            console.error('Error:', error);
                        });
                    } else {
                        // If cancelled
                        Swal.fire('Cancelled', 'The status update has been cancelled.', 'error');
                    }
                });
            }

            function refreshInProgressTable() {
                const tableBody = document.querySelector('#requestInProgressTable tbody');
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'refresh-in-progress-requests.php', true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        tableBody.innerHTML = xhr.responseText;
                    } else {
                        console.error('Error loading table data');
                    }
                };
                xhr.send();
            }


            function resetStatus(requestId) {
                // Reset the status field if the change was canceled
                const selectElement = document.querySelector(`select[onchange="confirmStatusChange(${requestId}, this.value)"]`);
                const currentStatus = selectElement.getAttribute('data-current-status');
                selectElement.value = currentStatus; // Restore previous status
            }

            

            function setActiveButton(section) {
                document.getElementById('backBtn').classList.remove('active-inspect', 'active-progress', 'active-completed');
                document.getElementById('inProgress').classList.remove('active-inspect', 'active-progress', 'active-completed');
                document.getElementById('completedBtn').classList.remove('active-inspect', 'active-progress', 'active-completed');

                if (section === 'inspect') {
                    document.getElementById('backBtn').classList.add('active-inspect');
                } else if (section === 'progress') {
                    document.getElementById('inProgress').classList.add('active-progress');
                } else if (section === 'completed') {
                    document.getElementById('completedBtn').classList.add('active-completed');
                }
            }

        // Example usage: adjust this based on how you switch sections
        document.getElementById('backBtn').addEventListener('click', function(e) {
            window.location.href = 'requests.php';
        });

        document.getElementById('inProgress').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default action
            document.getElementById('allRequests').style.display = 'none';
            document.getElementById('inProgressRequests').style.display = 'block';
            document.getElementById('completedRequests').style.display = 'none';
            setActiveButton('progress');
        });

        document.getElementById('completedBtn').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default action
            document.getElementById('allRequests').style.display = 'none';
            document.getElementById('inProgressRequests').style.display = 'none';
            document.getElementById('completedRequests').style.display = 'block';
            setActiveButton('completed');
        });

        function addMaterial() {
            const container = document.getElementById('materials-container');
            const firstSelect = container.querySelector('select.material-input');
            const newEntry = document.createElement('div');
            newEntry.className = 'material-entry';
            newEntry.innerHTML = firstSelect.parentElement.innerHTML;
            container.appendChild(newEntry);

            // Add event listener to the new quantity input
            const newQuantityInput = newEntry.querySelector('input[type="number"]');
            if (newQuantityInput) {
                newQuantityInput.addEventListener('input', validateQuantity);
            }
        }

        function removeMaterial(button) {
            const container = document.getElementById('materials-container');
            const entry = button.parentNode;
            if (container.children.length > 1) {
                container.removeChild(entry);
            }
        }

        function validateQuantity(event) {
            const quantityInput = event.target;
            const materialSelect = quantityInput.parentElement.querySelector('select');
            const selectedOption = materialSelect.options[materialSelect.selectedIndex];
            
            if (selectedOption.dataset.available) {
                const availableQuantity = parseInt(selectedOption.dataset.available);
                const requestedQuantity = parseInt(quantityInput.value);
                const unit = selectedOption.dataset.unit;
                const materialName = selectedOption.text.split(' (')[0];
                
                if (requestedQuantity > availableQuantity) {
                    quantityInput.value = availableQuantity;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Quantity Limit Exceeded',
                        text: `Only ${availableQuantity} ${unit} of ${materialName} available.`
                    });
                } else if (requestedQuantity <= 0) {
                    quantityInput.value = 1;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Quantity',
                        text: 'Please enter a quantity greater than 0.'
                    });
                }
            }
        }

        // Add event listeners to initial quantity inputs
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(input => {
                input.addEventListener('input', validateQuantity);
            });
        });

        function closeModal() {
            document.getElementById('modalOverlay').style.display = 'none';
            document.getElementById('detailsModal').style.display = 'none';
            // Reset form when closing
            document.querySelector('form').reset();
        }

        // Close modal when clicking overlay
        document.getElementById('modalOverlay').addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal();
            }
        });

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>

    <!-- Materials Modal -->
    <div id="materialsModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 1001;">
        <div class="modal-content" style="position: relative; background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 60%; max-width: 500px; border-radius: 5px;">
            <span class="close" onclick="closeMaterialsModal()" style="position: absolute; right: 10px; top: 10px; font-size: 15px; font-weight: bold; cursor: pointer;">&times;</span>
            <h3 style="margin-bottom: 20px; font-size: 15px; ">Material Used</h3>
            <form id="materialsForm" onsubmit="saveMaterials(event)">
                <input type="hidden" id="requestIdForMaterials" name="reqAssignment_id">
                <div id="materials-container">
                    <div class="material-entry" style="margin-bottom: 15px;">
                        <select name="materials[]" class="material-input" required style="width: 60%; margin-right: 10px;">
                            <option value="" disabled selected>Select material</option>
                            <?php
                            $conn = new mysqli("localhost", "root", "", "utrms_db");
                            if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

                            $sql = "SELECT material_code, material_desc, qty FROM vw_materials WHERE qty > 0 ORDER BY material_desc ASC";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $value = htmlspecialchars($row['material_code']);
                                    $display = htmlspecialchars($row['material_desc'] . " (" . $row['qty'] . " available)");
                                    echo "<option value='{$value}' data-available='{$row['qty']}'>{$display}</option>";
                                }
                            } else {
                                echo "<option disabled>No materials found</option>";
                            }
                            $conn->close();
                            ?>
                        </select>
                        <input type="number" name="quantities[]" class="quantity-input" placeholder="Qty" required min="1" style="width: 80px; margin-right: 10px;">
                        <button type="button" onclick="addMaterialField()" style="padding: 5px 10px; margin-right: 5px;">+</button>
                        <button type="button" onclick="removeMaterialField(this)" style="padding: 5px 10px;">×</button>
                    </div>
                </div>
                <div style="margin-top: 20px; text-align: right;">
                    <button type="submit" style="padding: 8px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openMaterialsModal(reqAssignmentId) {
        document.getElementById('requestIdForMaterials').value = reqAssignmentId;
        document.getElementById('materialsModal').style.display = 'block';
        event.stopPropagation(); // Prevent the event from bubbling up
    }

    function closeMaterialsModal() {
        document.getElementById('materialsModal').style.display = 'none';
    }

    function addMaterialField() {
        const container = document.getElementById('materials-container');
        const newEntry = container.querySelector('.material-entry').cloneNode(true);
        
        // Reset the values
        newEntry.querySelector('select').value = '';
        newEntry.querySelector('input').value = '';
        
        container.appendChild(newEntry);
    }

    function removeMaterialField(button) {
        const container = document.getElementById('materials-container');
        if (container.children.length > 1) {
            button.closest('.material-entry').remove();
        }
    }

    function saveMaterials(event) {
        event.preventDefault();
        const formData = new FormData(document.getElementById('materialsForm'));
        const reqAssignmentId = document.getElementById('requestIdForMaterials').value;
        fetch('save-materials.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Materials have been saved successfully!'
                }).then(() => {
                    closeMaterialsModal();
                    loadMaterialsUsed(reqAssignmentId);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to save materials. Please try again.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving materials.'
            });
        });
    }

    // Update the openModalWithDetails function to include the materials button
    function openModalWithDetails(rowData) {
        const tracking = rowData.request_id;
        const type = rowData.request_Type;
        const location = rowData.location;
        const status = rowData.req_status;
        const personnel = rowData.Name;
        const assignedPersonnel = rowData.assigned_personnel || 'No personnel assigned';
        const materialsList = document.getElementById('materialsUsedList');
        const reqAssignmentId = rowData.reqAssignment_id; 

        // Show loading state
        document.getElementById('completedModalContent').innerHTML = '<div style="text-align: center; padding: 20px;">Loading...</div>';
        document.getElementById('completedModal').style.display = 'block';

        const content = `
            <h3 style="margin-bottom: 20px; text-align: center; color: #000; font-size: 15px;">Request Details</h3>
            <div style="background: #e4f1ff; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <p><strong>Request ID:</strong> ${tracking}</p>
                <p><strong>Request Type:</strong> ${type}</p>
                <p><strong>Location:</strong> ${location}</p>
                <p><strong>Status:</strong> <span style="color:rgb(0, 73, 156);">${status}</span></p>
                <p><strong>Requestor:</strong> ${personnel}</p>
                <p><strong>Assigned Personnel:</strong> ${assignedPersonnel}</p>
                <div style="margin-top: 15px;">
                    <p style="font-size: 13px;">Materials Used:</p>
                    <div id="materialsUsedList">
                    <!-- Materials will be listed here dynamically -->
                    </div>
                    <br>
                    <button onclick="openMaterialsModal(${rowData.reqAssignment_id})" style="padding: 5px 15px; background-color: #077b80; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                        Add Materials Used
                    </button>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <h4 style="margin-bottom: 10px;">Photo Evidence</h4>
                <div style="text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <img src="view-image.php?request_id=${tracking}" 
                         alt="Photo Evidence" 
                         style="max-width: 70%; height: auto;"
                         onerror="if (!this.hasAttribute('data-fallback-attempted')) { this.setAttribute('data-fallback-attempted', 'true'); this.src='../../assets/icon/no-image.png'; } else { this.parentElement.innerHTML = '<br><em>No image available</em>'; }">
                </div>
            </div>
        `;

        document.getElementById('completedModalContent').innerHTML = content;
        // ✅ Now fetch and populate materials
        fetch('get-material-used.php?reqAssignment_id=' + reqAssignmentId)
        .then(response => response.json())
        .then(data => {
            const materialsList = document.getElementById('materialsUsedList');
            if (data.success && data.materials.length > 0) {
                const ul = document.createElement('ul');
                data.materials.forEach(material => {
                    const li = document.createElement('li');
                    li.textContent = `${material.material_desc} - Quantity: ${material.quantity_needed}`;
                    ul.appendChild(li);
                });
                // materialsList.innerHTML = '<h4>Materials Used:</h4>';
                materialsList.appendChild(ul);
            } else {
                materialsList.innerHTML = 'No materials used yet.';
            }
        })
        .catch(error => {
            console.error('Error fetching materials:', error);
            const materialsList = document.getElementById('materialsUsedList');
            if (materialsList) {
                materialsList.innerHTML = 'Failed to load materials.';
            }
        });
    }

    // Add event listener for clicking outside the materials modal
    window.addEventListener('click', function(event) {
        const materialsModal = document.getElementById('materialsModal');
        if (event.target == materialsModal) {
            closeMaterialsModal();
        }
    });

    // Add event listener for Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const materialsModal = document.getElementById('materialsModal');
            if (materialsModal.style.display === 'block') {
                closeMaterialsModal();
            }
        }
    });

    function loadMaterialsUsed(reqAssignmentId) {
    fetch('get-material-used.php?reqAssignment_id=' + reqAssignmentId)
        .then(response => response.json())
        .then(data => {
            const materialsList = document.getElementById('materialsUsedList');
            if (data.success && data.materials.length > 0) {
                const ul = document.createElement('ul');
                data.materials.forEach(material => {
                    const li = document.createElement('li');
                    li.textContent = `${material.material_desc} - Quantity: ${material.quantity_needed}`;
                    ul.appendChild(li);
                });
                materialsList.innerHTML = '<h4>Materials Used:</h4>';
                materialsList.appendChild(ul);
            } else {
                materialsList.innerHTML = 'No materials used yet.';
            }
        })
        .catch(error => {
            console.error('Error fetching materials:', error);
            const materialsList = document.getElementById('materialsUsedList');
            if (materialsList) {
                materialsList.innerHTML = 'Failed to load materials.';
            }
        });
}

    </script>

</body>
</html>

<?php

$conn = new mysqli("localhost", "root", "", "utrms_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['saveReqAss'])) {
    error_log("Form submitted with saveReqAss");
    error_log("POST data: " . print_r($_POST, true));

    // Check if priority is selected and not empty
    if (empty($_POST['priority'])) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a priority level'
            });
        </script>";
        exit;
    }

    // Check if at least one personnel is selected
    if (empty($_POST['personnel']) || !is_array($_POST['personnel']) || count(array_filter($_POST['personnel'])) === 0) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please assign at least one personnel'
            });
        </script>";
        exit;
    }

    if (isset($_POST['request_id'], $_POST['priority'], $_POST['status'], $_POST['personnel']) && is_array($_POST['personnel'])) {
        $request_id = intval($_POST['request_id']);
        $priority = $_POST['priority'];
        $status = $_POST['status'];
        $assignedPersonnel = array_filter($_POST['personnel']); // Remove empty values

        try {
            $conn->begin_transaction();

            // 1. Update priority status
            $stmt = $conn->prepare("CALL spUpdateRequestPriorityStatus(?, ?)");
            if (!$stmt) {
                throw new Exception("Failed to prepare priority status update");
            }
            $stmt->bind_param("is", $request_id, $priority);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update priority status");
            }
            $stmt->close();

            // 2. Update request status
            $stmt = $conn->prepare("CALL spUpdateRequestStatus(?, ?)");
            if (!$stmt) {
                throw new Exception("Failed to prepare status update");
            }
            $stmt->bind_param("is", $request_id, $status);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update request status");
            }
            $stmt->close();

            // 3. Set completion date if status is Complete/Completed
            if (strcasecmp($status, "Complete") === 0 || strcasecmp($status, "Completed") === 0) {
                $dateToday = date("Y-m-d");
                $stmt = $conn->prepare("UPDATE request_assignment SET date_finished = ? WHERE request_id = ?");
                if (!$stmt) {
                    throw new Exception("Failed to prepare completion date update");
                }
                $stmt->bind_param("si", $dateToday, $request_id);
                if (!$stmt->execute()) {
                    throw new Exception("Failed to update completion date");
                }
                $stmt->close();
            }

            // 4. First, delete existing personnel assignments
            $stmt = $conn->prepare("DELETE FROM request_assigned_personnel WHERE request_id = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare delete existing personnel");
            }
            $stmt->bind_param("i", $request_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete existing personnel assignments");
            }
            $stmt->close();

            // 5. Assign new personnel
            $stmt = $conn->prepare("INSERT INTO request_assigned_personnel (request_id, staff_id) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception("Failed to prepare personnel assignment");
            }

            $personnelAssigned = false;
            foreach ($assignedPersonnel as $staffId) {
                if ($staffId) {
                    $staffId = intval($staffId);
                    $stmt->bind_param("ii", $request_id, $staffId);
                    if ($stmt->execute()) {
                        $personnelAssigned = true;
                    } else {
                        throw new Exception("Failed to assign personnel with ID: " . $staffId);
                    }
                }
            }
            $stmt->close();

            if (!$personnelAssigned) {
                throw new Exception("No personnel were successfully assigned");
            }

            $conn->commit();
            error_log("Transaction committed successfully");
            
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Request assignment updated successfully!'
                }).then((result) => {
                    if (result.isConfirmed || result.isDismissed) {
                        window.location.href = 'requests.php';
                    }
                });
            </script>";
            exit;
            
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error in transaction: " . $e->getMessage());
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '" . htmlspecialchars($e->getMessage()) . "'
                });
            </script>";
            exit;
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Incomplete or invalid form data.'
            });
        </script>";
        exit;
    }
}


$conn->close();
?>
    