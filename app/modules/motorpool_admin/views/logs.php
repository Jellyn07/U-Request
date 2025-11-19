<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: /app/modules/shared/views/admin_login.php");
    exit;
}
require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/ActivityLogsController.php';
$controller = new ActivityLogsController();

// Default filters
$role = $_GET['table'] ?? 'motorpool';
$tableFilter = $_GET['table'] ?? 'all';
$actionFilter = $_GET['action'] ?? 'all';
$dateFilter = $_GET['date'] ?? 'all';
$profile = $controller->getProfile($_SESSION['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Activity Logs</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/admin-user.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/motorpool_menu.php'; ?>
  
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Activity Logs</h1>

      <!-- Removed showDetails logic -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- Left Section - FULL WIDTH -->
        <div class="col-span-3">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">

            <input type="text" id="search" placeholder="Search Activities" class="flex-1 min-w-[200px] input-field">

            <form method="GET" id="filterForm">
              <select name="table" onchange="document.getElementById('filterForm').submit()" class="input-field">
                <option value="all" <?= $tableFilter==='all'?'selected':'' ?>>All</option>
                <option value="driver" <?= $tableFilter==='driver'?'selected':'' ?>>Driver</option>
                <option value="vehicle" <?= $tableFilter==='vehicle'?'selected':'' ?>>Vehicle</option>
                <option value="vehicle request" <?= $tableFilter==='vehicle request'?'selected':'' ?>>Vehicle Request</option>
                <option value="vehicle request assignment" <?= $tableFilter==='vehicle request assignment'?'selected':'' ?>>Vehicle Request Assignment</option>
              </select>

              <select name="action" onchange="document.getElementById('filterForm').submit()" class="input-field">
                <option value="all" <?= $actionFilter==='all'?'selected':'' ?>>All Activity Type</option>
                <option value="INSERT" <?= $actionFilter==='INSERT'?'selected':'' ?>>Inserted</option>
                <option value="UPDATE" <?= $actionFilter==='UPDATE'?'selected':'' ?>>Updated</option>
                <option value="DELETE" <?= $actionFilter==='DELETE'?'selected':'' ?>>Deleted</option>
              </select>

              <select name="date" onchange="document.getElementById('filterForm').submit()" class="input-field">
                <option value="all" <?= $dateFilter==='all'?'selected':'' ?>>All Dates</option>
                <option value="today" <?= $dateFilter==='today'?'selected':'' ?>>Today</option>
                <option value="yesterday" <?= $dateFilter==='yesterday'?'selected':'' ?>>Yesterday</option>
                <option value="7" <?= $dateFilter==='7'?'selected':'' ?>>Last 7 days</option>
                <option value="14" <?= $dateFilter==='14'?'selected':'' ?>>Last 14 days</option>
                <option value="30" <?= $dateFilter==='30'?'selected':'' ?>>Last 30 days</option>
              </select>
            </form>

            <img id="logo" src="/public/assets/img/usep.png" class="hidden">
            <button title="Export" id="export" class="btn-upper">
              <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto h-[578px] overflow-y-auto rounded-b-lg shadow bg-white">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
              <thead class="bg-white sticky top-0">
                <tr>
                  <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Performed By</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Details</th>
                </tr>
              </thead>
              <tbody id="table" class="text-xs">
                <?= $controller->renderLogs($tableFilter, $actionFilter, $dateFilter) ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </main>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/search.js"></script>
<script src="/public/assets/js/shared/export.js"></script>
</html>
