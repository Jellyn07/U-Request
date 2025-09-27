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
  <link rel="icon" href="/public/assets/img/upper_logo.png" />
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
      <h1 class="text-2xl font-bold mb-4">Documents</h1>
      <div x-data="{ openRow: null }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div class="col-span-3 transition-all duration-300">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="search" placeholder="Search by name" class="flex-1 min-w-[200px] input-field">
            <form method="get" id="statusForm">
              <select name="status" class="input-field">
                <option value="all">All</option>
                <option>File Folder</option>
                <option>PDF</option>
                <option>Docs</option>
              </select>
            </form>
            <form method="get" id="sortForm">
              <select name="order" class="input-field">
                <option value="az">Sort A-Z</option>
                <option value="za">Sort Z-A</option>
                <option value="za">Date Modified</option>
              </select>
            </form>
            <!-- Add Dropdown -->
            <div x-data="{ open: false }" class="relative">
              <button @click="open = !open" title="Add new material" class="btn btn-secondary py-3">
                <img src="/public/assets/img/add.png" alt="User" class="size-3 ">
              </button>
              <div x-show="open" @click.outside="open=false" x-cloak class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-md z-50">
                <button class="block w-full text-left px-4 py-2 hover:bg-gray-100">Add File Folder</button>
                <button class="block w-full text-left px-4 py-2 hover:bg-gray-100">Upload</button>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto max-h-[550px] overflow-y-auto mt-4 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg p-2">
              <thead class="bg-gray-50">
                <tr>
                  <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Modified</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                  <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase pr-8">&nbsp;</th>
                </tr>
              </thead>
              <tbody id="table" class="text-sm" x-data>
                <!-- Folder 1 -->
                <tr @click="openRow = openRow === 1 ? null : 1" class="hover:bg-gray-100 cursor-pointer text-left">
                  <td class="pl-8 py-3">Procurement</td>
                  <td class="px-4 py-3">02/03/24</td>
                  <td class="px-4 py-3">File Folder</td>
                  <td class="px-4 py-3 text-right pr-8">
                    <img src="/public/assets/img/right-arrow.png" alt="File" class="size-3 inline-block"> 
                  </td>
                </tr>
                <tr x-show="openRow === 1" x-cloak>
                  <td colspan="4" class="pl-12 py-3 bg-gray-50 text-sm text-gray-600">
                    <ul class="list-disc ml-6">
                      <li>Purchase Order.pdf</li>
                      <li>Request Form.docx</li>
                    </ul>
                  </td>
                </tr>

                <!-- Folder 2 -->
                <tr @click="openRow = openRow === 2 ? null : 2" class="hover:bg-gray-100 cursor-pointer text-left">
                  <td class="pl-8 py-3">Material Request</td>
                  <td class="px-4 py-3">01/04/25</td>
                  <td class="px-4 py-3">File Folder</td>
                  <td class="px-4 py-3 text-right pr-8">
                    <img src="/public/assets/img/right-arrow.png" alt="File" class="size-3 inline-block"> 
                  </td>
                </tr>
                <tr x-show="openRow === 2" x-cloak>
                  <td colspan="4" class="pl-12 py-3 bg-gray-50 text-sm text-gray-600">
                    <ul class="list-disc ml-6">
                      <li>MRF_January.pdf</li>
                      <li>Summary.xlsx</li>
                    </ul>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script>
    window.adminSuccess = <?= isset($_SESSION['admin_success']) ? json_encode($_SESSION['admin_success']) : 'null' ?>;
    window.adminError = <?= isset($_SESSION['admin_error']) ? json_encode($_SESSION['admin_error']) : 'null' ?>;
  </script>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/search.js"></script>
</html>
