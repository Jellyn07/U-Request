<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Requests</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/admin-user.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/helpers.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/shared/popup.js"></script>
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/motorpool_menu.php'; ?>

  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Request</h1>

      <!-- Tabs -->
      <div id="tabs" class="flex gap-2 mt-4 text-xs text-text">
        <button class="ml-5 btn"><p>All</p></button>
        <button class="btn"><p>Pending</p></button>
        <button class="btn"><p>Approved</p></button>
        <button class="btn"><p>In Progress</p></button>
        <button class="btn"><p>Rejected/Cancelled</p></button>
        <button class="btn"><p>Completed</p></button>
      </div>

      <!-- Main Content -->
      <div x-data="requestList()" class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- LEFT SECTION -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <!-- Search and Filters -->
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <input type="text" id="searchRequests" placeholder="Search by ID or Requester Name" class="flex-1 min-w-[200px] input-field">
            <select id="sortCategory" class="input-field">
              <option value="all">All Dates</option>
              <option value="today">Today</option>
              <option value="yesterday">Yesterday</option>
              <option value="7">Last 7 days</option>
              <option value="14">Last 14 days</option>
              <option value="30">Last 30 days</option>
            </select>
            <button title="Print data in the table" class="input-field">
              <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
            </button>
            <button class="input-field" title="Export to Excel">
              <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
          </div>

          <!-- TABLE -->
          <div class="overflow-x-auto h-[540px] overflow-y-auto rounded-b-lg shadow bg-white">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
              <thead class="bg-white sticky top-0">
                <tr>
                  <th class="pl-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Request ID</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Requester</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Travel Location</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Request</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Status</th>
                </tr>
              </thead>
              <tbody id="requestsTable" class="text-sm">
                <?php 
                for ($i = 0; $i < 15; $i++){
                  echo '
                    <tr 
                      class="border-b hover:bg-gray-100 cursor-pointer"
                      @click="selectRow({
                        request_id: \'REQ-00' . ($i+1) . '\',
                        tracking_id: \'TRK-000' . ($i+1) . '\',
                        Name: \'Jellyn Omo\',
                        request_Type: \'Electrical\',
                        location: \'PECC-002\',
                        request_date: \'2025-10-13\',
                        req_status: \'To Inspect\',
                        image_path: \'\',
                        priority_status: \'High\',
                        staff_id: \'\'
                      })"
                    >
                      <td class="px-4 py-3">TRK-000' . ($i+1) . '</td>
                      <td class="px-4 py-3">Jellyn Omo</td>
                      <td class="px-4 py-3">Electrical</td>
                      <td class="px-4 py-3">PECC-002</td>
                      <td class="px-4 py-3">Oct 13, 2025</td>
                      <td class="px-4 py-3">To Inspect</td>
                    </tr>                
                  ';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- RIGHT SECTION -->
        <div x-show="showDetails" x-transition x-cloak
             class="bg-white shadow rounded-lg p-4 max-h-[602px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>

          <form id="assignmentForm" class="space-y-1" method="post" action="../../../controllers/RequestController.php">
            <input type="hidden" name="action" value="saveAssignment">
            <input type="hidden" name="req_id" x-model="selected.req_id">

            <h2 class="text-lg font-bold mb-2">Request Information</h2>

            <div>
              <label class="text-xs text-text mb-1">Tracking No.</label>
              <input type="text" class="w-full view-field"  x-model="selected.tracking_id" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Request Date</label>
              <input type="text" class="w-full view-field"  
              :value="new Date(selected.request_date).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: '2-digit' 
              })" 
              readonly />
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
              <select name="prio_level" id="prioritySelect" class="w-full input-field" x-model="selected.priority_status">
                <option value="">No Priority Level</option>
                <option value="Low">Low</option>
                <option value="High">High</option>
              </select>
            </div>

            <div>
              <label class="text-xs text-text mb-1">Assign Personnel</label>
              <select name="staff_id" id="staffSelect" class="w-full input-field" x-model="selected.staff_id">
                <option value="">No Assigned Personnel</option>
                <?php if (isset($personnels)): ?>
                  <?php foreach ($personnels as $person): ?>
                    <option value="<?= $person['staff_id'] ?>">
                      <?= htmlspecialchars($person['full_name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="flex justify-center pt-2 space-x-2">
              <button type="button" class="btn btn-primary">Full Details</button>
              <button type="button" class="btn btn-primary" id="saveBtn" name="saveAssignment">
                Save Changes
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- Alpine.js Component -->
  <script>
    document.addEventListener("alpine:init", () => {
      Alpine.data("requestList", () => ({
        showDetails: false,
        selected: {},

        selectRow(request) {
          this.selected = request;
          this.showDetails = true;
        },
      }));
    });
  </script>

  <!-- Table Filters -->
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
        statusTabs: "#tabs button",
        dateColumnIndex: 4
      });
    });
  </script>

  <script src="/public/assets/js/shared/menus.js"></script>
</body>
</html>
