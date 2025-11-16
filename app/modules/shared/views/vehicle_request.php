<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: /app/modules/shared/views/admin_login.php");
    exit;
}
require_once __DIR__ . '/../../../config/auth-admin.php';
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
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/motorpool_admin/request.js"></script>
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
      <h1 class="text-2xl font-bold mb-4">Request</h1>

      <!-- Tabs -->
      <div id="tabs" class="flex gap-2 mt-4 text-xs text-text">
        <button class="ml-5 btn"><p>All</p></button>
        <button class="btn"><p>Pending</p></button>
        <button class="btn"><p>Approved</p></button>
        <button class="btn"><p>On Going</p></button>
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
            <!-- <button title="Print data in the table" class="input-field">
              <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
            </button> -->
            <img id="logo" src="/public/assets/img/usep.png" class="hidden">
            <button title="Export" id="export" class="btn-upper">
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
                          contact: "<?= htmlspecialchars($row['contact'], ENT_QUOTES) ?>",
                          travel_destination: "<?= htmlspecialchars($row['travel_destination'], ENT_QUOTES) ?>",
                          date_request: "<?= htmlspecialchars(date('M d, Y', strtotime($row['date_request'])), ENT_QUOTES) ?>",
                          travel_date: "<?= htmlspecialchars(date('M d, Y', strtotime($row['travel_date'])), ENT_QUOTES) ?>",
                          return_date: "<?= htmlspecialchars(date('M d, Y', strtotime($row['return_date'])), ENT_QUOTES) ?>",
                          depret_time: "<?= htmlspecialchars(date('h:i A', strtotime($row['departure_time'])) . ' - ' . date('h:i A', strtotime($row['return_time'])), ENT_QUOTES) ?>",
                          trip_purpose: "<?= htmlspecialchars($row['trip_purpose'], ENT_QUOTES) ?>",
                          req_status: "<?= htmlspecialchars($row['req_status'], ENT_QUOTES) ?>",
                          original_status: "<?= htmlspecialchars($row['req_status'], ENT_QUOTES) ?>",
                          reason: "<?= htmlspecialchars($row['reason'] ?? '', ENT_QUOTES) ?>",
                          vehicle_name: "<?= htmlspecialchars($row['vehicle_name'] ?? '', ENT_QUOTES) ?>",
                          driver_name: "<?= htmlspecialchars($row['driver_name'] ?? '', ENT_QUOTES) ?>",
                          approved_by: "<?= htmlspecialchars($row['approved_by'] ?? '', ENT_QUOTES) ?>",
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

          <form id="assignmentForm" class="space-y-1" method="post" 
            action="../../../controllers/VehicleRequestController.php"
            x-data="{ 
                isSuperadmin: <?= ($_SESSION['access_level'] == 1 ? 'true' : 'false') ?>,

                isLocked() {
                    return this.isSuperadmin ||
                      this.selected.original_status === 'Rejected/Cancelled' ||
                      this.selected.original_status === 'Completed';
                }

            }">
              <input type="hidden" name="form_action" value="saveAssignment">
              <input type="hidden" name="control_no" x-model="selected.control_no">

              <h2 class="text-lg font-bold mb-2">Vehicle Request Information</h2>

              <div>
                <label class="text-xs text-text mb-1">Tracking No.</label>
                <input type="text" class="w-full view-field" x-model="selected.tracking_id" readonly />
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

              <!-- VEHICLE DROPDOWN -->
              <div x-data="vehicleDropdown" x-init="init()">
                <label class="text-xs text-text mb-1">Assign Vehicle</label>

                <select 
                  id="vehicleSelect"
                  name="vehicle_id"
                  x-model="selected.vehicle_id"
                  class="w-full input-field"
                  :disabled="isLocked()"
                >
                  <option value="">Select Vehicle</option>

                  <template x-for="v in vehicles" :key="v.vehicle_id">
                    <option :value="v.vehicle_id" x-text="v.vehicle_name"></option>
                  </template>
                </select>

                <p class="text-xs text-gray-500 mt-1">
                  Current Assigned Vehicle:
                  <span x-text="selected.vehicle_name"></span>
                </p>
              </div>

              <!-- STATUS + APPROVED BY + REASON -->
              <div>
                <!-- STATUS SELECT -->
                <select 
                  id="status"  
                  name="req_status"  
                  x-model="selected.req_status" 
                  class="w-full input-field"
                  :disabled="isLocked()"
                >
                  <option value="" disabled>Select Status</option>
                  <option value="Pending">Pending</option>
                  <option value="Approved">Approved</option>
                  <option value="On Going">On Going</option>
                  <option value="Rejected/Cancelled">Rejected/Cancelled</option>
                  <option value="Completed">Completed</option>
                </select>

                <!-- APPROVED BY -->
                <div x-show="selected.req_status === 'Approved'" x-cloak>
                  <label class="text-xs text-text mb-1 mt-2">Approved By</label>
                  <input 
                    type="text"
                    id="approvedBy"
                    name="approved_by"
                    x-model="selected.approved_by"
                    class="w-full input-field"
                    placeholder="e.g., Dr. Shirley Villanueva"
                    :readonly="isLocked()"
                  />
                </div>

                <!-- REASON TEXTAREA -->
                <div x-show="selected.req_status === 'Rejected/Cancelled'" x-cloak>
                  <label class="text-xs text-text mb-1 mt-2">Reason</label>
                  <textarea 
                    name="reason" 
                    x-model="selected.reason"
                    class="w-full input-field resize-none" 
                    rows="2"
                    placeholder="Enter reason for cancellation or rejection..."
                    :disabled="isLocked()"
                    required
                  ></textarea>
                </div>
              </div>

              <div class="flex justify-center pt-2 space-x-2">
                <button type="button" class="btn btn-primary"
                        @click="viewFullDetails(selected)">
                  Full Details
                </button>

                <button 
                  type="button" 
                  class="btn btn-primary" 
                  id="saveBtn"
                  :hidden="isLocked()"
                >
                  Save Changes
                </button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </main>
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
    <script>
  document.getElementById('saveBtn').addEventListener('click', async () => {
  const form = document.getElementById('assignmentForm');
  const formData = new FormData(form);

  const confirm = await Swal.fire({
    title: 'Confirm Save?',
    text: 'Do you want to save these changes?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes, Save it!'
  });
  if (!confirm.isConfirmed) return;

  Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

  try {
    const res = await fetch(form.action, { method: 'POST', body: formData });
    const text = await res.text();

    let data;
    try {
      data = JSON.parse(text);
    } catch (e) {
      Swal.close();
      console.error('Non-JSON response:', text);
      Swal.fire({ icon: 'error', title: 'Server error', html: `<pre>${text}</pre>` });
      return;
    }

    Swal.close();
    if (data.success) {
      Swal.fire({ icon: 'success', title: 'Saved!', text: data.message || 'Saved successfully.' });
    } else {
      Swal.fire({ icon: 'error', title: 'Failed', text: data.message || 'Unknown server error.' });
    }
  } catch (err) {
    Swal.close();
    console.error('Fetch error:', err);
    Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to connect to server.' });
  }
});

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.sendEmailBtn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault(); // stop form submission
      const controlNo = btn.dataset.controlNo;

      const confirm = await Swal.fire({
        title: 'Send Email?',
        text: `Do you want to notify Top Management about vehicle request ${controlNo}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, send it'
      });

      if (!confirm.isConfirmed) return;

      Swal.fire({
        title: 'Sending Email...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      try {
        const res = await fetch(`../../../controllers/VehicleController.php?send_email=1&control_no=${encodeURIComponent(controlNo)}`);
        const text = await res.text();

        let data;
        try {
          data = JSON.parse(text);
        } catch {
          throw new Error(text);
        }

        Swal.close();
        if (data.success) {
          Swal.fire({ icon: 'success', title: 'Sent!', text: data.message });
        } else {
          Swal.fire({ icon: 'error', title: 'Failed', text: data.message || 'Email failed to send.' });
        }
      } catch (err) {
        Swal.close();
        Swal.fire({ icon: 'error', title: 'Error', html: `<pre>${err.message}</pre>` });
      }
    });
  });
});
</script>
<script src="/public/assets/js/shared/export.js"></script>