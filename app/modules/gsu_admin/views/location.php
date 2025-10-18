<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
require_once __DIR__ . '/../../../controllers/AdminController.php';

$controller = new AdminController();
$admins = $controller->getAllAdmins();

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
      <h1 class="text-2xl font-bold mb-4">Campus Locations</h1>

      <div x-data="{ showDetails: false, selected: {} }" class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- Left Section (Table) -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <input type="text" id="search" placeholder="Search Location" class="flex-1 min-w-[200px] input-field">
            <select class="input-field">
                <option value="all">All Unit</option>
                <option>Tagum Unit</option>
                <option>Mabini Unit</option>
            </select>
            <button title="Print data in the table" class="input-field">
                <img src="/public/assets/img/printer.png" alt="Print" class="size-4 my-0.5">
            </button>
            <button class="input-field" title="Export to Excel">
                <img src="/public/assets/img/export.png" alt="Export" class="size-4 my-0.5">
            </button>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto max-h-[578px] overflow-y-auto rounded-b-lg shadow bg-white">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
              <thead class="bg-white sticky top-0">
                <tr>
                  <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Added</th>
                  <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Building</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Exact Location</th>
                </tr>
              </thead>
              <tbody id="table" class="text-sm">
                <?php for($i=0; $i<20; $i++): ?>
                  <tr class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100" 
                      @click="showDetails = true; selected = { 
                          dateAdded: 'Jan 07, 2025', 
                          unit: 'Tagum Unit', 
                          building: 'PECC', 
                          location: 'Clinic' 
                      }">
                    <td class="pl-8 py-3">Jan 07, 2025</td>
                    <td class="px-4 py-3">Tagum Unit</td>
                    <td class="px-4 py-3">PECC</td>
                    <td class="px-4 py-3">Clinic</td>
                  </tr>
                <?php endfor; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Right Section (Details Panel) -->
        <div x-show="showDetails" x-cloak class="bg-white shadow rounded-lg p-4 max-h-[640px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>

          <h2 class="text-lg font-bold mb-2">Edit Location</h2>
          <form class="space-y-5" method="post">
            <div>
              <label class="text-sm text-text mb-1">Date Added</label>
              <input class="w-full view-field cursor-not-allowed" disabled :value="selected.dateAdded">
            </div>

            <div>
              <label class="text-sm text-text mb-1">Unit</label>
              <select class="w-full input-field" x-model="selected.unit">
                <option>Tagum Unit</option>
                <option>Mabini Unit</option>
              </select>
            </div>

            <div>
              <label class="text-sm text-text mb-1">Building</label>
              <input class="w-full input-field" :value="selected.building">
            </div>

            <div>
              <label class="text-sm text-text mb-1">Exact Location</label>
              <input class="w-full input-field" :value="selected.location">
            </div>

            <div class="flex justify-center gap-2">
              <button type="button" class="btn btn-secondary historyBtn flex items-center gap-2">
              <img src="/public/assets/img/delete.png" class="size-4">
            </button>
              <button type="submit" name="update_location" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </main>
</body>

<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/search.js"></script>
