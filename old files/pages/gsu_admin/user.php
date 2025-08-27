<?php include 'auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>GSU System</title>
    <link rel="icon" href="../../assets/icon/logo.png" type="icon">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-menu.css">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-global.css">
    <link rel="stylesheet" type="text/css" href="../../css/GSUAdmin/user.css">
    <style>
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        .history-table th, .history-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .history-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .history-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .history-table tr:hover {
            background-color: #f0f0f0;
        }

        #modalHistory {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body>
    <div id="admin-menu"></div>
    <script src="../../js/admin-menu.js"></script>

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
        } 
        // elseif ($filterOption === 'student') {
        //     $sql .= " AND officeOrDept LIKE '%Student%'";
        // } elseif ($filterOption === 'staff') {
        //     $sql .= " AND officeOrDept NOT LIKE '%Student%'";
        // }

        // Add sorting
        if ($sortOption === 'az') {
            $sql .= " ORDER BY full_name ASC";
        } elseif ($sortOption === 'za') {
            $sql .= " ORDER BY full_name DESC";
        } else {
            $sql .= " ORDER BY requester_id ASC";
        }

        // For debugging
        error_log("SQL Query: " . $sql);
        error_log("Filter Option: " . $filterOption);
        error_log("Sort Option: " . $sortOption);
        error_log("Search Term: " . $searchTerm);

        $result = $conn->query($sql);

        if (!$result) {
            die("Query failed: " . $conn->error);
        }
        ?>

    <div class="main">
        <p class="type">Users Account</p>
        <div class="toolbar">
            <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;" id="searchForm">
                <input type="search" placeholder="Search User" name="search" id="gsusearch" value="<?php echo htmlspecialchars($searchTerm); ?>" oninput="handleRealTimeSearch()">
                <select class="filter" name="filter" id="filterSelect" onchange="handleRealTimeSearch()">
                    <option value="" <?php echo empty($filterOption) ? 'selected' : ''; ?>>All</option>
                    <option value="have_pending" <?php echo $filterOption === 'have_pending' ? 'selected' : ''; ?>>Have Pending</option>
                    <option value="no_pending" <?php echo $filterOption === 'no_pending' ? 'selected' : ''; ?>>No Pending</option>
                    <!-- <option value="student" <?php echo $filterOption === 'student' ? 'selected' : ''; ?>>Student</option>
                    <option value="staff" <?php echo $filterOption === 'staff' ? 'selected' : ''; ?>>Staff</option> -->
                </select>
                <select class="sorting" name="sort" id="sortSelect" onchange="handleRealTimeSearch()">
                    <option value="id" <?php echo empty($sortOption) || $sortOption === 'id' ? 'selected' : ''; ?>>Sort by ID</option>
                    <option value="az" <?php echo $sortOption === 'az' ? 'selected' : ''; ?>>Sort A-Z</option>
                    <option value="za" <?php echo $sortOption === 'za' ? 'selected' : ''; ?>>Sort Z-A</option>
                </select>
            </form>
            <button onclick="printSection('gsupersonnel')" class="print"><img src="../../assets/icon/printing.png">&nbsp;Print</button>
        </div>

        <div id="gsupersonnel" class="gsupersonnel">
            <div class="tablegsu">
                <table>
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Name</td>
                            <td>Email</td>
                            <td>Department/Office</td>
                            <td>Status</td>
                            <td class="no-print">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
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
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

            <!-- User Detail Modal -->
        <div id="userModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
            <div style="background:white; width:30vw; height: 70vh; margin:5vw auto; padding:2vw; border-radius:0.5vw; overflow-y: auto;">
                <h2>User Details</h2> <button onclick="closeModal()" id="moreExit"><img src="../../assets/icon/exit.png" alt="More" width="26"></button>
                <form method="POST" id="deleteForm">
                <input type="hidden" name="delete_id" id="deleteId">
                <p><strong>ID#:</strong> <span id="modalId"></span></p></form>
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                <p><strong>Department/Office:</strong> <span id="modalDept"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>

                <!-- Example History -->
                <h3>Request History</h3>
                <div id="modalHistory">
                </div>

                <div style="margin-top: 5px;">
                    <div class="button-group">
                        <button type="button" class="cancel-btn" onclick="closeModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
    const modal1 = document.getElementById('userModal');
    const historyDiv = document.getElementById('modalHistory');

    let searchTimeout;

    function handleRealTimeSearch() {
        clearTimeout(searchTimeout);
        
        // Set a timeout to prevent too many requests while typing
        searchTimeout = setTimeout(() => {
            const searchTerm = document.getElementById('gsusearch').value;
            const filterValue = document.getElementById('filterSelect').value;
            const sortValue = document.getElementById('sortSelect').value;
            
            // Create URL with search parameters
            const url = `get-users.php?search=${encodeURIComponent(searchTerm)}&filter=${encodeURIComponent(filterValue)}&sort=${encodeURIComponent(sortValue)}`;
            
            // Fetch updated results
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    document.querySelector('.tablegsu table tbody').innerHTML = html;
                    // Reattach event listeners to new view buttons
                    attachViewButtonListeners();
                })
                .catch(error => console.error('Error:', error));
        }, 300); // 300ms delay
    }

    function attachViewButtonListeners() {
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const requesterId = btn.dataset.id;
                document.getElementById('modalId').textContent = requesterId;
                document.getElementById('deleteId').value = requesterId;
                document.getElementById('modalName').textContent = btn.dataset.name;
                document.getElementById('modalEmail').textContent = btn.dataset.email;
                document.getElementById('modalDept').textContent = btn.dataset.officeordep;
                document.getElementById('modalStatus').textContent = btn.dataset.status;

                // Fetch request history for this user
                fetch(`get-user-history.php?requester_id=${requesterId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(response => {
                        const historyDiv = document.getElementById('modalHistory');
                        if (!response.success) {
                            throw new Error(response.error || 'Unknown error occurred');
                        }

                        const history = response.data;
                        if (history.length === 0) {
                            historyDiv.innerHTML = '<p>No request history found.</p>';
                        } else {
                            const historyTable = `
                                <table class="history-table">
                                    <tr>
                                        <th>Request ID</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>Date Requested</th>
                                        <th>Status</th>
                                        <th>Date Finished</th>
                                    </tr>
                                    ${history.map(req => `
                                        <tr>
                                            <td>${req.request_id}</td>
                                            <td>${req.request_Type}</td>
                                            <td>${req.location}</td>
                                            <td>${req.request_date}</td>
                                            <td>${req.req_status}</td>
                                            <td>${req.date_finished || '-'}</td>
                                        </tr>
                                    `).join('')}
                                </table>
                            `;
                            historyDiv.innerHTML = historyTable;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching history:', error);
                        document.getElementById('modalHistory').innerHTML = `
                            <div class="error-message" style="color: red; padding: 10px;">
                                Error loading request history: ${error.message}
                            </div>`;
                    });

                modal1.style.display = 'block';
            });
        });
    }

    function closeModal() {
        modal1.style.display = 'none';
    }

    function printSection(sectionId) {
            const headerHtml = `
                <div style="text-align:center;">
                    <img src="../../assets/icon/usep.png" style="height:80px;"><br>
                    <h2 class="print-header">University of Southeastern Philippines</h2>
                    <h3>General Services Unit - User Accounts Report</h3>
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

            const originalTable = document.querySelector(".tablegsu table");
            const tableHead = originalTable.querySelector("tr");
            const tableRows = Array.from(originalTable.querySelectorAll("tr")).slice(1);

            // Remove the "Actions" column from the header
            const headerRow = tableHead.cloneNode(true);
            const headerCells = Array.from(headerRow.querySelectorAll("td"));
            if (headerCells.length > 0) {
                headerCells[headerCells.length - 1].remove(); // Remove last column (Actions)
            }

            // Clone rows and remove "Actions" column from each row
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

        // Call on initial page load
        window.addEventListener('DOMContentLoaded', () => {
            attachViewButtonListeners();
        });

</script>



