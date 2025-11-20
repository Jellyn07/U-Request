<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: /app/modules/shared/views/admin_login.php");
    exit;
}
require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/DashboardController.php';
require_once __DIR__ . '/../../../controllers/RequestController.php';
$con = new RequestController();
$data = $con->indexVehicle();
$requests = $data['requests'];

$c = new RequestController();
$d = $c->index();
$rrequests = $d['requests'];

$controller = new DashboardController();
$year = $_GET['year'] ?? date('Y');
$data = $controller->getDashboardData($year);
// ✅ Date range display (example)
$startDate = "Jan 1";
$endDate = date('M d');
$dateRange = "$startDate - $endDate";
$profile = $controller->getProfile($_SESSION['email']);
if (!isset($_SESSION['email'])) {
    header("Location: modules/shared/views/admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Superadmin Dashboard</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <!-- 📅 Date Display -->
    <div class="absolute top-7 right-8 bg-white p-2 px-4 rounded-xl shadow border border-gray-300 text-sm">
      Showing stats from <span class="font-semibold"><?= $dateRange ?></span>
    </div>
    <div class="p-6 space-y-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

      <!-- Summary Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-5 bg-white p-6 rounded-2xl shadow">
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Overall Requests</h2>
          <p class="text-4xl font-bold text-text mt-2">
            <?= isset($data['summary']['total_requests']) ? $data['summary']['total_requests'] : 0 ?>
          </p>
          <p class="text-xs text-gray-500 font-medium mt-2">Overall request this year</p>
        </div>
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Pending Vehicle Requests</h2>
          <p class="text-4xl font-bold text-text mt-2">
            <?= isset($data['summary']['total_vrequests_p']) ? $data['summary']['total_vrequests_p'] : 0 ?>
          </p>
          <p class="text-xs text-gray-500 font-medium mt-2">Total pending vehicle requests</p>
        </div>
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Pending Repair Requests</h2>
          <p class="text-4xl font-bold text-text mt-2">
            <?= isset($data['summary']['total_pending']) ? $data['summary']['total_pending'] : 0 ?>
          </p>
          <p class="text-xs text-gray-500 font-medium mt-2">Total pending repair requests</p>
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
       <div class="bg-white p-4 rounded-2xl shadow">
          <h3 class="font-semibold text-text text-base text-center mb-2">Monthly Request Overview</h3>
          <div class="w-full h-64 flex justify-center">
            <canvas id="monthlyLineChart"></canvas>
          </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow">
          <h3 class="font-semibold text-text text-base text-center mb-2">Request Distribution</h3>
          <div class="w-full h-64 flex justify-center">
            <canvas id="requestTypeChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Activities -->
      <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Vehicle Requests -->
        <div class="bg-white rounded-2xl shadow-md pt-5 pb-0">
          <h2 class="text-lg font-bold mb-3 pl-5 text-primary">Recent Vehicle Requests</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-left">
              <thead class="text-xs uppercase text-gray-700 border-b-gray-400 border-b">
                <tr>
                  <th class="px-4 py-2">Date</th>
                  <th class="px-4 py-2">Requester</th>
                  <th class="px-4 py-2">Vehicle</th>
                  <th class="px-6 py-2">Status</th>
                </tr>
              </thead>
              <tbody id="requestsTable" class="text-sm">
                <?php if (!empty($requests)): ?>
                  <?php foreach ($requests as $row): ?>
                    <tr 
                      class="border-b hover:bg-gray-100 cursor-pointer text-xs">
                      <td class="px-4 py-3"><?= htmlspecialchars(date('M d, Y', strtotime($row['date_request']))) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['requester_name']) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['vehicle_name'] ?? 'N/A') ?></td>
                      <td class="px-4 py-3">
                        <?php if ($row['req_status'] === 'Completed'): ?>
                            <!-- ✅ Show label only when Completed -->
                            <span class="px-8 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Completed
                            </span>
                        <?php elseif ($row['req_status'] === 'Pending'): ?>
                            <!-- ✅ Show label only when Pending -->
                            <span class="px-10 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        <?php elseif ($row['req_status'] === 'Approved'): ?>
                            <!-- ✅ Show label only when Approved -->
                            <span class="px-9 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                Approved
                            </span>     
                        <?php elseif ($row['req_status'] === 'On Going'): ?>
                            <!-- ✅ Show label only when On Going -->
                            <span class="px-9 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                On Going
                            </span>   
                        <?php elseif ($row['req_status'] === 'Rejected/Cancelled'): ?>
                            <!-- ✅ Show label only when Rejected/Cancelled -->
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                Rejected/Cancelled
                            </span>         
                        <?php else: ?>
                            <!-- Fallback for any other statuses -->
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                <?= htmlspecialchars($row['req_status']) ?>
                            </span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="6" class="text-center py-3 text-gray-400">No vehicle requests found</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Repair Requests -->
        <div class="bg-white rounded-2xl shadow-md pt-5 pb-0">
          <h2 class="text-lg font-bold mb-3 pl-5 text-primary">Recent Repair Requests</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-left">
              <thead class="text-xs uppercase text-gray-700 border-b-gray-400 border-b">
                <tr>
                  <th class="px-4 py-2">Date</th>
                  <th class="px-4 py-2">Requester</th>
                  <th class="px-4 py-2">Facility</th>
                  <th class="px-6 py-2">Status</th>
                </tr>
              </thead>
              <tbody id="requestsTable" class="text-sm">
                <?php foreach ($rrequests as $row): ?>
                    <tr 
                        data-category="<?= htmlspecialchars($row['request_Type']) ?>" 
                        data-status="<?= htmlspecialchars($row['req_status']) ?>" 
                        @click="selected = <?= htmlspecialchars(json_encode($row)) ?>; showDetails = true"
                        class="border-b hover:bg-gray-100 cursor-pointer text-xs">
                        <td class="px-4 py-3" data-date="<?= htmlspecialchars($row['request_date']) ?>">
                            <?= htmlspecialchars(date("M d, Y", strtotime($row['request_date']))) ?>
                        </td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['Name']) ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['location']) ?></td>  
                        <td class="px-4 py-3">
                            <?php if ($row['req_status'] === 'Completed'): ?>
                                <!-- ✅ Show label only when Completed -->
                                <span class="px-5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    Completed
                                </span>
                            <?php elseif ($row['req_status'] === 'To Inspect'): ?>
                                <span class="px-5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    To&nbsp;Inspect
                                </span>
                            <?php elseif (in_array($row['req_status'], ['In Progress', 'In progress'], true)): ?>
                                <span class="px-5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    To&nbsp;Inspect
                                </span>
                            <?php else: ?>
                                <!-- Fallback for any other statuses -->
                                <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                    <?= htmlspecialchars($row['req_status']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
  </main>
  <script src="/public/assets/js/shared/menus.js"></script>
  <script src="/public/assets/js/shared/stars.js"></script>
  <script src="/public/assets/js/s-dashboard.js"></script>
</body>
</html>
