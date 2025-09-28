<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/RequestController.php';

$controller = new RequestController();
$data = $controller->index();
$requests = $data['requests'];
$personnels = $data['personnels'];
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
      <h1 class="text-2xl font-bold mb-4">Repair Request</h1>

        <div id="tabs" class="flex gap-2 mt-4 text-xs text-gray-700">
            <button class="ml-5 btn bg-white hover:bg-red-100 border border-gray-200 border-b-0 rounded-t-lg shadow-lg">
                <p>All</p>
            </button>
            <button class="btn">
                <p>To Inspect</p>
            </button>
            <button class="btn">
                <p>In Progress</p>
            </button>
            <button class="btn">
                <p>Completed</p>
            </button>
        </div>
      <div x-data="{ showDetails: false, selected: {}, addmaterial: false }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="searchRequests" placeholder="Search by ID or Requester Name" class="flex-1 min-w-[200px] input-field">
            <select class="input-field" id="filterCategory">
                <option value="all">All</option>
                <option>Carpentry/Masonry</option>
                <option>Welding</option>
                <option>Hauling</option>
                <option>Plumbing</option>
                <option>Landscaping</option>
                <option>Electrical</option>
                <option>Air-Condition</option>
                <option>Others</option>
            </select>
            <select id="sortCategory" class="input-field">
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
          </div>
        </div>

          <!-- Table -->
          <div class="overflow-x-auto max-h-[540px] overflow-y-auto rounded-b-lg shadow">
          <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-b-lg p-2">
            <thead class="bg-white sticky top-0">
              <tr>
                <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Request ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Requester</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Status</th>
              </tr>
            </thead>
            <tbody id="requestsTable" class="text-sm">
            <?php foreach ($requests as $row): ?>
                <tr 
                    data-category="<?= htmlspecialchars($row['request_Type']) ?>" 
                    data-status="<?= htmlspecialchars($row['req_status']) ?>" 
                    @click="selected = <?= htmlspecialchars(json_encode($row)) ?>; showDetails = true"
                    class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100"
                >
                    <td class="pl-8 py-3"><?= htmlspecialchars($row['request_id']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['Name']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['request_Type']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['location']) ?></td>
                    <td class="px-4 py-3">
                        <select class="px-2 py-1 rounded-full text-xs 
                            <?= $row['req_status'] === 'In Progress' ? 'bg-blue-100 text-blue-800' : 
                              ($row['req_status'] === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') ?>">
                            <option <?= $row['req_status'] === 'To Inspect' ? 'selected' : '' ?>>To Inspect</option>
                            <option <?= $row['req_status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                            <option <?= $row['req_status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
          </tbody>

          </table>
          </div>
        </div>

        <!-- Right Section (Details) -->
        <div x-show="showDetails" x-cloak
            class="bg-white shadow rounded-lg p-4 max-h-[602px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>
          
          <!-- Form -->
          <form id="adminForm" class="space-y-1" method="post">
            <h2 class="text-lg font-bold mb-2">Repair Information</h2>

            <img id="profile-preview"  
            :src="selected.profile_picture ? '/public/uploads/profile_pics/' + selected.profile_picture : '/public/assets/img/default-img.png'"
            alt=""
            class="w-10/12 shadow-lg mx-auto rounded-lg"
            />
            <input type="hidden" name="request_id" x-model="selected.request_id">
            <div>
              <label class="text-xs text-text mb-1">Tracking No.</label>
              <input type="text" class="w-full view-field"  x-model="selected.request_id" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Requester</label>
              <input type="text" class="w-full view-field" x-model="selected.Name" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Category</label>
              <input type="text" class="w-full view-field" x-model="selected.request_Type" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Location</label>
              <input type="text" class="w-full view-field" x-model="selected.location" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Priority Level</label>
              <select type="text" name="priority" class="w-full input-field">
                <option value="" selected>Select Priority Level</option>
                <option value="Low">Low</option>
                <option value="High">High</option>
              </select>
            </div>

            <div>
              <label class="text-xs text-text mb-1">Assign Personnel</label>
              <select name="staff_id" x-model="selected.staff_id" class="w-full input-field">
                  <option value="" disabled>Select Personnel</option>
                  <?php foreach ($personnels as $person): ?>
                      <option value="<?= $person['staff_id'] ?>">
                          <?= htmlspecialchars($person['full_name']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
            </div>

            <div class="flex justify-center pt-2">
                <!-- Use the request layout in user just add the completion date -->
                <button type="button" title="View all request information" class="btn btn-primary">Full Details</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </main>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
<script type="module">
  import { initTableFilters } from "/public/assets/js/shared/table-filters.js";

  document.addEventListener("DOMContentLoaded", () => {
    initTableFilters({
      tableId: "requestsTable",
      searchId: "searchRequests",
      filterId: "filterCategory",  
      sortId: "sortCategory",      
      searchColumns: [0, 1],       
      filterAttr: "data-category", 
      statusTabs: "#tabs button"   // ✅ NEW: listen for tab clicks
    });
  });
</script>



</html>