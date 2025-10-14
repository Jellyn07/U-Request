<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Users</title>
  <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
  <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/motorpool_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Users</h1>
      <div 
        x-data="{showDetails: false}" 
        class="grid grid-cols-1 md:grid-cols-3 gap-4"
      >
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="searchUser" placeholder="Search by name or email" class="flex-1 min-w-[200px] input-field">
            <select class="input-field">
              <option value="all">All</option>
              <option value="have_pending">Active</option>
              <option value="no_pending">Inactive</option>
            </select>
            <select id="sortUsers" class="input-field">
              <option value="az">Sort A-Z</option>
              <option value="za">Sort Z-A</option>
            </select>
            <button class="input-field">
              <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
            </button>
            <button class="input-field">
              <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto max-h-[550px] overflow-y-auto mt-4 rounded-lg shadow bg-white">
          <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg p-2">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-1 py-2 rounded-tl-lg">&nbsp;</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Email</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Office / Department</th>
              </tr>
            </thead>
            <tbody id="Table" class="text-sm">
            <?php 
              for($i = 0; $i < 15; $i++): 
                echo '<tr @click="showDetails = true;"
                      class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100">
                  <td class="px-1 py-2">
                    <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover mx-auto">
                  </td>
                  <td class="px-4 py-2">Juan Dela Cruz</td>
                  <td class="px-4 py-2">
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                  </td>
                  <td class="px-4 py-2">jdcruz@usep.edu.ph</td>
                  <td class="px-4 py-2">BSIT</td>
                </tr>';
              endfor;
            ?>
          </tbody>
          </table>
          </div>
        </div>

        <!-- Right Section (Details) -->
      <div x-show="showDetails" x-cloak class="bg-white shadow rounded-lg p-4 max-h-[630px] overflow-y-auto">
        <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
          <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
        </button>

        <h2 class="text-lg font-bold mb-2">User Information</h2>

        <!-- Profile Picture -->
        <img id="profile-preview" src="/public/assets/img/user-default.png" alt="Profile" class="w-36 h-36 rounded-full object-cover shadow-sm mx-auto mb-4" />

        <!-- Form -->
        <form id="userForm" class="space-y-5" method="post" action="../../../controllers/AdminController.php">
          <input type="hidden" name="requester_email" :value="selected.email || ''">
          <input type="hidden" name="update_user" value="1">

          <div>
            <label class="text-sm text-text mb-1">USeP Email</label>
            <input type="email" disabled class="w-full view-field cursor-not-allowed"/>
          </div>

          <div>
            <label class="text-sm text-text mb-1">Student/Staff ID No.</label>
            <input type="text" name="requester_id" class="w-full view-field cursor-not-allowed"/>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm text-text mb-1">First Name</label>
              <input type="text" name="firstName"class="w-full view-field cursor-not-allowed"/>
            </div>
            <div>
              <label class="text-sm text-text mb-1">Last Name</label>
              <input type="text" name="lastName" class="w-full view-field cursor-not-allowed"/>
            </div>
          </div>

          <div>
            <label class="text-sm text-text mb-1">Program/Office</label>
            <input type="text" name="dept" class="w-full view-field cursor-not-allowed"/>
          </div>


          <div class="flex justify-center gap-2">
            <button type="button" class="btn btn-secondary historyBtn flex items-center gap-2">
              <img src="/public/assets/img/work-history.png" class="size-4" alt="Request history">
              <p class="text-sm">Request History</p>
            </button>
          </div>
        </form>
      </div>
      </div>
    </div>
  </div>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
</html>
