<?php include 'auth-check.php'; ?>

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "utrms_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to count requests
$sql = "SELECT COUNT(*) AS total FROM request";
$result = mysqli_query($conn, $sql);

// Fetch the count
$row = mysqli_fetch_assoc($result);
$requestCount = $row['total'];

$sql1 = "SELECT COUNT(*) AS totalpersonnel FROM gsu_personnel";
$result = mysqli_query($conn, $sql1);

// Fetch the count
$row = mysqli_fetch_assoc($result);
$countPersonnel = $row['totalpersonnel'];

$sql2 = "SELECT COUNT(*) AS totalmaterials FROM materials";
$result = mysqli_query($conn, $sql2);

// Fetch the count
$row = mysqli_fetch_assoc($result);
$countMaterials = $row['totalmaterials'];

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>GSU System</title>
    <link rel="icon" href="../../assets/icon/logo.png" type="icon">
    <link rel="stylesheet" type="text/css" href="../../css/GSUAdmin/dashboard.css">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-menu.css">
    <link rel="stylesheet" type="text/css" href="../../css/shared/admin-global.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div id="admin-menu"></div>
    <script src="../../js/admin-menu.js"></script>
    <p class="type">DASHBOARD</p>
    <div class="main">
        <div class="dashi1">
            <p class="dashi" id="gsup">GSU PERSONNELS</p>
            <div class="desc"> 
                <img src="../../assets/icon/employees.png"> 
                <div class="count">
                <p>OVERALL</p>
                <h3><?php echo $countPersonnel; ?></h3>
                </div>  
            </div>
        </div>

        <div class="dashi1">
            <p class="dashi">REQUESTS</p>
            <div class="desc" id="req" >
                <img src="../../assets/icon/request-form.png">
                <div class="count">
                <p>OVERALL</p>
                <h3><?php echo $requestCount; ?></h3>
                </div>
            </div>
        </div>

        <div class="dashi1">
            <p class="dashi">INVENTORY</p>
            <div class="desc" id="inv"> 
                <img src="../../assets/icon/inventory-management.png">
                <div class="count1">
                <p>AVAILABLE MATERIALS</p>
                <h3><?php echo $countMaterials; ?></h3>
                </div>
            </div>
        </div>
        <br><br>
    </div>

    <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "utrms_db");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Prepare search query if search term is provided
        $searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

        // Query to fetch data from the view with optional search filter
        $sql = "SELECT DISTINCT(request_id), full_name, request_Type, location, req_status 
                FROM vw_dashboardRequest 
                WHERE 
                    (req_status = 'To Inspect' OR req_status = 'In Progress') AND 
                    CONCAT(request_id, full_name, request_Type, location, req_status) LIKE '%$searchTerm%' 
                ORDER BY request_id DESC;
                ";
        $result = mysqli_query($conn, $sql);
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
    ?>

<div class="recentrequests">
    <div class="rrhead">
        <label id="recent-title">RECENT REQUESTS</label>
        <div class="rr-controls">
            <form method="GET">
            <input type="search" placeholder="Search" name="search" id="reqsearch" value="<?php echo htmlspecialchars($searchTerm); ?>"></form>
            <!-- <button onclick="printSection('tablereq')" class="print"><img src="../../assets/icon/printing.png" alt="Printing">Print</button> -->
            <!-- <button id="printButton" class="print" type="button" onclick="printSection()"><img src="../../assets/icon/printing.png" alt="Printing">Print</button> -->
            <button id="printButton" class="print" type="button" onclick="printSection()">
                <img src="../../assets/icon/printing.png" alt="Printing">Print
            </button>
            <form method="post"><button class="refresh" name="refresh"><img src="../../assets/icon/refresh.png" alt="Refresh">Refresh</button></form>
            <?php
                $conn = new mysqli("localhost", "root", "", "utrms_db");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if(isset($_POST['refresh'])){
                $sql = "SELECT * FROM vw_dashboardRequest where req_status = 'In Progress' OR req_status ='To Inspect' ORDER BY request_id DESC";
                $result = $conn->query($sql); 
                }
            ?>
        </div>
    </div>
    <div id="tablereq">
        <table>
            <tr id="table_dashboard">
                <td>REQUEST ID</td>
                <td>NAME</td>
                <td>REQUEST TYPE</td>
                <td>LOCATION</td>
                <td>STATUS</td>
            </tr>
            <?php
            // Loop through and display the results in table rows
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['request_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['request_Type']) . '</td>';
                echo '<td>' . htmlspecialchars($row['location']) . '</td>';
                $status = htmlspecialchars($row['req_status']);
                $statusClass = strtolower(str_replace(' ', '-', $status)); // converts to "to-inspect", "in-progress", etc.
                echo '<td class="status ' . $statusClass . '">' . $status . '</td>';                
                echo '</tr>';
            }
            ?>
        </table>
    </div>
            <!-- <img src="../../assets/icon/usep.png" style="height:100px;"> -->

</div>
<script>
    function printSection() {
    const headerHtml = `
        <div style="text-align:center;">
        <img src="../../assets/icon/usep.png" style="height:90px;"><br>
        <h2 class="print-header">University of Southeastern Philippines</h2>
        <h3>General Services Unit - Recent Requests Report</h3>
        <p>Printed on ${new Date().toLocaleString()}</p>
        </div>
    `;

    const content = document.getElementById("tablereq").outerHTML;

    const footerHtml = `
        <img src="../../assets/icon/footer.png" alt="Footer Logo" id="footer">
    `;

    const style = `
        <style>
        body { 
            font-family: Arial, sans-serif; padding: 20px; margin:0;
        }
        table { 
            width: 100%; border-collapse: collapse; margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #000; padding: 8px; text-align: left; 
        }
        h3 { 
            font-size:16px;margin: 5px 0; 
        }
        .to-inspect { 
            background-color: #ffeb99; 
        }
        .in-progress { 
            background-color: #add8e6; 
        }
        .print-header {
            font-family: 'Old English Text MT', cursive;
            font-size:25px;
            margin-bottom:10px;
        }
        #footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
        }
        </style>
    `;

    const printWindow = window.open('', '_blank', 'width=900,height=700');

    printWindow.document.open();
    printWindow.document.write('<html><head>' + style + '</head><body>');
    printWindow.document.write(headerHtml);
    printWindow.document.write(content);
    printWindow.document.write(footerHtml);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function () {
        setTimeout(() => {
        printWindow.focus();
        printWindow.print();
        }, 500);
    };
    }



    document.getElementById("refreshButton").addEventListener("click", function() {
    location.reload();
    });
</script>
</body>    
</html>
<?php
// Close the connection
mysqli_close($conn);
?>
