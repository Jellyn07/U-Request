<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: modules/shared/views/admin_login.php");
  exit;
}

require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserAdminController.php';

$controller = new UserAdminController();

// ✅ If it's an AJAX request (fetching users only)
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
  $search = $_GET['search'] ?? '';
  $status = $_GET['status'] ?? 'all';
  $sort   = $_GET['order'] ?? 'az';

  $users = $controller->getUsers($search, $status, $sort);

  header('Content-Type: application/json');
  echo json_encode($users);
  exit;
}

// ✅ Regular page load
$users = $controller->getUsers();
$profile = $controller->getProfile($_SESSION['email']);
?>



<script>
  document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchUser");
    const statusSelect = document.getElementById("statusFilter");
    const sortSelect = document.getElementById("sortUsers");
    const tableBody = document.getElementById("Table");

    async function fetchUsers() {
      const search = searchInput.value.trim();
      const status = statusSelect.value;
      const order = sortSelect.value;

      const params = new URLSearchParams({
        search: search,
        status: status,
        order: order,
        ajax: "1"
      });

      try {
        const res = await fetch(`users.php?${params.toString()}`);
        const users = await res.json();
        renderTable(users);
      } catch (err) {
        console.error("Fetch error:", err);
      }
    }

    function renderTable(users) {
      tableBody.innerHTML = "";

      if (!users.length) {
        tableBody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center py-4 text-gray-500">
            No users found.
          </td>
        </tr>`;
        return;
      }

      users.forEach(user => {
        const profilePic = user.profile_pic ?
          user.profile_pic :
          "/public/assets/img/user-default.png";

        // Build a JS object of user data to pass to Alpine
        const safeUser = {
          requester_id: user.requester_id ?? "",
          firstName: user.firstName ?? "",
          lastName: user.lastName ?? "",
          email: user.email ?? "",
          dept: user.officeOrDept ?? "N/A",
          profile_pic: user.profile_pic
        };

        const safeJson = JSON.stringify(safeUser)
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#39;");

        // ✅ Determine badge color & text based on account_status
        const isActive = user.account_status === "Active";
        const statusBadge = `
          <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
            ${isActive ? "bg-green-100 text-green-800" : "bg-gray-200 text-gray-600"}">
            ${user.account_status}
          </span>
        `;

        // 🧠 Clickable row using Alpine
        const row = `
        <tr 
          @click="selected = ${safeJson}; showDetails = true"
          class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100"
        >
          <td class="px-1 py-2">
            <img src="${profilePic}" alt="User" class="size-8 rounded-full object-cover mx-auto">
          </td>
          <td class="px-4 py-2">${user.firstName} ${user.lastName}</td>
          <td class="px-4 py-2">${statusBadge}</td>
          <td class="px-4 py-2">${user.email}</td>
          <td class="px-4 py-2">${user.officeOrDept || 'N/A'}</td>
        </tr>`;

        tableBody.insertAdjacentHTML("beforeend", row);
      });

      // 🪄 Reinitialize Alpine after rendering
      if (window.Alpine) Alpine.initTree(tableBody);
    }

    // 🔍 Live search + filters
    searchInput.addEventListener("input", debounce(fetchUsers, 400));
    statusSelect.addEventListener("change", fetchUsers);
    sortSelect.addEventListener("change", fetchUsers);

    function debounce(func, delay) {
      let timer;
      return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => func(...args), delay);
      };
    }

    // Initial load
    fetchUsers();
  });

  // 🔍 Function for showing details
  async function showUserDetails(requester_id) {
    try {
      const res = await fetch(`/app/controllers/UserAdminController.php?requester_id=${requester_id}`);
      const data = await res.json();

      if (data) {
        selected = data; // Update Alpine variable or state
        showDetails = true; // Open the details panel
      }
    } catch (err) {
      console.error('Error loading user details:', err);
    }
  }


  /////history
  async function viewRequestHistory(req_id) {
  try {
    const formData = new FormData();
    formData.append("get_work_history", "1");
    formData.append("req_id", req_id);

    const res = await fetch("/app/controllers/UserAdminController.php", {
      method: "POST",
      body: formData
    });

    const history = await res.json();

    if (!history.length) {
      Swal.fire("No Work History Found", "This requester has no recorded requests yet.", "info");
    } else {
      console.log(history);
      // You can open a modal or table here to show results
    }
  } catch (err) {
    console.error("Error fetching work history:", err);
  }
}
</script>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Users</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/admin-user.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/helpers.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/shared/popup.js"></script>

  <?php
  if (!isset($_SESSION['email'])) {
    header("Location: modules/shared/views/admin_login.php");
    exit;
  }
  // ✅ Fetch profile here
  $profile = $controller->getProfile($_SESSION['email']);
  ?>

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U-Request | Users</title>
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
    <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  </head>

<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/gsu_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Users</h1>
      <div
        x-data="{ 
          showDetails: false, 
          selected: { 
            requester_id: '', 
            firstName: '', 
            lastName: '', 
            email: '', 
            dept: '', 
            profile_pic: '' 
          },

          setSelected(user) {
            this.selected = user;
            this.showDetails = true;
          },

          async viewRequestHistory(requester_id) {
            try {
              const formData = new FormData();
              formData.append('get_work_history', '1');
              formData.append('staff_id', requester_id);

              const res = await fetch('/app/controllers/UserAdminController.php', {
                method: 'POST',
                body: formData
              });

              const history = await res.json();

              if (!history.length) {
                Swal.fire({
                  icon: 'info',
                  title: 'No Work History Found',
                  text: 'This requester has no recorded requests yet.'
                });
                return;
              }

              // ✅ Build a simple list for display
              const historyHTML = history.map(item => `
                <tr>
                  <td class='px-3 py-1 border'>${item.request_Type}</td>
                  <td class='px-3 py-1 border'>${item.req_status}</td>
                  <td class='px-3 py-1 border'>${item.date_created}</td>
                </tr>
              `).join('');

              Swal.fire({
                title: 'Work History',
                html: `
                  <table class='w-full border text-sm text-left'>
                    <thead>
                      <tr class='bg-gray-100'>
                        <th class='px-3 py-1 border'>Request Type</th>
                        <th class='px-3 py-1 border'>Status</th>
                        <th class='px-3 py-1 border'>Date Created</th>
                      </tr>
                    </thead>
                    <tbody>${historyHTML}</tbody>
                  </table>
                `,
                width: 600,
                confirmButtonText: 'Close'
              });
            } catch (error) {
              console.error('Error fetching work history:', error);
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not fetch work history. Please try again later.'
              });
            }
          }
        }"
        class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="searchUser" placeholder="Search by name or email" class="flex-1 min-w-[200px] input-field">
            <select id="statusFilter" class="input-field">
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
          <img :src="selected.profile_pic || '/public/assets/img/user-default.png'" alt="Profile" class="w-36 h-36 rounded-full object-cover shadow-sm mx-auto mb-4">
          <!-- Form -->
          <form id="userForm" class="space-y-5" method="post" action="../../../controllers/UserAdminController.php">
            <input type="hidden" name="requester_email" :value="selected.email || ''">
            <input type="hidden" name="update_user" value="1">

            <div>
              <label class="text-sm text-text mb-1">USeP Email</label>
              <input type="email" x-model="selected.email" disabled class="w-full view-field cursor-not-allowed" />
            </div>

            <div>
              <label class="text-sm text-text mb-1">Student/Staff ID No.</label>
              <input type="text" x-model="selected.requester_id" disabled class="w-full view-field cursor-not-allowed" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm text-text mb-1">First Name</label>
                <input type="text" x-model="selected.firstName" disabled class="w-full view-field cursor-not-allowed" />
              </div>
              <div>
                <label class="text-sm text-text mb-1">Last Name</label>
                <input type="text" x-model="selected.lastName" disabled class="w-full view-field cursor-not-allowed" />
              </div>
            </div>

            <div>
              <label class="text-sm text-text mb-1">Program/Office</label>
              <input type="text" x-model="selected.dept" disabled class="w-full view-field cursor-not-allowed" />
            </div>
          </form>

          <div class="flex justify-center gap-2 pt-2">
            <button
              type="button"
              title="Work History"
              class="btn btn-secondary"
              @click="viewRequestHistory(selected.requester_id)">
              <img src="/public/assets/img/work-history.png" class="size-4" alt="work history">
            </button>

            <!-- 
  <button type="submit" name="update_personnel" class="btn btn-primary">
    Save Changes
  </button>
  -->
          </div>
        </div>
      </div>
    </div>
    </div>
</body>
<script src="/public/assets/js/shared/menus.js"></script>

</html>