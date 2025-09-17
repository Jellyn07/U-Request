<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
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
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Manage Admin</h1>
      <div x-data="{ showDetails: false }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="searchUser" placeholder="Search by name or email" class="flex-1 min-w-[200px] input-field">
            <select class="input-field">
              <option value="all">All</option>
              <option value="have_pending">Have Pending</option>
              <option value="no_pending">No Pending</option>
            </select>
            <select class="input-field">
              <option value="az">Sort A-Z</option>
              <option value="za">Sort Z-A</option>
            </select>
            <button class="input-field">
              <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
            </button>
            <button class="input-field">
              <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
            <button class="btn btn-primary">
              <!-- <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5"> -->
              Add
            </button>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto max-h-[550px] overflow-y-auto mt-4 rounded-lg shadow">
          <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg p-2">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-1 py-2 rounded-tl-lg">&nbsp;</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Email</th>
              </tr>
            </thead>
            <tbody id="usersTable">
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <tr @click="showDetails = true" class="cursor-pointer hover:bg-gray-100">
                <td class="pl-4 py-2">
                  <img src="/public/assets/img/user-default.png" alt="User" class="size-8 rounded-full object-cover">
                </td>
                <td class="px-4 py-2">John Doe</td>
                <td class="px-4 py-2 text-green-800">Active</td>
                <td class="px-4 py-2">john@example.com</td>
              </tr>
              <!-- more rows -->
            </tbody>
          </table>
          </div>
        </div>

        <!-- Right Section (Details) -->
        <div x-show="showDetails" x-cloak
            class="bg-white shadow rounded-lg p-4">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>
          <h2 class="text-lg font-bold mb-2">User Information</h2>
          <img id="profile-preview"  
                  src="<?php echo htmlspecialchars(!empty($profile['profile_pic']) ? $profile['profile_pic'] : '/public/assets/img/user-default.png'); ?>" 
                  alt="<?php echo htmlspecialchars($profile['cust_name'] ?? 'User Profile'); ?>"
                  class="w-36 h-36 rounded-full object-cover shadow-sm mx-auto mb-4"
              />
          <form class="space-y-5" method="post" action="../../../controllers/ProfileController.php">
            <!-- <input type="hidden" name="requester_email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>"> -->

            <div>
              <label class="text-sm text-text mb-1">
                USeP Email
              </label>
              <input type="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>" disabled class="w-full view-field cursor-not-allowed"/>
              <input type="hidden" name="requester_email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
            </div>

            <div>
              <label class="text-sm text-text mb-1">
                Student/Staff ID No.
              </label>
              <input type="text" value="<?php echo htmlspecialchars($profile['requester_id'] ?? ''); ?>" class="w-full input-field"/>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm text-text mb-1">
                  First Name
                </label>
                <input type="text" value="<?php echo htmlspecialchars($profile['firstName'] ?? ''); ?>" class="w-full input-field"/>
              </div>
              <div>
                <label class="text-sm text-text mb-1">
                  Last Name
                </label>
                <input type="text" value="<?php echo htmlspecialchars($profile['lastName'] ?? ''); ?>" class="w-full input-field"/>
              </div>
            </div>

            <div>
              <label for="program" class="text-sm text-text mb-1">Program/Office</label>
              <select id="dept" name="officeOrDept" class="w-full input-field" >
              <option disabled <?php echo empty($profile['officeOrDept']) ? 'selected' : ''; ?>>Select Department/Office</option>
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
            <div class="flex justify-center">
              <button type="submit" class="btn btn-secondary mr-2">
                User Requests
              </button>
              <button type="submit" class="btn btn-primary">
                Save Changes
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('searchUser').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#usersTable tr');
      rows.forEach(row => {
        const name = row.children[1].textContent.toLowerCase();
        const email = row.children[2].textContent.toLowerCase();
        row.style.display = (name.includes(filter) || email.includes(filter)) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
