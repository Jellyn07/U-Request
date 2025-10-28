<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/RequestController.php';
$controller = new RequestController();
$data = $controller->indexVehicle();
$requests = $data['requests'];
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
          <div class="overflow-x-auto h-[545px] overflow-y-auto rounded-b-lg shadow bg-white">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
              <thead class="bg-white sticky top-0">
                <tr>
                  <th class="pl-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tracking ID</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Requester</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Travel Date</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Travel Location</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Request</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Status</th>
                </tr>
              </thead>
              <tbody id="requestsTable" class="text-sm">
                <?php if (!empty($requests)): ?>
                  <?php foreach ($requests as $row): ?>
                    <tr 
                      class="border-b hover:bg-gray-100 cursor-pointer"
                      data-status="<?= htmlspecialchars($row['req_status']) ?>"
                      data-date="<?= htmlspecialchars(date('Y-m-d', strtotime($row['date_request']))) ?>"
                      @click='selectRow({
                          control_no: "<?= htmlspecialchars($row['control_no'], ENT_QUOTES) ?>",
                          tracking_id: "<?= htmlspecialchars($row['tracking_id'], ENT_QUOTES) ?>",
                          requester_name: "<?= htmlspecialchars($row['requester_name'], ENT_QUOTES) ?>",
                          travel_destination: "<?= htmlspecialchars($row['travel_destination'], ENT_QUOTES) ?>",
                          date_request: "<?= htmlspecialchars(date('M d, Y', strtotime($row['date_request'])), ENT_QUOTES) ?>",
                          travel_date: "<?= htmlspecialchars(date('M d, Y', strtotime($row['travel_date'])), ENT_QUOTES) ?>",
                          return_date: "<?= htmlspecialchars(date('M d, Y', strtotime($row['return_date'])), ENT_QUOTES) ?>",
                          depret_time: "<?= htmlspecialchars(date('h:i A', strtotime($row['departure_time'])) . ' - ' . date('h:i A', strtotime($row['return_time'])), ENT_QUOTES) ?>",
                          trip_purpose: "<?= htmlspecialchars($row['trip_purpose'], ENT_QUOTES) ?>",
                          req_status: "<?= htmlspecialchars($row['req_status'], ENT_QUOTES) ?>",
                          passenger_count: "<?= count($row['passengers']) ?>",
                          passengers: <?= htmlspecialchars(json_encode($row['passengers'] ?? []), ENT_QUOTES) ?>
                      })'>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['tracking_id']) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['requester_name']) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars(date('M d, Y', strtotime($row['travel_date']))) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['travel_destination']) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars(date('M d, Y', strtotime($row['date_request']))) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['req_status']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="6" class="text-center py-3 text-gray-400">No vehicle requests found</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- RIGHT SECTION -->
        <div x-show="showDetails" x-transition x-cloak
             class="bg-white shadow rounded-lg p-4 max-h-[607px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>

          <form id="assignmentForm" class="space-y-1" method="post" action="../../../controllers/RequestController.php">
            <input type="hidden" name="action" value="saveAssignment">
            <input type="hidden" name="req_id" x-model="selected.req_id">

            <h2 class="text-lg font-bold mb-2">Vehicle Request Information</h2>

            <div>
              <label class="text-xs text-text mb-1">Tracking No.</label>
              <input type="text" class="w-full view-field"  x-model="selected.tracking_id" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Request Date</label>
              <input type="text" class="w-full view-field"  
              :value="new Date(selected.date_request).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: '2-digit' 
              })" 
              readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Requester</label>
              <input type="text" class="w-full view-field" x-model="selected.requester_name" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Travel Date</label>
              <input type="text" class="w-full view-field" x-model="selected.travel_date" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Destination</label>
              <input type="text" class="w-full view-field" x-model="selected.travel_destination" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Trip Purpose</label>
              <input type="text" class="w-full view-field" x-model="selected.trip_purpose" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">No of Passengers</label>
              <input type="text" class="w-full view-field" x-model="selected.passenger_count" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Assign Vehicle</label>
              <select id="vehicleSelect" class="w-full input-field"></select>
            </div>

            <div>
              <label class="text-xs text-text mb-1">Assign Driver</label>
              <select id="staffSelect" class="w-full input-field"></select>
            </div>

            <div class="flex justify-center pt-2 space-x-2">
              <button type="button" class="btn btn-primary"
                      @click="viewFullDetails(selected)">
                Full Details
              </button>
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

<script>
fetch('../../../controllers/VehicleRequestController.php?vehicles=1')
  .then(res => res.json())
  .then(data => {
    const vehicleSelect = document.getElementById('vehicleSelect');
    vehicleSelect.innerHTML = '<option value="">No Vehicle Assigned</option>';
    data.forEach(v => {
      const opt = document.createElement('option');
      opt.value = v.vehicle_id;
      opt.textContent = v.vehicle_name;
      vehicleSelect.appendChild(opt);
    });
  });

fetch('../../../controllers/VehicleRequestController.php?drivers=1')
  .then(res => res.json())
  .then(data => {
    const staffSelect = document.getElementById('staffSelect');
    staffSelect.innerHTML = '<option value="">No Assigned Driver</option>';
    data.forEach(p => {
      const opt = document.createElement('option');
      opt.value = p.driver_id; // ✅ corrected
      opt.textContent = p.full_name;
      staffSelect.appendChild(opt);
    });
  });
  document.addEventListener("alpine:init", () => {
  Alpine.data("requestList", () => ({
    showDetails: false,
    selected: {},

    selectRow(request) {
      this.selected = request;
      this.showDetails = true;
    },

    viewFullDetails(selected) {
      Swal.fire({
        html: `
          <div class="text-left text-sm max-w-full overflow-x-auto">
            <h2 class="text-base font-bold mb-2">Vehicle Request Details</h2>

            <div class="mb-2"><label class="text-xs">Tracking No.</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.tracking_id}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Request Date</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.date_request}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Requester</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.requester_name}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Requester Contact No</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.contact_no}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Travel Date</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.travel_date}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Return Travel Date</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.return_date}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Destination</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.travel_destination}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Trip Purpose</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.trip_purpose}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Departure and Return Time</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.depret_time || 'N/A'}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Passengers</label>
              <ul class="border px-2 py-1 rounded text-sm max-h-40 overflow-y-auto">
                ${selected.passengers && selected.passengers.length > 0 
                  ? selected.passengers.map(p => `<li>${p.name || p}</li>`).join('') 
                  : '<li>No Passengers</li>'}
              </ul>
            </div>

            <div class="mb-2"><label class="text-xs">Assigned Vehicle</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.vehicle_name || 'Not Assigned'}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Assigned Driver</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.full_name || 'Not Assigned'}" readonly />
            </div>

            <div class="mb-2"><label class="text-xs">Status</label>
              <input type="text" class="w-full border px-2 py-1 rounded text-sm" value="${selected.req_status}" readonly />
            </div>

          </div>
        `,
        width: 600,
        confirmButtonText: 'Close',
        confirmButtonColor: '#800000'
      });
    }
  }));
});

</script>

</script>
  <!-- Table Filters -->
  <script type="module">
  import { initTableFilters } from "/public/assets/js/shared/table-filters.js";

    document.addEventListener("DOMContentLoaded", () => {
    initTableFilters({
    tableId: "requestsTable",
    searchId: "searchRequests",
    sortId: "sortCategory",
    searchColumns: [0, 1, 3],
    statusTabs: "#tabs button",
    dateColumnIndex: 4
  });
});
</script>

  <script src="/public/assets/js/shared/menus.js"></script>
</body>
</html>
