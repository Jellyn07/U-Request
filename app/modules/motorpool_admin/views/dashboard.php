<?php
session_start();
// if (!isset($_SESSION['email'])) {
//     header("Location: admin_login.php");
//     exit;
// }
// require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/DashboardController.php';
require_once __DIR__ . '/../../../controllers/RequestController.php';
$con = new RequestController();
$data = $con->indexVehicle();
$requests = $data['requests'];

$controller = new DashboardController();
$year = $_GET['year'] ?? date('Y');
$data = $controller->getDashboardData($year);
if (!isset($_SESSION['email'])) {
    header("Location: modules/shared/views/admin_login.php");
    exit;
}

// ✅ Fetch profile here
$profile = $controller->getProfile($_SESSION['email']);

// ✅ Date range display (example)
$startDate = "Jan 1";
$endDate = date('M d');
$dateRange = "$startDate - $endDate";
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
    <!-- 📅 Date Display -->
    <div class="absolute top-7 right-8 bg-white p-2 px-4 rounded-xl shadow border border-gray-300 text-sm">
      Showing stats from <span class="font-semibold"><?= $dateRange ?></span>
    </div>
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

      <!-- Summary Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-5 bg-white p-6 rounded-2xl shadow">
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Pending Requests</h2>
          <p class="text-4xl font-bold text-text mt-2">
            <?= isset($data['summary']['total_vrequests_p']) ? $data['summary']['total_vrequests_p'] : 0 ?>
          </p>
          <p class="text-xs text-gray-500 font-medium mt-2">Pending request today</p>
        </div>
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Total Request</h2>
          <p class="text-4xl font-bold text-text mt-2">
            <?= isset($data['summary']['total_vrequests']) ? $data['summary']['total_vrequests'] : 0 ?>
          </p>
          <p class="text-xs text-gray-500 font-medium mt-2">Total request this year</p>
        </div>
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Drivers</h2>
          <p class="text-4xl font-bold text-text mt-2">
            <?= isset($data['summary']['totalDrivers']) ? $data['summary']['totalDrivers'] : 0 ?>
          </p>
          <p class="text-xs text-gray-500 font-medium mt-2">Total number of Drivers this year</p>
        </div>
        <div>
          <h2 class="font-medium mb-3">Average Rating</h2>
          <div class="flex items-center space-x-2 mt-2">
            <span class="text-4xl font-bold text-yellow-500">4.5</span>
            <div id="averageStars" class="flex"></div>
          </div>
          <p class="text-xs text-gray-500 font-medium mt-2">Average rating this year</p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid md:grid-cols-2 gap-6 mb-5">
       <div class="bg-white p-4 rounded-2xl shadow mb-6">
          <h3 class="font-semibold text-text text-base text-center mb-2">Request Status</h3>
          <div class="w-full h-80 flex justify-center">
            <canvas id="requestStatusChart"></canvas>
          </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow">
          <h3 class="font-semibold text-text text-base text-center mb-2">Vehicle Usage</h3>
          <div class="w-full h-80 flex justify-center">
            <canvas id="vehicleUsageChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Requests -->
      <div class="flex justify-between bg-white p-4 pb-1 rounded-t-2xl shadow">
        <h3 class="text-xl font-bold text-primary mb-1 order-1">Recent Requests</h3>
        <input type="text" id="searchRequests" placeholder="Search by Requester Name" class="flex-right min-w-[300px] input-field order-2">
      </div>
      <table class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
        <thead class="bg-white sticky top-0">
            <tr>
              <th class="pl-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tracking ID</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Requester</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Travel Location</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Request</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Status</th>
                </tr>
              </thead>
              <tbody id="requestsTable" class="text-sm">
                <?php if (!empty($requests)): ?>
                  <?php foreach ($requests as $row): ?>
                    <tr 
                      class="border-b hover:bg-gray-100 cursor-pointer"
                      data-status="<?= htmlspecialchars($row['req_status']) ?>"
                      data-date="<?= htmlspecialchars(date('Y-m-d', strtotime($row['date_request']))) ?>"
                      @click="selectRow({
                        control_no: '<?= htmlspecialchars($row['control_no']) ?>',
                        tracking_id: '<?= htmlspecialchars($row['tracking_id']) ?>',
                        requester_name: '<?= htmlspecialchars($row['requester_name']) ?>',
                        travel_destination: '<?= htmlspecialchars($row['travel_destination']) ?>',
                        date_request: '<?= htmlspecialchars(date('M d, Y', strtotime($row['date_request']))) ?>',
                        travel_date: '<?= htmlspecialchars(date('M d, Y', strtotime($row['travel_date']))) ?>',
                        trip_purpose: '<?= htmlspecialchars($row['trip_purpose']) ?>',
                        req_status: '<?= htmlspecialchars($row['req_status']) ?>'
                      })"
                    >
                      <td class="px-4 py-3"><?= htmlspecialchars($row['tracking_id']) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['requester_name']) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars(date('M d, Y', strtotime($row['travel_date']))) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['travel_destination']) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars(date('M d, Y', strtotime($row['date_request']))) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['req_status']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="6" class="text-center py-3 text-gray-400">No vehicle requests found</td></tr>
                <?php endif; ?>
          </tbody>
       </table>
    </div>
    <script>
      document.getElementById('searchRequests').addEventListener('input', function() {
          const filter = this.value.toLowerCase();
          const rows = document.querySelectorAll('#requestsTable tr'); // use correct ID
          
          rows.forEach(row => {
              const cells = Array.from(row.children);
              const match = cells.some(cell => cell.textContent.toLowerCase().includes(filter));
              row.style.display = match ? '' : 'none';
          });
      });
      </script>
  </main>
</body>
<script src="/public/assets/js/motorpool_admin/dashboard-charts.js"></script>
<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/stars.js"></script>
</html>
