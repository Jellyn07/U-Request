<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/PersonnelController.php';

$controller = new PersonnelController(); $personnels = $controller->getAllPersonnel();
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
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/helpers.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  
  <?php
  // ✅ Pass PHP session values into JavaScript after scripts are loaded
  if (isset($_SESSION['personnel_success'])) {
      echo "<script>window.personnelSuccess = " . json_encode($_SESSION['personnel_success']) . "; console.log('Personnel Success:', window.personnelSuccess);</script>";
      unset($_SESSION['personnel_success']);
  }

  if (isset($_SESSION['personnel_error'])) {
      echo "<script>window.personnelError = " . json_encode($_SESSION['personnel_error']) . "; console.log('Personnel Error:', window.personnelError);</script>";
      unset($_SESSION['personnel_error']);
  }
  ?>
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/gsu_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Personnels</h1>
      <div x-data="{ showDetails: false, selected: {} }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="searchUser" placeholder="Search by name" class="flex-1 min-w-[200px] input-field">
            <select class="input-field">
                <option value="all">All</option>
                <option value="available">Available</option>
                <option value="fixing">Fixing</option>
            </select>
            <select class="input-field" id="sortUsers">
                <option value="az">Sort A-Z</option>
                <option value="za">Sort Z-A</option>
            </select>
            <button title="Print data in the table" class="input-field">
                <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
            </button>
            <button class="input-field" title="Export to Excel">
                <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
            <!-- Add Admin Modal -->
                <div x-data="{ showModal: false }">
            <!-- Trigger Button (example only) -->
            <button @click="showModal = true" title="Add new personnel" class="btn btn-secondary">
                <img src="/public/assets/img/add-admin.png" alt="User" class="size-4 my-0.5">
            </button>

            <!-- Modal Background -->
            <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-auto">
              <div class="bg-white rounded-xl shadow-xl w-90% md:w-1/3 mx-auto relative overflow-auto">
                <!-- Modal Content -->
                <main class="flex flex-col transition-all duration-300 p-4 space-y-4 px-5">
                  <!-- Profile Picture -->
                  <form method="post" action="../../../controllers/PersonnelController.php" enctype="multipart/form-data">
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
                      <h2 class="text-base font-medium">Personnel Credentials</h2>
                      <!-- <form class="space-y-4" method="post"> -->
                        <div>
                          <label class="text-xs text-text mb-1">Staff ID No.<span class="text-secondary">*</span></label>
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

                        <div>
                          <label class="text-xs text-text mb-1">Unit<span class="text-secondary">*</span></label>
                          <select name="access_level" class="w-full input-field" required>
                            <option value="" disabled selected>Select Unit</option>
                            <option value="tagum">Tagum Unit</option>
                            <option value="mabini">Mabini Unit</option>
                          </select>
                        </div>

                        <div>
                          <label class="text-xs text-text mb-1">Department<span class="text-secondary">*</span></label>
                          <select name="department" class="w-full input-field" required>
                            <option value="" disabled selected>Select department</option>
                            <option value="Janitorial">Janitorial</option>
                            <option value="Utility">Utility</option>
                            <option value="Landscaping">Landscaping</option>
                            <option value="Ground Maintenance">Ground Maintenance</option>
                            <option value="Building Repair And Maintenance">Building Repair And Maintenance</option>
                          </select>
                        </div>

                        <div>
                            <label class="text-xs text-text mb-1">Hire Date<span class="text-secondary">*</span></label>
                            <input type="date" name="hire_date" required class="w-full input-field"/>
                        </div>
                      <!-- </form> -->
                    </div>
                  </div>


                  <!-- Action Buttons -->
                  <div class="flex justify-center gap-2 pt-4">
                    <button type="button" @click="showModal = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" name="add_personnel" class="btn btn-primary px-7">Save</button>
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
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">&nbsp;</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Staff ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Department</th>
              </tr>
            </thead>
            <tbody id="usersTable" class="text-sm">
              
            <?php if (!empty($personnels)): ?>
              <?php foreach ($personnels as $person): ?>

                <tr 
                  data-staffid="<?= htmlspecialchars($person['staff_id']) ?>"
                  data-firstname="<?= htmlspecialchars($person['firstName']) ?>"
                  data-lastname="<?= htmlspecialchars($person['lastName']) ?>"
                  @click="selected = {
                      staff_id: '<?= htmlspecialchars($person['staff_id']) ?>',
                      firstName: '<?= htmlspecialchars($person['firstName']) ?>',
                      lastName: '<?= htmlspecialchars($person['lastName']) ?>',
                      department: '<?= htmlspecialchars($person['department']) ?>',
                      contact: '<?= htmlspecialchars($person['contact']) ?>',
                      hire_date: '<?= htmlspecialchars($person['hire_date']) ?>',
                      unit: '<?= htmlspecialchars($person['unit']) ?>',
                      status: '<?= !empty($person['status']) ? htmlspecialchars($person['status']) : "Available" ?>',
                      profile_pic: '/public/assets/img/user-default.png'
                  }; showDetails = true"
                  class="cursor-pointer hover:bg-gray-100"
                >
                  <td class="pl-4 py-2">
                    <img src="/public/assets/img/user-default.png" 
                        alt="User" 
                        class="size-8 rounded-full object-cover">
                  </td>
                  <td class="px-4 py-2">
                    <?= htmlspecialchars($person['staff_id']) ?>
                  </td>
                  <td class="px-4 py-2">
                    <?= htmlspecialchars($person['firstName'] . ' ' . $person['lastName']) ?>
                  </td>
                  <td class="px-4 py-2 text-green-800">
                    <?= !empty($person['status']) ? htmlspecialchars($person['status']) : 'Available' ?>
                  </td>
                  <td class="px-4 py-2">
                    <?= htmlspecialchars($person['department']) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">No personnel records found.</td>
              </tr>
            <?php endif; ?>
          </tbody>

          </table>
          </div>
        </div>

        <!-- Right Section (Details) -->
        <div x-show="showDetails" x-cloak
            class="bg-white shadow rounded-lg p-4 max-h-[630px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>

          <h2 class="text-lg font-bold mb-2">Personnel Information</h2>

          <!-- Profile Picture -->
          <img id="profile-preview"  
            :src="selected.profile_picture ? '/public/uploads/profile_pics/' + selected.profile_picture : '/public/assets/img/user-default.png'"
            alt=""
            class="w-24 h-24 rounded-full object-cover shadow-sm mx-auto"
          />

          <!-- Form -->
          <form id="personnelForm" class="space-y-2" method="post" action="../../../controllers/PersonnelController.php" >
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
                <label class="text-xs text-text mb-1">Unit</label>
                <select name="unit" class="w-full input-field">
                  <option value="Tagum Unit" :selected="selected.unit === 'Tagum Unit'">Tagum Unit</option>
                  <option value="Mabini Unit" :selected="selected.unit === 'Mabini Unit'">Mabini Unit</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-text mb-1">Department</label>
                <select name="department" class="w-full input-field">
                  <option value="Janitorial" :selected="selected.department === 'Janitorial'">Janitorial</option>
                  <option value="Utility" :selected="selected.department === 'Utility'">Utility</option>
                  <option value="Landscaping" :selected="selected.department === 'Landscaping'">Landscaping</option>
                  <option value="Ground Maintenance" :selected="selected.department === 'Ground Maintenance'">Ground Maintenance</option>
                  <option value="Building Repair And Maintenance" :selected="selected.department === 'Building Repair And Maintenance'">Building Repair And Maintenance</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-text mb-1">Hire Date</label>
                <input type="date" name="hire_date" :value="selected.hire_date || ''" class="w-full input-field"/>
            </div>

            <div class="flex justify-center gap-2 pt-2">
              <button type="button" title="Work History" class="btn btn-secondary">
                <img src="/public/assets/img/work-history.png" class="size-4" alt="work history">
              </button>
                <button type="submit" name="update_personnel"  class="btn btn-primary">Save Changes</button>
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
      const rows = document.querySelectorAll('#personnelTable tr');
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
  </script>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
</html>
