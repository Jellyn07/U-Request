<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
require_once __DIR__ . '/../../../controllers/AdminController.php';
require_once __DIR__ . '/../../../controllers/LocationController.php';

$locationController = new LocationController();
$locations = $locationController->getAllLocations();
$building = $locationController->getAllBuildings();
$locationController = new LocationController();
$locationController->addLocation($_POST);

$controller = new AdminController();
$admins = $controller->getAllAdmins();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Locations</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body class="bg-gray-200">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Campus Locations</h1>

      <div x-data="{ showDetails: false, selected: {}, addLocation: false }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <input type="text" id="searchInput" placeholder="Search Location" class="flex-1 min-w-[200px] input-field">
            <select class="input-field" id="sortFilter">
              <option value="id">By ID</option>
              <option value="az">A - Z</option>
              <option value="za">Z - A</option>
            </select>
            <!-- <button title="Print data in the table" id="print" class="input-field">
                <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
            </button> -->
            <img id="logo" src="/public/assets/img/usep.png" class="hidden">
            <button title="Export" id="export" class="btn-upper">
                <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
            <!-- Add New Location Modal Trigger -->
            <button @click="addLocation = true" title="Add new location" class="btn btn-secondary py-3">
              <img src="/public/assets/img/add.png" alt="Add" class="size-2.5">
            </button>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto h-[580px] overflow-y-auto rounded-b-lg shadow bg-white">
            <table id="table" class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
              <thead class="bg-white sticky top-0">
                <tr>
                  <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                  <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Added</th>
                  <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Building</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Exact Location</th>
                </tr>
              </thead>
              <tbody id="body_table" class="text-sm">
                <?php foreach ($locations as $row): ?>
                  <?php 
                    $rowJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                  ?>
                  <tr 
                    @click="selected = <?= $rowJson ?>; showDetails = true"
                    class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100"
                  >
                    <!-- Hidden ID column -->
                    <td class="pl-8 py-3" x-text="<?= htmlspecialchars($row['location_id']) ?>"></td>

                    <td class="pl-8 py-3"><?= htmlspecialchars(date('M d, Y', strtotime($row['date_added']))) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['unit']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['building']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['exact_location']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Right Section (Details Panel) -->
        <div x-show="showDetails" x-cloak
          class="bg-white shadow rounded-lg p-4 max-h-[640px] overflow-y-auto">
          
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>

          <form 
            id="locationForm" 
            method="post" 
            action="../../../controllers/LocationController.php" 
            class="space-y-5 mt-6"
          >
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="location_id" :value="selected.location_id">
            

            <h2 class="text-lg font-bold">Location Information</h2>

            <div>
              <label class="text-xs text-text mb-1">Date Added</label>
              <input type="text" name="date_added" :value="selected.date_added || ''" class="w-full view-field" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Unit</label>
              <input type="text" name="unit" :value="selected.unit || ''" class="w-full input-field" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Building</label>
              <input type="text" name="building" :value="selected.building || ''" class="w-full input-field" />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Exact Location</label>
              <input type="text" name="exact_location" :value="selected.exact_location || ''" class="w-full input-field" />
            </div>

            <div class="flex justify-center gap-2 pt-4">
              <!-- Delete -->
              <button 
                type="button" 
                class="btn btn-secondary"
                @click="deleteLocation(selected.location_id)"
              >
                <img src="/public/assets/img/delete.png" class="size-4" alt="Delete">
              </button>

              <!-- Save Changes -->
              <button type="button" class="btn btn-primary" onclick="confirmUpdate()">
                Save Changes </button>
            </div>
          </form>
        </div>

        <!-- Add New Location Modal -->
        <div  x-show="addLocation" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-auto">
          <div class="bg-white rounded-xl shadow-xl w-90% md:w-1/4 mx-auto relative overflow-auto">
            <main class="flex flex-col transition-all duration-300 p-4 space-y-4 px-5">
              <form id="addLocationForm" method="post" action="../../../controllers/LocationController.php" class="space-y-4">
                <input type="hidden" name="action" value="add">
                <h2 class="text-base font-medium mb-3">Add Location</h2>

                <!-- UNIT -->
                <div>
                  <label class="text-xs text-text mb-1">Unit</label>
                  <select name="unit" class="w-full input-field" required>
                    <option value="">Select Unit</option>
                    <option value="Tagum Unit">Tagum Unit</option>
                    <option value="Mabini Unit">Mabini Unit</option>
                  </select>
                </div>

                <!-- BUILDING SELECTION -->
                <div class="space-y-2">
                  <h2 class="text-xs text-text mb-1 mt-2">Select Building</h2>

                  <div class="flex items-center gap-4 pl-2">
                    <label class="flex items-center gap-2">
                      <input 
                        type="radio" 
                        name="buildingOption" 
                        value="existing" 
                        checked 
                        onclick="toggleBuildingOption('existing')"
                      >
                      <span class="text-xs">Choose Existing</span>
                    </label>
                    <label class="flex items-center gap-2">
                      <input 
                        type="radio" 
                        name="buildingOption" 
                        value="new" 
                        onclick="toggleBuildingOption('new')"
                      >
                      <span class="text-xs">Add New</span>
                    </label>
                  </div>

                  <!-- EXISTING BUILDING DROPDOWN -->
                  <div id="existingBuilding">
                    <label class="text-xs text-text mb-1">Existing Building</label>
                    <select name="existing_building" class="w-full input-field">
                      <option value="">Select Building</option>
                      <?php foreach ($building as $b): ?>
                        <option value="<?= htmlspecialchars($b['building']) ?>">
                          <?= htmlspecialchars($b['building']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- NEW BUILDING INPUT -->
                  <div id="newBuilding" class="hidden">
                    <label class="text-xs text-text mb-1">New Building Name</label>
                    <input 
                      type="text" 
                      name="new_building" 
                      class="w-full input-field" 
                      placeholder="Enter building name"
                    >
                  </div>
                </div>

                <!-- EXACT LOCATION -->
                <div>
                  <label class="text-xs text-text mb-1">Exact Location</label>
                  <input 
                    type="text" 
                    name="exact_location" 
                    class="w-full input-field" 
                    required
                  />
                </div>

                <!-- BUTTONS -->
                <div class="flex justify-center gap-2 pt-4">
                  <button 
                    type="button" 
                    @click="addLocation = false" 
                    class="btn btn-secondary"
                  >
                    Cancel
                  </button>
                  <button 
                    type="submit" 
                    class="btn btn-primary px-7"
                  >
                    Save
                  </button>
                </div>
              </form>
            </main>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="/public/assets/js/shared/menus.js"></script>
  <script src="/public/assets/js/shared/search.js"></script>          
  <script src="/public/assets/js/gsu_admin/location.js"></script>  
  <script src="/public/assets/js/shared/export.js"></script>
</body>
</html>