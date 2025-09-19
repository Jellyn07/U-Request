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
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Manage Admin</h1>
      <div x-data="{ showDetails: false, selected: {} }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
            <!-- Add Admin Modal -->
          <div x-data="{ showModal: false }">
            <!-- Trigger Button (example only) -->
            <button @click="showModal = true" class="btn btn-secondary">
              <img src="/public/assets/img/add-admin.png" alt="User" class="size-4 my-0.5">
            </button>

            <!-- Modal Background -->
            <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-auto">
              <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl mx-auto my- relative">
                <!-- Close Button -->
                <button @click="showModal = false" class="absolute top-3 left-3 text-gray-600 hover:text-gray-800 text-xl font-bold z-50">
                  &times;
                </button>

                <!-- Modal Content -->
                <main class="flex flex-col transition-all duration-300 p-4 space-y-4">
                  <!-- Profile Picture -->
                  <form method="post" action="../../../controllers/AdminController.php" enctype="multipart/form-data">
                    <div class="rounded-xl flex flex-col items-center">
                      <div class="relative">
                        <img id="profile-preview"  
                            src="/public/assets/img/user-default.png" 
                            alt="profile picture"
                            class="w-24 h-24 rounded-full object-cover border-2 border-secondary shadow-sm"
                        />
                        <!-- Edit button -->
                        <label for="profile_picture" title="Change Profile Picture" 
                          class="absolute bottom-2 right-2 bg-primary text-white p-2 rounded-full shadow-md cursor-pointer transition">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15.232 5.232l3.536 3.536m-2.036-5.036
                                    a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                          </svg>
                        </label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" onchange="previewProfile(event)">
                      </div>
                    </div>
                  

                  <!-- Identity Information -->
                  <div class="flex justify-center">
                    <div class="w-full md:w-2/3 bg-background shadow-md rounded-xl p-4 border border-gray-200">
                      <h2 class="text-xl font-semibold mb-3">Admin Credentials</h2>
                      <!-- <form class="space-y-4" method="post"> -->
                        <div>
                          <label class="text-sm text-text mb-1">Staff ID No.</label>
                          <input type="text" name="staff_id" class="w-full input-field" required />
                        </div>

                        <div>
                          <label class="text-sm text-text mb-1">USeP Email</label>
                          <input type="email" name="email" class="w-full input-field" required />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <div>
                            <label class="text-sm text-text mb-1">First Name</label>
                            <input type="text" name="first_name" class="w-full input-field" required />
                          </div>
                          <div>
                            <label class="text-sm text-text mb-1">Last Name</label>
                            <input type="text" name="last_name" class="w-full input-field" required />
                          </div>
                          <div>
                            <label class="text-sm text-text mb-1">Contact No.</label>
                            <input type="text" name="contact_no" 
                                  class="w-full input-field" 
                                  required 
                                  minlength="11" 
                                  maxlength="11" 
                                  pattern="[0-9]{11}" 
                                  title="Please enter a valid 11-digit contact number" />
                          </div>
                        </div>

                        <div>
                          <label class="text-sm text-text mb-1">Access Level</label>
                          <select name="access_level" class="w-full input-field" required>
                            <option value="" disabled selected>Select Access</option>
                            <option value="1">Super Admin</option>
                            <option value="2">GSU Admin</option>
                            <option value="3">Motorpool Admin</option>
                          </select>
                        </div>
                      <!-- </form> -->
                    </div>
                  </div>

                  <!-- Password Update -->
                  <div class="flex justify-center">
                    <div class="w-full md:w-2/3 bg-white shadow-md rounded-xl p-4 border border-gray-200">
                      <h2 class="text-xl font-semibold mb-3">Default Password</h2>
                      <!-- <form class="space-y-4" method="post" action="../../../controllers/ProfileController.php"> -->
                        <div>
                          <label class="text-sm text-text mb-1">Password</label>
                          <input type="password" name="password" class="w-full input-field" required />
                        </div>
                        <div>
                          <label class="text-sm text-text mb-1">Confirm Password</label>
                          <input type="password" name="confirm_password" class="w-full input-field" required />
                        </div>
                      <!-- </form> -->
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="flex justify-center gap-2 pb-2">
                    <button type="button" @click="showModal = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" name="add_admin" class="btn btn-primary px-7">Save</button>
                  </div>
                  </form>
                </main>
              </div>
            </div>
          </div>
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
              <?php if (!empty($admins)): ?>
                <?php foreach ($admins as $admin): ?>
                  <tr 
                      @click="showDetails = true; selected = {
                        staff_id: '<?php echo $admin['staff_id']; ?>',
                        email: '<?php echo $admin['email']; ?>',
                        first_name: '<?php echo $admin['first_name']; ?>',
                        last_name: '<?php echo $admin['last_name']; ?>',
                        full_name: '<?php echo $admin['full_name']; ?>',
                        contact_no: '<?php echo $admin['contact_no']; ?>',
                        profile_picture: '<?php echo $admin['profile_picture']; ?>',
                        accessLevel_id: '<?php echo $admin['accessLevel_id']; ?>',
                        accessLevel_desc: '<?php echo $admin['accessLevel_desc']; ?>',
                        status: '<?php echo $admin['status']; ?>'
                      }"
                      class="cursor-pointer hover:bg-gray-100"
                    >
                    <td class="pl-4 py-2">
                    <img src="<?php echo !empty($admin['profile_picture']) 
                                ? '/public/uploads/profile_pics/' . htmlspecialchars($admin['profile_picture']) 
                                : '/public/assets/img/user-default.png'; ?>" 
                                  alt="User" 
                                  class="size-8 rounded-full object-cover">
                    </td>
                    <td class="px-4 py-2">
                      <?php echo htmlspecialchars($admin['full_name']); ?>
                    </td>
                    <td class="px-4 py-2 <?php echo strtolower($admin['status']) === 'active' ? 'text-green-800' : 'text-red-800'; ?>">
                      <?php echo htmlspecialchars($admin['status']); ?>
                    </td>
                    <td class="px-4 py-2">
                      <?php echo htmlspecialchars($admin['email']); ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-center py-4 text-gray-500">No administrators found.</td>
                </tr>
              <?php endif; ?>
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

          <h2 class="text-lg font-bold mb-2">Admin Information</h2>

          <!-- Profile Picture -->
          <img id="profile-preview"  
            :src="selected.profile_picture ? '/public/uploads/profile_pics/' + selected.profile_picture : '/public/assets/img/user-default.png'"
            alt=""
            class="w-36 h-36 rounded-full object-cover shadow-sm mx-auto"
          />

          <!-- Form -->
          <form class="space-y-5" method="post" action="../../../controllers/AdminController.php">
            <input type="hidden" name="requester_email" :value="selected.email || ''">

            <div>
              <label class="text-sm text-text mb-1">USeP Email</label>
              <input type="email" id="email" :value="selected.email || ''" disabled class="w-full view-field cursor-not-allowed"/>
            </div>

            <div>
              <label class="text-sm text-text mb-1">Staff ID No.</label>
              <input type="text" id="staff_id" name="staff_id" :value="selected.staff_id || ''" class="w-full input-field"/>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="text-sm text-text mb-1">First Name</label>
                <input type="text" id="firstName" name="firstName" :value="selected.first_name || ''" class="w-full input-field"/>
              </div>
              <div>
                <label class="text-sm text-text mb-1">Last Name</label>
                <input type="text" id="lastName" name="lastName" :value="selected.last_name || ''" class="w-full input-field"/>
              </div>
              <div>
                <label class="text-sm text-text mb-1">Contact No.</label>
                <input type="text" id="contact_no" name="contact_no" :value="selected.contact_no || ''" class="w-full input-field"/>
              </div>
            </div>

            <div x-data="{ editingRole: false }">
          <label class="text-sm text-text mb-1">Access Level</label>

          <!-- Display mode -->
          <div x-show="!editingRole" class="flex items-center justify-between">
          <span class="font-medium text-gray-700" x-text="selected.accessLevel_desc || 'No Role Assigned'"></span>

          <!-- Edit button -->
          <button type="button"
            @click="editingRole = true"
            class="text-blue-600 hover:text-blue-800 text-sm ml-2">
            Edit
          </button>
        </div>


          <!-- Edit mode -->
          <div x-show="editingRole" class="flex items-center gap-2 mt-2">
            <select name="accessLevel_id" x-model="selected.accessLevel_id" class="w-full input-field">
              <option disabled value="">Select Role</option>
              <option value="1">GSU Administrator</option>
              <option value="2">Motorpool Administrator</option>
              <option value="3">Superadmin</option>
            </select>

            <button type="button"
              @click="editingRole = false"
              class="text-gray-500 hover:text-gray-700 text-sm">
              Cancel
            </button>
          </div>
        </div>



            <div class="flex justify-center">
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
  </main>

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

      function previewProfile(event) {
        const output = document.getElementById('profile-preview');
        output.src = URL.createObjectURL(event.target.files[0]);
      }
      window.adminSuccess = <?= isset($_SESSION['admin_success']) ? json_encode($_SESSION['admin_success']) : 'null' ?>;
      window.adminError = <?= isset($_SESSION['admin_error']) ? json_encode($_SESSION['admin_error']) : 'null' ?>;
  </script>
</body>
</html>

<?php
// Clear session variables after outputting
unset($_SESSION['admin_success']);
unset($_SESSION['admin_error']);
?>
