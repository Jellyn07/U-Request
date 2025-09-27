<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
require_once __DIR__ . '/../../../controllers/AdminController.php';

$controller = new AdminController();
$admins = $controller->getAllAdmins();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/admin-user.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/gsu_menu.php'; ?>
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
            <select class="input-field">
                <option value="all">All Users</option>
                <option>Admin</option>
                <option>Student/Staff</option>
            </select>
            <select class="input-field">
              <option value="all">Activity Type</option>
                <option>Added</option>
                <option>Updated</option>
                <option>Deleted</option>
                <option>Approved</option>
                <option>Rejected</option>
                <option>Completed</option>
            </select>
            <select class="input-field">
                <option value="all">All Dates</option>
                <option>Today</option>
                <option>Yesterday</option>
                <option>Last 7 days</option>
                <option>Last 14 days</option>
                <option>Last 30 days</option>
            </select>
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
          <div class="overflow-x-auto max-h-[580px] overflow-y-auto rounded-b-lg shadow">
          <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-b-lg p-2">
            <thead class="bg-white sticky top-0">
              <tr>
                <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Description</th>
              </tr>
            </thead>
            <tbody id="table" class="text-sm">
                <?php for($i=0; $i<20; $i++){
                    echo'
                        <tr @click="showDetails = true" class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100">
                            <td class="pl-8 py-3">Jan 07, 2025</td>
                            <td class="px-4 py-3">Juan Cruz</td>
                            <td class="px-4 py-3">Added</td>
                            <td class="px-4 py-3">Added new personnel named Tommy Lim</td>
                        </tr>                    
                    ';
                } 
                ?>
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