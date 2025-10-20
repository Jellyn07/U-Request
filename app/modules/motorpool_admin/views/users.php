<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: modules/shared/views/admin_login.php");
  exit;
}
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
             <button class="input-field">
              <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
             </button>
             <button class="input-field">
              <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
             </button>
          </div>

        <!-- TABLE -->
        <div 
          x-data="{
            showDetails: false,
            selected: {
              email: '',
              firstName: '',
              lastName: '',
              requester_id: '',
              officeOrDept: '',
              profile_pic: '',
              account_status: ''
            }
          }"
          class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- LEFT: User List -->
          <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
            <div class="overflow-x-auto max-h-[550px] overflow-y-auto mt-4 rounded-lg shadow">
              <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg p-2">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-1 py-2">&nbsp;</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Office / Department</th>
                  </tr>
                </thead>
                <tbody class="text-sm" id="usersTable">
                  <?php foreach ($users as $user): ?>
                    <tr 
                      @click="
                        selected = {
                          email: '<?= htmlspecialchars($user['email']) ?>',
                          firstName: '<?= htmlspecialchars($user['firstName']) ?>',
                          lastName: '<?= htmlspecialchars($user['lastName']) ?>',
                          requester_id: '<?= htmlspecialchars($user['requester_id']) ?>',
                          officeOrDept: '<?= htmlspecialchars($user['officeOrDept']) ?>',
                          profile_pic: '<?= !empty($user['profile_pic']) ? $user['profile_pic'] : '/public/assets/img/user-default.png' ?>',
                          account_status: '<?= htmlspecialchars($user['account_status']) ?>'
                        };
                        showDetails = true;
                      "
                      class="hover:bg-gray-100 cursor-pointer border-b border-gray-100"
                    >
                      <td class="px-2 py-2">
                        <img src="<?= !empty($user['profile_pic']) ? $user['profile_pic'] : '/public/assets/img/user-default.png' ?>" 
                            class="size-8 rounded-full object-cover mx-auto" alt="User">
                      </td>
                      <td class="px-4 py-2"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></td>
                      <td class="px-4 py-2">
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                          <?= $user['account_status'] === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' ?>">
                          <?= htmlspecialchars($user['account_status']) ?>
                        </span>
                      </td>
                      <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                      <td class="px-4 py-2">
                        <?php if (!empty($user['officeOrDept'])): ?>
                          <?= htmlspecialchars($user['officeOrDept']) ?>
                        <?php else: ?>
                          <span class="text-red-500">Undefined</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- RIGHT: User Details -->
          <div 
            x-show="showDetails" 
            x-cloak 
            class="bg-white shadow rounded-lg p-4 max-h-[580px] overflow-y-auto transition-all duration-300"
          >
            <button 
              @click="showDetails = false" 
              class="text-sm text-gray-500 hover:text-gray-800 float-right"
            >
              <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
            </button>

            <h2 class="text-lg font-bold mb-2">User Information</h2>

            <!-- Profile -->
            <img 
              :src="selected.profile_pic" 
              alt="Profile"
              class="w-36 h-36 rounded-full object-cover shadow-sm mx-auto mb-4"
            >

            <form id="userForm" method="post" action="../../../controllers/UserAdminController.php" class="space-y-4">
              <input type="hidden" name="requester_email" :value="selected.email || ''">
              <input type="hidden" name="update_user" value="1">

              <div>
                <label class="text-sm text-gray-600">Email</label>
                <input type="email" x-model="selected.email" disabled class="w-full view-field cursor-not-allowed">
              </div>

              <div>
                <label class="text-sm text-gray-600">Student/Staff ID</label>
                <input type="text" x-model="selected.requester_id" disabled class="w-full view-field cursor-not-allowed">
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm text-gray-600">First Name</label>
                  <input type="text" x-model="selected.firstName" disabled class="w-full view-field cursor-not-allowed">
                </div>
                <div>
                  <label class="text-sm text-gray-600">Last Name</label>
                  <input type="text" x-model="selected.lastName" disabled class="w-full view-field cursor-not-allowed">
                </div>
              </div>

              <div>
                <label class="text-sm text-gray-600">Program/Office</label>
                <input type="text" x-model="selected.officeOrDept" disabled class="w-full view-field cursor-not-allowed">
              </div>

              <div class="flex justify-center gap-2 pt-2">
                <button type="button" class="btn btn-secondary" 
                  @click="viewRequestHistory(selected.requester_id)">
                  <img src="/public/assets/img/work-history.png" class="size-4" alt="Work History">
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <script src="/public/assets/js/shared/menus.js"></script>
  </main>

  <script>
async function viewRequestHistory(requester_id) {
  if (!requester_id) return;

  try {
    const formData = new FormData();
    formData.append("get_request_history", "1");
    formData.append("requester_id", requester_id);

    const res = await fetch("../../../controllers/UserAdminController.php", {
      method: "POST",
      body: formData
    });

    const history = await res.json();

    if (!Array.isArray(history) || !history.length) {
      Swal.fire({
        icon: "info",
        title: "No Work History Found",
        text: "This requester has no recorded requests yet."
      });
      return;
    }

    // Create table rows dynamically
    const rows = history.map(item => `
      <tr class="hover:bg-gray-50">
        <td class="px-3 py-1 border">${item.tracking_id}</td>
        <td class="px-3 py-1 border">${item.request_Type}</td>
        <td class="px-3 py-1 border">${item.req_status}</td>
        <td class="px-3 py-1 border">${item.date_finished ?? "—"}</td>
      </tr>
    `).join("");

    Swal.fire({
      title: "Request History",
      html: `
        <div class="overflow-x-auto">
          <table class="w-full border text-sm text-left">
            <thead>
              <tr class="bg-gray-100">
                <th class="px-3 py-1 border">Tracking ID</th>
                <th class="px-3 py-1 border">Request Type</th>
                <th class="px-3 py-1 border">Status</th>
                <th class="px-3 py-1 border">Date Finished</th>
              </tr>
            </thead>
            <tbody>${rows}</tbody>
          </table>
        </div>
      `,
      width: 800,
      confirmButtonText: "Close",
      confirmButtonColor: "#800000"
    });
  } catch (err) {
    console.error(err);
    Swal.fire({ icon: "error", title: "Error", text: "Could not fetch history." });
  }
}

  </script>
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
      filterColumn: 2                  // Status column
    });
  });
</script>



</body>
</html>
