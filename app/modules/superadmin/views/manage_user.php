<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: /app/modules/shared/views/admin_login.php");
    exit;
}
require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
require_once __DIR__ . '/../../../controllers/RequestController.php';

$controller = new RequestController();
$requesters = $controller->getAllRequesters();

require_once __DIR__ . '/../../../controllers/DashboardController.php';
$controller = new DashboardController();
$profile = $controller->getProfile($_SESSION['email']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Users</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/helpers.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body class="bg-gray-200">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Manage Users</h1>
      <div x-data="{ showDetails: false, selected: {} }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="searchUser" placeholder="Search by name" class="flex-1 min-w-[200px] input-field">
            <!-- <select class="input-field">
              <option value="all">All</option>
              <option value="have_pending">Active</option>
              <option value="no_pending">Inactive</option>
            </select> -->
            <select id="sortUsers" class="input-field">
              <option value="az">Sort A-Z</option>
              <option value="za">Sort Z-A</option>
            </select>
            <img id="logo" src="/public/assets/img/usep.png" class="hidden">
            <button title="Export" id="export" class="btn-upper">
                <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto h-[580px] overflow-y-auto rounded-b-lg shadow bg-white">
          <table class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-1 py-2 rounded-tl-lg">&nbsp;</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Email</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Office / Department</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              </tr>
            </thead>
            <tbody id="usersTable" class="text-sm">
            <?php foreach ($requesters as $req): ?>
              <tr 
              data-firstname="<?= htmlspecialchars($req['firstName']) ?>"
              data-lastname="<?= htmlspecialchars($req['lastName']) ?>"
              @click="selected = {
                  email: '<?= htmlspecialchars($req['email']) ?>',
                  firstName: '<?= htmlspecialchars($req['firstName']) ?>',
                  lastName: '<?= htmlspecialchars($req['lastName']) ?>',
                  requester_id: '<?= htmlspecialchars($req['requester_id']) ?>',
                  officeOrDept: '<?= htmlspecialchars($req['officeOrDept']) ?>',
                  profile_pic: '<?= !empty($req['profile_pic']) ? $req['profile_pic'] : '/public/assets/img/user-default.png' ?>'
              }; showDetails = true"
              class="cursor-pointer hover:bg-gray-100 border-b border-gray-200">
                <td class="pl-4 py-2">
                  <img src="<?= !empty($req['profile_pic']) ? $req['profile_pic'] : '/public/assets/img/user-default.png' ?>"
                      alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">
                  <?= htmlspecialchars($req['firstName'] . ' ' . $req['lastName']) ?>
                </td>
                <td class="px-4 py-2"><?= htmlspecialchars($req['email']) ?></td>
                <td class="px-4 py-2">
                  <?php if (!empty($req['officeOrDept'])): ?>
                    <?= htmlspecialchars($req['officeOrDept']) ?>
                  <?php else: ?>
                    <span class="text-red-500">Undefined</span>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-2">
                  <span class="text-xs px-3 py-1 rounded-full font-semibold bg-green-200 text-green-800">
                    Active
                  </span>
                </td> 
                <!-- <td class="px-4 py-2">
                  <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full <?= $req['account_status'] === 'Active' ? 'bg-green-100 text-green-800 px-3' : 'bg-red-200 text-red-600' ?>">
                    <?= htmlspecialchars($req['account_status']) ?>
                  </span>
                </td> -->
              </tr>
            <?php endforeach; ?>
          </tbody>
          </table>
          </div>
        </div>

        <!-- Right Section (Details) -->
      <div x-show="showDetails" x-cloak class="bg-white shadow rounded-lg p-4 max-h-[640px] overflow-y-auto">
        <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
          <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
        </button>

        <h2 class="text-lg font-bold mb-2">User Information</h2>

        <!-- Profile Picture -->
        <img id="profile-preview"  
          :src="selected.profile_pic ? selected.profile_pic : '/public/assets/img/user-default.png'"
          alt=""
          class="w-36 h-36 rounded-full object-cover shadow-sm mx-auto mb-4"
        />

        <!-- Form -->
        <form id="userForm" class="space-y-5" method="post" action="../../../controllers/AdminController.php">
          <input type="hidden" name="requester_email" :value="selected.email || ''">
          <input type="hidden" name="update_user" value="1">

          <div>
            <label class="text-sm text-text mb-1">USeP Email</label>
            <input type="email" :value="selected.email || ''" disabled class="w-full view-field cursor-not-allowed"/>
          </div>

          <div>
            <label class="text-sm text-text mb-1">Student/Staff ID No.</label>
            <input type="text" name="requester_id" :value="selected.requester_id || ''" class="w-full input-field"/>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm text-text mb-1">First Name</label>
              <input type="text" name="firstName" :value="selected.firstName || ''" class="w-full input-field"/>
            </div>
            <div>
              <label class="text-sm text-text mb-1">Last Name</label>
              <input type="text" name="lastName" :value="selected.lastName || ''" class="w-full input-field"/>
            </div>
          </div>

          <div>
            <label for="dept" class="text-sm text-text mb-1">Program/Office</label>
            <select name="officeOrDept" x-model="selected.officeOrDept" class="w-full input-field">
              <option disabled value="">Select Department/Office</option>
              <optgroup label="Department">
                <option value="BEED">BEED</option>
                <option value="BSNED">BSNED</option>
                <option value="BECED">BECED</option>
                <option value="BSED">BSED</option>
                <option value="BSIT">BSIT</option>
                <option value="BTVTED">BTVTED</option>
                <option value="BSABE">BSABE</option>
              </optgroup>
              <optgroup label="OFFICES">
                <option value="OSAS">OSAS</option>
                <option value="CTET">CTET</option>
                <option value="SDMD">SDMD</option>
                <option value="CPU">CPU</option>
                <option value="Chancellor Office">Chancellor Office</option>
                <option value="Campus Library">Campus Library</option>
                <option value="Campus Clinic">Campus Clinic</option>
                <option value="Campus Register">Campus Register</option>
                <option value="Admin Office">Admin Office</option>
              </optgroup>
              <option value="Others">Others</option>
            </select>
          </div>

          <div class="flex justify-center gap-2">
            <form name="get_history" method="post" action="../../../controllers/UserController.php">
              <input type="hidden" name="requester_id" :value="selected.requester_id || ''">
              <input type="hidden" name="action" value="get_history">
              <button type="button" 
                  class="btn btn-secondary uhistoryBtn"
                  :data-requester-id="selected.requester_id" 
                  @click="fetchHistory(selected.requester_id)"
                  title="Request History">
            <img src="/public/assets/img/work-history.png" class="size-4" alt="Request history">
          </button>

            </form>
            <button type="button" id="updateBtn" name="update_user" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>

      <script>
      document.getElementById('updateBtn').addEventListener('click', function(e) {
          Swal.fire({
              title: 'Update User Details?',
              text: "Are you sure you want to save these changes?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, update!'
          }).then((result) => {
              if (result.isConfirmed) {
                  document.getElementById('userForm').submit();
              }
          })
      });
      </script> <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
      document.addEventListener('DOMContentLoaded', () => {
        <?php if(isset($_SESSION['update_status'])): ?>
          Swal.fire({
            icon: '<?php echo $_SESSION['update_status'] === "success" ? "success" : ($_SESSION['update_status'] === "duplicate" ? "warning" : "error"); ?>',
            title: '<?php echo $_SESSION['update_status'] === "success" ? "Updated!" : ($_SESSION['update_status'] === "duplicate" ? "Duplicate ID!" : "Error!"); ?>',
            text: '<?php echo $_SESSION['update_status'] === "success" ? "User updated successfully." : ($_SESSION['update_status'] === "duplicate" ? "This requester ID already exists." : "Failed to update user."); ?>'
          });
          <?php unset($_SESSION['update_status']); ?>
        <?php endif; ?>
      });
      </script>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('searchUser').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#usersTable tr');
      rows.forEach(row => {
        const name = row.children[1].textContent.toLowerCase();
        const email = row.children[3].textContent.toLowerCase();
        row.style.display = (name.includes(filter) || email.includes(filter)) ? '' : 'none';
      });
    });
  </script>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/export.js"></script>
</html>