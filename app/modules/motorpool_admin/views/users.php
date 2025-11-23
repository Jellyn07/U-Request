<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: /app/modules/shared/views/admin_login.php");
    exit;
}
if (!isset($_SESSION['email'])) {
    header("Location: admin_login.php");
    exit;
}
require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserAdminController.php';

$controller = new UserAdminController();
$users = $controller->getUsers();
$profile = $controller->getProfile($_SESSION['email']);
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
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/helpers.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/shared/popup.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body class="bg-gray-100">
  <!-- Sidebar -->
  <?php include COMPONENTS_PATH . '/motorpool_menu.php'; ?>

  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Users</h1>

      <div x-data="{ showDetails: false, selected: {} }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- LEFT SECTION -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <!-- Search + Sort + Buttons -->
            <input type="text" id="searchUser" placeholder="Search by name or email" class="flex-1 min-w-[200px] input-field">

            <select id="statusFilter" class="input-field">
              <option value="all">All</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>

            <select id="sortUsers" class="input-field">
              <option value="az">Sort A–Z</option>
              <option value="za">Sort Z–A</option>
            </select>

            <!-- <button class="input-field" title="Print data in the table">
              <img src="/public/assets/img/printer.png" class="size-4 my-0.5" alt="Print">
            </button> -->
            <img id="logo" src="/public/assets/img/usep.png" class="hidden">
            <button title="Export" id="export" class="btn-upper">
              <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
          </div>

          <!-- TABLE -->
          <div id="table" class="overflow-x-auto h-[578px] overflow-y-auto rounded-b-lg shadow bg-white">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg p-2">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2">&nbsp;</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Office / Department</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
              </thead>
              <tbody id="usersTable" class="text-sm">
                <?php if (!empty($users)): ?>
                  <?php foreach ($users as $user): ?>
                    <tr 
                      @click="selected = {
                        email: '<?= htmlspecialchars($user['email']) ?>',
                        firstName: '<?= htmlspecialchars($user['firstName']) ?>',
                        lastName: '<?= htmlspecialchars($user['lastName']) ?>',
                        requester_id: '<?= htmlspecialchars($user['requester_id']) ?>',
                        req_id: '<?= htmlspecialchars($user['req_id']) ?>',
                        officeOrDept: '<?= htmlspecialchars($user['officeOrDept']) ?>',
                        profile_pic: '<?= !empty($user['profile_pic']) ? $user['profile_pic'] : '/public/assets/img/user-default.png' ?>',
                        account_status: '<?= htmlspecialchars($user['account_status']) ?>'
                      }; showDetails = true"
                      class="hover:bg-gray-100 cursor-pointer border-b border-gray-100"
                    >
                      <td class="px-2 py-2">
                        <img src="<?= !empty($user['profile_pic']) ? $user['profile_pic'] : '/public/assets/img/user-default.png' ?>" class="size-8 rounded-full object-cover mx-auto">
                      </td>
                      <td class="px-4 py-2"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></td>
                      <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                      <td class="px-4 py-2">
                        <?= !empty($user['officeOrDept']) ? htmlspecialchars($user['officeOrDept']) : '<span class="text-red-500">Undefined</span>' ?>
                      </td>
                      <td class="px-4 py-2">
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                          <?= $user['account_status'] === 'Active' ? 'bg-green-200 text-green-800 px-3' : 'px-1 bg-red-200 text-red-600' ?>">
                          <?= htmlspecialchars($user['account_status']) ?>
                        </span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No user records found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- RIGHT SECTION: USER DETAILS -->
        <div x-show="showDetails" x-cloak class="bg-white shadow rounded-lg p-4 max-h-[640px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>

          <h2 class="text-lg font-bold mb-2">User Information</h2>
          <img :src="selected.profile_pic" alt="Profile" class="w-36 h-36 rounded-full object-cover shadow-sm mx-auto mb-4">

          <form id="userForm" method="post" action="../../../controllers/UserAdminController.php" class="space-y-4">
            <input type="hidden" name="requester_email" :value="selected.email || ''">
            <input type="hidden" name="requester_id" :value="selected.requester_id || ''">
            <input type="hidden" name="update_user" value="1">

            <div>
              <label class="text-xs text-text mb-1">Email</label>
              <input type="email" x-model="selected.email" disabled class="w-full view-field cursor-not-allowed">
            </div>
            <div>
              <label class="text-xs text-text mb-1">Student/Staff ID</label>
              <input type="text" x-model="selected.requester_id" disabled class="w-full view-field cursor-not-allowed">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="text-xs text-text mb-1">First Name</label>
                <input type="text" x-model="selected.firstName" disabled class="w-full view-field cursor-not-allowed">
              </div>
              <div>
                <label class="text-xs text-text mb-1">Last Name</label>
                <input type="text" x-model="selected.lastName" disabled class="w-full view-field cursor-not-allowed">
              </div>
            </div>
            <div>
              <label class="text-xs text-text mb-1">Program/Office</label>
              <input type="text" x-model="selected.officeOrDept" disabled class="w-full view-field cursor-not-allowed">
            </div>

              <div class="flex justify-center gap-2 pt-2">
                <button type="button" class="btn btn-secondary" 
                  @click="viewVehicleRequestHistory(selected.requester_id)">
                  <img src="/public/assets/img/work-history.png" class="size-4" alt="Work History">
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <script src="/public/assets/js/shared/menus.js"></script>
  <script src="/public/assets/js/shared/export.js"></script>
</main>
  <!-- load the filter script (defer so DOM is ready) -->
<script defer src="<?php echo PUBLIC_URL; ?>/assets/js/shared/table-filter.js"></script>

<!-- Load as ES Module -->
<script type="module">
  import { initTableFilters } from "<?php echo PUBLIC_URL; ?>/assets/js/shared/table-filters.js";

  document.addEventListener("DOMContentLoaded", () => {
    initTableFilters({
      tableId: "usersTable",           // tbody ID
      searchId: "searchUser",          // search input ID
      filterId: "statusFilter",        // dropdown for Active/Inactive
      sortId: "sortUsers",             // dropdown for sorting A–Z / Z–A
      searchColumns: [1, 3],           // Full Name and Email columns
      filterColumn: 4                  // Status column
    });
  });
</script>

</body>
</html>
