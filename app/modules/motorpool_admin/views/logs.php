<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/ActivityLogsController.php';
$controller = new ActivityLogsController();

// Default filters
$role = $_GET['table'] ?? 'motorpool';
$tableFilter = $_GET['table'] ?? 'all';
$actionFilter = $_GET['action'] ?? 'all';
$dateFilter = $_GET['date'] ?? 'all';
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
  
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/motorpool_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Activity Logs</h1>

      <div x-data="{ showDetails: false, selected: {}, addmaterial: false }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="search" placeholder="Search Activities" class="flex-1 min-w-[200px] input-field">
            <form method="GET" id="filterForm">
            <select name="table" onchange="document.getElementById('filterForm').submit()" class="input-field">
              <option value="all" <?= $tableFilter==='all'?'selected':'' ?>>All</option>
              <option value="driver" <?= $tableFilter==='driver'?'selected':'' ?>>Driver</option>
              <option value="vehicle" <?= $tableFilter==='vehicle'?'selected':'' ?>>Vehicle</option>
            </select>
            <select name="action" onchange="document.getElementById('filterForm').submit()" class="input-field">
              <option value="all" <?= $actionFilter==='all'?'selected':'' ?>>All Activity Type</option>
              <option value="INSERT" <?= $actionFilter==='INSERT'?'selected':'' ?>>Insert</option>
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
            <button title="Print data in the table" class="input-field">
                <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
            </button>
            <button class="input-field" title="Export to Excel">
                <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
            <!-- Add Admin Modal -->
            <div x-data="{ showModal: false }">
          </div>
        </div>

          <!-- Table -->
          <div class="overflow-x-auto h-[578px] overflow-y-auto rounded-b-lg shadow bg-white">
          <table class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
            <thead class="bg-white sticky top-0">
              <tr>
                <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Affected Items</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Details</th>
              </tr>
            </thead>
            <tbody id="table" class="text-sm">
                 <?= $controller->renderLogs($tableFilter, $actionFilter, $dateFilter) ?>
            </tbody>
          </table>
          </div>
        </div>

        <!-- Right Section (Details) -->
        <div x-show="showDetails" x-cloak
            class="bg-white shadow rounded-lg p-4 max-h-[642px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>
          
          <!-- Form -->
          <form id="adminForm" class="space-y-1" method="post">
            <!-- <h2 class="text-lg font-bold mb-2">More Information</h2> -->

            <!-- Header -->
            <div class="flex flex-col items-center text-center mt-4">
              <img id="profile-preview"  
                :src="selected.profile_picture ? '/public/uploads/profile_pics/' + selected.profile_picture : '/public/assets/img/user-default.png'"
                alt="Profile"
                class="w-16 h-16 rounded-full object-cover shadow-md"
              />
              <h2 class="mt-1 text-base font-bold text-gray-800" x-text="selected.performed_by || 'Unknown User'"></h2>
              <p class="text-xs text-gray-500" x-text="selected.role || 'System User'"></p>
            </div>

            <div>
              <label class="text-xs text-text mb-1">Tracking No.</label>
              <input type="text" disabled class="w-full view-field cursor-not-allowed"/>
            </div>

            <div>
              <label class="text-xs text-text mb-1">User/Staff ID</label>
              <input type="text" disabled class="w-full view-field cursor-not-allowed"/>
            </div>

            <div>
              <label class="text-xs text-text mb-1">Date & Time</label>
              <input type="text" disabled class="w-full view-field cursor-not-allowed"/>
            </div>

            <div>
              <label class="text-xs text-text mb-1">Type</label>
              <input type="text" disabled class="w-full view-field cursor-not-allowed"/>
            </div>

            <!-- What page it was performed on -->
            <div>
              <label class="text-xs text-text mb-1">Page</label>
              <input type="text" disabled class="w-full view-field cursor-not-allowed"/>
            </div>

            <div>
              <label class="text-xs text-text mb-1">Description</label>
              <textarea type="text" class="w-full view-field cursor-not-allowed" rows="4" disabled></textarea>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </main>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/search.js"></script>
</html>