<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/DriverController.php';
if (isset($_SESSION['driver_success'])) {
    echo "<script>window.driverSuccess = " . json_encode($_SESSION['driver_success']) . ";</script>";
    unset($_SESSION['driver_success']);
}

if (isset($_SESSION['driver_error'])) {
    echo "<script>window.driverError = " . json_encode($_SESSION['driver_error']) . ";</script>";
    unset($_SESSION['driver_error']);
}

if (!isset($_SESSION['email'])) {
    header('Location: modules/shared/views/admin_login.php');
    exit;
}
$controller = new DriverController(); $drivers = $controller->getAllDriver();

require_once __DIR__ . '/../../../controllers/DashboardController.php';
$controller = new DashboardController();
$profile = $controller->getProfile($_SESSION['email']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Drivers</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/admin-user.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/helpers.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/shared/popup.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Menu & Header -->
  <?php
  if ($_SESSION['access_level'] == 1) {
      include COMPONENTS_PATH . '/superadmin_menu.php';
  } elseif ($_SESSION['access_level'] == 3) {
      include COMPONENTS_PATH . '/motorpool_menu.php';
  } else {
      echo "<p>No menu available for your access level.</p>";
  }
  ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Drivers</h1>
      <div x-data="{ showDetails: false, selected: {} }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="searchUser" placeholder="Search by name" class="flex-1 min-w-[200px] input-field">
            <!-- <select class="input-field" id="statusFilter">
              <option value="all">All</option>
              <option value="Available">Available</option>
              <option value="Fixing">Fixing</option>
            </select> -->

            <select class="input-field" id="sortUsers">
                <option value="az">Sort A-Z</option>
                <option value="za">Sort Z-A</option>
            </select>
            <img id="logo" src="/public/assets/img/usep.png" class="hidden">
            <button title="Export" id="export" class="btn-upper">
                <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
            <!-- Add Admin Modal -->
                <div x-data="{ showModal: false }">
            <!-- Trigger Button (example only) -->
            <button @click="showModal = true" title="Add new driver" class="btn btn-secondary">
                <img src="/public/assets/img/add-admin.png" alt="User" class="size-4 my-0.5">
            </button>

            <!-- Modal Background -->
            <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-auto">
              <div class="bg-white rounded-xl shadow-xl w-90% md:w-1/3 mx-auto relative overflow-auto">
                <!-- Modal Content -->
                <main class="flex flex-col transition-all duration-300 p-4 space-y-4 px-5">
                  <!-- Profile Picture -->
                  <form method="post" action="../../../controllers/DriverController.php" enctype="multipart/form-data">
                    <div class="rounded-xl flex flex-col items-center">
                      <div class="relative">
                        <img id="profile-preview"  
                            src="/public/assets/img/user-default.png" 
                            alt="profile picture"
                            class="w-24 h-24 rounded-full object-cover shadow-sm"
                        />
                        <!-- Edit button -->
                        <label for="profile_picture" title="Change Profile Picture" 
                          class="absolute bottom-2 right-2 bg-primary text-white p-1 rounded-full shadow-md cursor-pointer transition">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="w-full">
                      <h2 class="text-base font-medium">Driver Credentials</h2>
                      <!-- <form class="space-y-4" method="post"> -->
                        <div>
                          <label class="text-xs text-text mb-1">Staff ID / Driver ID No.<span class="text-secondary">*</span></label>
                          <input type="text" name="staff_id" class="w-full input-field" required />
                        </div>

                        <!-- <div>
                          <label class="text-xs text-text mb-1">USeP Email</label>
                          <input type="email" name="email" class="w-full input-field" required />
                        </div> -->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                          <div>
                            <label class="text-xs text-text mb-1">First Name<span class="text-secondary">*</span></label>
                            <input type="text" name="first_name" class="w-full input-field" required />
                          </div>
                          <div>
                            <label class="text-xs text-text mb-1">Last Name<span class="text-secondary">*</span></label>
                            <input type="text" name="last_name" class="w-full input-field" required />
                          </div>
                        </div>

                        <div>
                            <label class="text-xs text-text mb-1">Contact No.<span class="text-secondary">*</span></label>
                            <input type="text" name="contact_no" required minlength="11" maxlength="11" pattern="[0-9]{11}" title="Please enter a valid 11-digit contact number" class="w-full input-field"/>
                        </div>

                        <!-- <div>
                          <label class="text-xs text-text mb-1">Unit<span class="text-secondary">*</span></label>
                          <select name="unit" class="w-full input-field" required>
                            <option value="" disabled selected>Select Unit</option>
                            <option value="Tagum Unit">Tagum Unit</option>
                            <option value="Mabini Unit">Mabini Unit</option>
                          </select>
                        </div> -->

                        <div>
                            <label class="text-xs text-text mb-1">Hire Date<span class="text-secondary">*</span></label>
                            <input type="date" name="hire_date" :value="selected.hire_date || ''" max="<?= date('Y-m-d') ?>" class="w-full input-field"/>
                        </div>
                      <!-- </form> -->
                    </div>
                  </div>


                  <!-- Action Buttons -->
                  <div class="flex justify-center gap-2 pt-4">
                    <button type="button" @click="showModal = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" name="add_driver" class="btn btn-primary px-7">Save</button>
                  </div>
                  </form>
                </main>
              </div>
            </div>
          </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto h-[578px] overflow-y-auto rounded-b-lg shadow bg-white">
          <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg p-2">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">&nbsp;</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Driver ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              </tr>
            </thead>
            <tbody id="usersTable" class="text-sm">
              
            <?php if (!empty($drivers)): ?>
              <?php foreach ($drivers as $person): ?>

                <tr 
                  data-staffid="<?= htmlspecialchars($person['driver_id']) ?>"
                  data-firstname="<?= htmlspecialchars($person['firstName']) ?>"
                  data-lastname="<?= htmlspecialchars($person['lastName']) ?>"
                  @click="selected = {
                      staff_id: '<?= htmlspecialchars($person['driver_id']) ?>',
                      firstName: '<?= htmlspecialchars($person['firstName']) ?>',
                      lastName: '<?= htmlspecialchars($person['lastName']) ?>',
                      contact: '<?= htmlspecialchars($person['contact']) ?>',
                      hire_date: '<?= htmlspecialchars($person['hire_date']) ?>',
                      status: '<?= htmlspecialchars($person['status']) ?>',
                      profile_picture: '<?= !empty($person['profile_picture']) ? $person['profile_picture'] : null ?>'
                  }; showDetails = true"
                  class="cursor-pointer hover:bg-gray-100 border-b border-gray-100"
                >
                  <td class="pl-4 py-2">
                    <img src="<?= !empty($person['profile_picture']) 
                                  ? '/public/uploads/profile_pics/' . $person['profile_picture'] 
                                  : '/public/assets/img/user-default.png' ?>"
                        alt="User" class="size-8 rounded-full object-cover">
                  </td>
                  <td class="px-4 py-2">
                    <?= htmlspecialchars($person['driver_id']) ?>
                  </td>
                  <td class="px-4 py-2">
                    <?= htmlspecialchars($person['firstName'] . ' ' . $person['lastName']) ?>
                  </td>
                  <td class="px-4 py-2">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full <?= strtolower($person['status']) === 'Available' ? 'bg-gray-200 text-gray-600' : 'bg-green-200 text-green-800 ' ?>">
                      <?= htmlspecialchars($person['status']) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">No driver records found.</td>
              </tr>
            <?php endif; ?>
          </tbody>

          </table>
          </div>
        </div>

        <!-- Right Section (Details) -->
        <div x-show="showDetails" x-cloak
            class="bg-white shadow rounded-lg p-4 max-h-[640px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>

          <h2 class="text-lg font-bold mb-2">Driver Information</h2>

          <!-- Profile Picture -->
          <!-- <img id="profile-preview"  
            :src="selected.profile_picture ? '/public/uploads/profile_pics/' + selected.profile_picture : '/public/assets/img/user-default.png'"
            alt=""
            class="w-24 h-24 rounded-full object-cover shadow-sm mx-auto"
          /> -->

          <!-- Form -->
          <form id="driverForm"   class="space-y-2"  method="post" action="../../../controllers/DriverController.php">
            <div class="rounded-xl flex flex-col items-center">
              <div class="relative">
                <!-- Profile Picture Preview -->
              <img 
                  id="profile-preview"
                  :src="selected.profile_picture 
                      ? '/public/uploads/profile_pics/' + selected.profile_picture 
                      : '/public/assets/img/user-default.png'"
                  alt="Profile Picture"
                  class="w-24 h-24 rounded-full object-cover shadow-sm"
              />
                <!-- Edit Button -->
                <label for="profile_picture" title="Change Profile Picture"
                  class="absolute bottom-2 right-2 bg-primary text-white p-1 rounded-full shadow-md cursor-pointer transition hover:bg-primary/80">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536m-2.036-5.036
                        a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                  </svg>
                </label>

                <!-- Hidden File Input -->
                <input 
                  type="file" 
                  id="profile_picture" 
                  name="profile_picture" 
                  accept="image/*" 
                  class="hidden" 
                  onchange="previewProfile(event)">
              </div>
            </div>
            <div>
              <label class="text-xs text-text mb-1">Staff ID No.</label>
              <input type="text" id="staff_id" name="staff_id" :value="selected.staff_id || ''" class="w-full input-field" readonly/>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="text-xs text-text mb-1">First Name</label>
                <input type="text" id="first_name" name="first_name" :value="selected.firstName || ''" class="w-full input-field"/>
              </div>
              <div>
                <label class="text-xs text-text mb-1">Last Name</label>
                <input type="text" id="last_name" name="last_name" :value="selected.lastName || ''" class="w-full input-field"/>
              </div>
            </div>

            <div>
                <label class="text-xs text-text mb-1">Contact No.</label>
                <input type="text" id="contact_no" name="contact_no" :value="selected.contact || ''" class="w-full input-field"/>
            </div>
            
            <div>
                <label class="text-xs text-text mb-1">Hire Date</label>
                <input 
                    type="date" 
                    name="hire_date" 
                    :value="selected.hire_date || ''" 
                    max="<?= date('Y-m-d') ?>" 
                    class="w-full input-field"
                    :disabled="access_level == 3"
                />
            </div>

            <div class="flex justify-center gap-2 pt-2">
              <!-- <button type="button" 
                title="Work History" 
                class="btn btn-secondary" 
                @click="viewWorkHistory(selected.staff_id)">
                <img src="/public/assets/img/work-history.png" class="size-4" alt="work history">
              </button> -->
                <button type="submit" name="update_driver"  class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
  </main>

  <script type="module">
    import { initTableFilters } from "/public/assets/js/shared/table-filters.js";

    initTableFilters({
      tableId: "usersTable",
      searchId: "searchUser",
      filterId: "statusFilter",  
      sortId: "sortUsers",          
      searchColumns: [2, 4],         
      filterColumn: 3             
    });

      function previewProfile(event) {
        const output = document.getElementById('profile-preview');
        output.src = URL.createObjectURL(event.target.files[0]);
      }
  </script>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/export.js"></script>
</html>
