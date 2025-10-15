<?php
session_start();
// if (!isset($_SESSION['email'])) {
//     header("Location: admin_login.php");
//     exit;
// }
// require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/DashboardController.php';

$controller = new DashboardController();
$year = $_GET['year'] ?? date('Y');
$data = $controller->getDashboardData($year);
if (!isset($_SESSION['email'])) {
    header("Location: modules/shared/views/admin_login.php");
    exit;
}

// ✅ Fetch profile here
$profile = $controller->getProfile($_SESSION['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>U-Request | Dashboard</title>
  <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
  <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-100">
  <?php include COMPONENTS_PATH . '/motorpool_menu.php';?>
        <!-- include COMPONENTS_PATH . '/admin_header.php'; -->

<main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <!-- <p class="flex text-sm text-gray-600 p-4">
      <img src="/public/assets/img/upper_logo.png" class="size-5 m-0.5">
       > Dashboard
    </p> -->
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
      <!-- Year Selector -->
      <div class="mb-2 flex items-center">
        <label for="yearSelect" class="mr-2 text-text text-sm">Select Year:</label>
        <select id="yearSelect" class="btn btn-secondary text-sm px-10">
          <option value="2025">2025</option>
          <option value="2024">2024</option>
          <option value="2023">2023</option>
        </select>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4">
        <div class="bg-white shadow rounded-lg p-4 text-center">
          <h2 class="text-gray-600 text-xs">Pending Requests</h2>
          <p class="text-xl font-bold text-primary" id="total_pending">
            <?= isset($data['summary']['total_pending']) ? $data['summary']['total_pending'] : 0 ?>
          </p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 text-center">
          <h2 class="text-gray-600 text-xs">Total Vehicle Requests</h2>
          <p class="text-xl font-bold text-primary" id="totalrRequests">
              <?= isset($data['summary']['total_vrequests']) ? $data['summary']['total_vrequests'] : 0 ?>
          </p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 text-center">
          <h2 class="text-gray-600 text-xs">Drivers</h2>
          <p class="text-xl font-bold text-green-500" id="totalgPersonnel">
            <?= isset($data['summary']['totalgPersonnel']) ? $data['summary']['totalgPersonnel'] : 0 ?>
          </p>
        </div>
        <div class="bg-white shadow rounded-lg p-4 text-center">
          <h2 class="text-gray-600 text-xs">Users</h2>
          <p class="text-xl font-bold text-secondary">
            <?= isset($data['summary']['total_user']) ? $data['summary']['total_user'] : 0 ?>
          </p>
        </div>
      </div>

      <!-- Line Graph with Title -->
      <div class="bg-white shadow rounded-lg p-10 flex flex-col justify-center items-center" style="height: 490px;">
        <h2 class="text-sm font-semibold mb-2">Monthly Requests Overview</h2>
        <canvas id="requestsChart" class="w-full h-full"></canvas>
      </div>      
    </div>

    
  </main>

  <script>
    // Dummy data for different years
    const yearData = {
      2025: {
        vehicle: [120,180,250,220,300,280,350,400,370,390,420,450],
        total: 1245,
        pending: 312,
        approved: 890
      },
      2024: {
        vehicle: [100,150,200,180,220,250,300,320,310,330,340,360],
        total: 1050,
        pending: 280,
        approved: 770
      },
      2023: {
        vehicle: [90,120,160,150,180,200,220,240,230,250,260,280],
        total: 900,
        pending: 200,
        approved: 700
      }
    };

    // Initialize chart
    const ctx = document.getElementById('requestsChart').getContext('2d');
    let requestsChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [
          {
            label: 'Vehicle Requests',
            data: yearData[2025].vehicle,
            borderColor: '#ff0000',
            backgroundColor: 'rgba(117,0,0,0.2)',
            fill: true,
            tension: 0.4
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false, // respects container height
        plugins: { legend: { labels: { color: '#333', font: { size: 10 } } } },
        scales: {
          x: { ticks: { color: '#555', font: { size: 9 } } },
          y: { ticks: { color: '#555', font: { size: 9 } } }
        }
      }
    });

    // Update chart and stats on year change
    document.getElementById('yearSelect').addEventListener('change', function() {
      const selectedYear = this.value;
      requestsChart.data.datasets[0].data = yearData[selectedYear].vehicle;
      requestsChart.data.datasets[1].data = yearData[selectedYear].vehicle;
      requestsChart.update();

      document.getElementById('totalRequests').textContent = yearData[selectedYear].total;
      document.getElementById('pendingRequests').textContent = yearData[selectedYear].pending;
      document.getElementById('approvedRequests').textContent = yearData[selectedYear].approved;
    });
  </script>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
</html>
