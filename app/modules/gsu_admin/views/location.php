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
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Campus Locations</h1>

      <div x-data="{ showDetails: false, selected: {}, addmaterial: false }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div class="col-span-3">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="search" placeholder="Search Location" class="flex-1 min-w-[200px] input-field">
            <select class="input-field">
                <option value="all">All Unit</option>
                <option>Tagum Unit</option>
                <option>Mabini Unit</option>
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
              <button @click="showModal = true" title="Add new material" class="btn btn-secondary py-3">
                <img src="/public/assets/img/add.png" alt="User" class="size-2.5">
              </button>

              <!-- Modal Background -->
              <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-auto">
                <div class="bg-white rounded-xl shadow-xl w-90% md:w-1/5 mx-auto relative overflow-auto">
                  <!-- Modal Content -->
                  <main class="flex flex-col transition-all duration-300 p-4 space-y-4 px-5">
                    <!-- Profile Picture -->
                    <form id="addMaterialForm" method="post" action="../../../controllers/MaterialController.php" enctype="multipart/form-data" required>
                      <!-- Identity Information -->
                      <div class="flex justify-center">
                        <div class="w-full">
                          <h2 class="text-base font-medium mb-3">Add Location</h2>
                          <div>
                            <label class="text-xs text-text mb-1">Unit<span class="text-secondary"></span></label>
                            <select class="w-full input-field">
                                <option>Tagum Unit</option>
                                <option>Mabini Unit</option>
                            </select>
                          </div>

                          <div class="space-y-2">
                            <h2 class="text-xs text-text mb-1 mt-2">Select Building</h2>
                            <!-- Radio buttons to choose mode -->
                            <div class="flex items-center gap-4 pl-2">
                                <label class="flex items-center gap-2">
                                <input type="radio" name="buildingOption" value="existing" checked
                                        onclick="toggleBuildingOption('existing')">
                                <span class="text-xs">Choose Existing</span>
                                </label>
                                <label class="flex items-center gap-2">
                                <input type="radio" name="buildingOption" value="new"
                                        onclick="toggleBuildingOption('new')">
                                <span class="text-xs">Add New</span>
                                </label>
                            </div>

                            <!-- Existing building dropdown -->
                            <div id="existingBuilding">
                                <label class="text-xs text-text mb-1">Existing Building</label>
                                <select class="w-full input-field">
                                <option value="">Select Building</option>
                                <option>Main Building</option>
                                <option>Science Hall</option>
                                <option>Library</option>
                                </select>
                            </div>

                            <!-- New building input -->
                            <div id="newBuilding" class="hidden">
                                <label class="text-xs text-text mb-1">New Building Name</label>
                                <input type="text" class="w-full input-field">
                            </div>
                            </div>


                          <div>
                            <label class="text-xs text-text mb-1">Exact Location<span class="text-secondary"></span></label>
                            <input type="text" class="w-full input-field" required />
                          </div>

                          <div class="flex justify-center gap-2 pt-4">
                              <button type="button" @click="showModal = false" class="btn btn-secondary">Cancel</button>
                              <button type="submit" name="add_material" class="btn btn-primary px-7">Save</button>
                          </div>
                        </div>
                      </div>
                    </form>


                    <!-- Action Buttons -->
                  </main>
                </div>
              </div>
          </div>
        </div>

          <!-- Table -->
          <div class="overflow-x-auto max-h-[580px] overflow-y-auto rounded-b-lg shadow">
          <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-b-lg p-2">
            <thead class="bg-white sticky top-0">
              <tr>
                <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Added</th>
                <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Building</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Exact Location</th>
              </tr>
            </thead>
            <tbody id="table" class="text-sm">
                <?php for($i=0; $i<20; $i++){
                    echo'
                        <tr class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100">
                            <td class="pl-8 py-3">Jan 07, 2025</td>
                            <td class="px-4 py-3">Tagum Unit</td>
                            <td class="px-4 py-3">PECC</td>
                            <td class="px-4 py-3">Clinic</td>
                        </tr>                    
                    ';
                } 
                ?>
            </tbody>
          </table>
          </div>
        </div>

      </div>
    </div>
  </div>
  </main>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/search.js"></script>

<script>
function toggleBuildingOption(option) {
  document.getElementById("existingBuilding").classList.toggle("hidden", option !== "existing");
  document.getElementById("newBuilding").classList.toggle("hidden", option !== "new");
}
</script>
</html>