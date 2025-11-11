<?php
// session_start();
// if (!isset($_SESSION['email'])) {
//     header("Location: /app/modules/shared/views/admin_login.php");
//     exit;
// }
// require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/RequestController.php';

$materialController = new RequestController();
$materials = $materialController->getAllMaterials();

$controller = new RequestController();
$data = $controller->index();
$requests = $data['requests'];

$model = new RequestModel();
$personnels = $model->getAvailableStaff();

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
  <title>U-Request | Repair Request</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/admin-user.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/shared/popup.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/gsu_admin/request.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
  
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/gsu_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Request</h1>

        <div id="tabs" class="flex gap-2 mt-4 text-xs text-text">
            <button class="ml-5 btn">
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
      <div 
        x-data="{ 
          showDetails: false, 
          selected: {}, 
          addmaterial: false,
          materials: [{ material_code: '', qty: 1 }],
          selectedMaterialCodes: [],
          updateMaterialOptions() {
            this.selectedMaterialCodes = this.materials
              .map(m => m.material_code)
              .filter(code => code !== '');
          }
        }" 
        class="grid grid-cols-1 md:grid-cols-3 gap-4"
      >
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
            <!-- Add Admin Modal -->
            <div x-data="{ showModal: false }">
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto h-[545px] overflow-y-auto rounded-b-lg shadow bg-white">
          <table class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
            <thead class="bg-white sticky top-0">
              <tr>
                <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Request ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Requester</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Request</th>
                <th class="px-4 py-2 ml-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Status</th>
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
                      <td class="px-4 py-3" data-date="<?= htmlspecialchars($row['request_date']) ?>">
                          <?= htmlspecialchars(date("F d, Y", strtotime($row['request_date']))) ?>
                      </td>
                      <td class="px-4 py-3">
                          <?php if ($row['req_status'] === 'Completed'): ?>
                              <!-- ✅ Show label only when Completed -->
                              <span class="px-5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                  Completed
                              </span>

                          <?php elseif ($row['req_status'] === 'To Inspect'): ?>
                              <!-- ✅ Dropdown for 'To Inspect' -->
                              <select 
                                  class="status-dropdown px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800" 
                                  data-request-id="<?= $row['request_id'] ?>"
                                  data-current-status="<?= $row['req_status'] ?>"
                              >
                                  <option class="hidden" disabled value="To Inspect" <?= $row['req_status'] === 'To Inspect' ? 'selected' : '' ?>>To Inspect</option>
                                  <option value="In Progress" class="bg-blue-100 text-blue-800">In Progress</option>
                                  <option value="Completed" class="bg-green-100 text-green-800">Completed</option>
                              </select>

                          <?php elseif (in_array($row['req_status'], ['In Progress', 'In progress'], true)): ?>
                              <!-- ✅ Dropdown for 'In Progress' -->
                              <select 
                                  class="status-dropdown px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800" 
                                  data-request-id="<?= $row['request_id'] ?>"
                                  data-current-status="<?= $row['req_status'] ?>"
                              >
                                  <option class="hidden" disabled value="In Progress" <?= $row['req_status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                  <option value="Completed" class="bg-green-100 text-green-800">Completed</option>
                              </select>

                          <?php else: ?>
                              <!-- Fallback for other statuses -->
                              <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">
                                  <?= htmlspecialchars($row['req_status']) ?>
                              </span>
                          <?php endif; ?>
                      </td>
                  </tr>
              <?php endforeach; ?>
          </tbody>
          </table>
          </div>
        </div>

        <!-- Right Section (Details) -->
        <div x-show="showDetails"   x-data="requestHandler()"  x-cloak
            class="bg-white shadow rounded-lg p-4 max-h-[602px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>
          
          <!-- Form -->
          <form id="assignmentForm" class="space-y-1" method="post" action="../../../controllers/RequestController.php">
            <input type="hidden" name="request_id" x-model="selected.request_id">
            <input type="hidden" name="action" value="saveAssignment">
            <input type="hidden" name="req_id" x-model="selected.req_id">
            <h2 class="text-lg font-bold mb-2">Repair Information</h2>
            <img id="profile-preview"
                :src="selected.image_path 
                      ? '/public/uploads/' + selected.image_path 
                      : '/public/assets/img/default-img.png'"
                @error="$el.src = '/public/assets/img/default-img.png'"
                alt="Preview"
                class="w-10/12 shadow-lg mx-auto rounded-lg"
            />
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

            <div x-data="{
                    editing: false,
                    locationValue: selected.location,
                    editLocation() {
                        this.editing = true;
                        this.locationValue = selected.location;
                    },
                    saveLocation() {
                        fetch('/../../../controllers/RequestController.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                action: 'updateLocation',
                                id: selected.request_id,
                                location: this.locationValue
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                selected.location = this.locationValue; // update the parent field
                                this.editing = false;
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: 'Location updated successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(() => Swal.fire('Error', 'Unable to connect to server.', 'error'));
                    }
                }" class="mb-4">
                
                <label class="text-xs text-text mb-1">Location</label>

                <!-- Display current location -->
                <div class="flex items-center justify-between">
                    <span x-text="selected.location" class="w-full view-field"></span>
                    <!-- <button class="text-blue-600 text-xs underline" @click="editLocation" x-show="!editing">
                        Edit Location
                    </button> -->
                </div>

                <!-- Editable input -->
                <div x-show="editing" class="flex items-center gap-2 mt-2">
                    <input type="text" x-model="locationValue" class="w-full input-field border rounded px-2 py-1" />
                    <button class="bg-green-600 text-white px-2 py-1 rounded text-xs" @click="saveLocation">
                        Save
                    </button>
                    <button class="bg-gray-300 text-black px-2 py-1 rounded text-xs" @click="editing = false">
                        Cancel
                    </button>
                </div>
            </div>

            <div>
                <label class="text-xs text-text mb-1">Priority Level</label>
                <select name="prio_level" id="prioritySelect" 
                        class="w-full input-field" 
                        x-model="selected.priority_status"
                        :disabled="selected.req_status === 'Completed'">

                    <!-- Fallback if no priority -->
                    <!-- <option value="" x-show="!selected.priority_status">No Priority Level</option> -->

                    <option value="Low" x-show="!selected.priority_status">Low</option>
                    <option value="High">High</option>
                </select>
            </div>

           <!-- Personnel Section -->
          <div
            x-data="{ 
                personnel: [{ staff_id: '' }],
                selectedStaffIds: [],
                assignedPersonnel: [],
                
                updatePersonnelOptions() {
                  this.selectedStaffIds = this.personnel
                    .map(p => p.staff_id)
                    .filter(id => id !== '');
                },
                async loadAssignedPersonnel(requestId) {
                  try {
                    const res = await fetch(`../../../controllers/RequestController.php?getAssignment=${requestId}`);
                    const data = await res.json();
                    if (Array.isArray(data) && data.length > 0) {
                      this.assignedPersonnel = data;
                      // If In Progress or Pending → populate for editing
                      if (selected.req_status !== 'Completed') {
                        this.personnel = data.map(p => ({ staff_id: p.staff_id }));
                      }
                    } else {
                      this.assignedPersonnel = [];
                      this.personnel = [{ staff_id: '' }];
                    }
                    this.updatePersonnelOptions();
                  } catch (err) {
                    console.error('❌ Failed to load personnel:', err);
                    }
                }
              }"
              x-init="loadAssignedPersonnel(selected.request_id)"
            >
            <!-- Editable Section (Pending + In Progress) -->
            <template x-if="selected.req_status !== 'Completed'">
              <div id="personnel-fields" class="space-y-3 mb-6">
                <label class="text-xs text-text mb-1">Assign / Edit Personnel</label>

                <!-- Editable Dropdowns -->
                <template x-for="(p, index) in personnel" :key="index">
                  <div class="flex gap-2 personnel-row items-end">
                    <!-- Dropdown -->
                    <div class="w-full">
                      <select 
                        :name="'staff_id[' + index + ']'"
                        x-model="p.staff_id"
                        @change="updatePersonnelOptions()"
                        class="input-field w-full staff-select"
                      >
                        <option value="">Select Personnel</option>
                        <?php foreach ($personnels as $person): ?>
                          <option 
                            value="<?= $person['staff_id'] ?>"
                            x-bind:disabled="selectedStaffIds.includes('<?= $person['staff_id'] ?>') && p.staff_id !== '<?= $person['staff_id'] ?>'">
                            <?= htmlspecialchars($person['full_name']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <!-- Add / Remove Buttons -->
                    <div class="flex items-center gap-1">
                      <button 
                        type="button"
                        class="bg-primary hover:bg-secondary text-white rounded-full w-9 h-9 flex justify-center shadow-md items-center"
                        @click="personnel.push({ staff_id: '' }); updatePersonnelOptions();"
                        title="Add Personnel">
                        <img src="<?php echo PUBLIC_URL; ?>/assets/img/add_white.png" alt="Add" class="w-3 h-3">
                      </button>

                      <button 
                        type="button"
                        class="bg-gray-700 hover:bg-gray-600 text-white rounded-full w-9 h-9 flex items-center justify-center"
                        @click="personnel.splice(index, 1); updatePersonnelOptions();"
                        title="Remove Personnel">
                        <img src="<?php echo PUBLIC_URL; ?>/assets/img/minus.png" alt="Minus" class="w-3 h-3">
                      </button>
                    </div>
                  </div>
                </template>

                <!-- Read-only List (for In Progress view) -->
                <template x-if="selected.req_status === 'In Progress' && assignedPersonnel.length > 0">
                  <div class="mt-4">
                    <label class="text-sm mb-1 block font-medium">Currently Assigned Personnel</label>
                    <template x-for="person in assignedPersonnel" :key="person.staff_id">
                      <div class="w-full input-field bg-gray-100 text-gray-700 cursor-default">
                        <span x-text="person.full_name"></span>
                      </div>
                    </template>
                  </div>
                </template>
              </div>
            </template>

            <!-- Completed: Hide everything -->
            <template x-if="selected.req_status === 'Completed'">
              <div class="hidden"></div>
            </template>
          </div>

          <div>
              <label class="text-xs text-text mb-1">Status</label>

              <!-- Completed: show plain label -->
              <template x-if="selected.req_status === 'Completed'">
                <span class="block w-full input-field bg-green-100 text-green-800 cursor-default" x-text="selected.req_status"></span>
              </template>

              <!-- In Progress: dropdown without "To Inspect" -->
              <template x-if="selected.req_status === 'In Progress'">
                <select name="req_status" id="status" x-model="selected.req_status" class="w-full input-field">
                  <option value="In Progress">In Progress</option>
                  <option value="Completed">Completed</option>
                </select>
              </template>

              <!-- Other (e.g. To Inspect) : full dropdown -->
              <template x-if="selected.req_status !== 'Completed' && selected.req_status !== 'In Progress'">
                <select name="req_status" id="status" x-model="selected.req_status" class="w-full input-field">
                  <option value="To Inspect">To Inspect</option>
                  <option value="In Progress">In Progress</option>
                  <option value="Completed">Completed</option>
                </select>
              </template>
            </div>

            <!-- ✅ MATERIALS SECTION -->
            <div 
              x-show="selected.req_status === 'In Progress'" 
              x-data="{ 
                materials: [{ material_code: '', qty: 1 }],
                selectedMaterialCodes: [],

                updateMaterialOptions() {
                  this.selectedMaterialCodes = this.materials
                    .map(m => m.material_code)
                    .filter(code => code !== '');
                },

                async loadAssignedMaterials(requestId) {
                  try {
                    const res = await fetch(`../../../controllers/RequestController.php?getAssignedMaterials=${requestId}`);
                    const data = await res.json();

                    if (Array.isArray(data) && data.length > 0) {
                      this.materials = data.map(m => ({
                        material_code: m.material_code,
                        material_desc: m.material_desc,
                        qty: m.quantity_needed
                      }));
                    } else {
                      this.materials = [{ material_desc: 'No materials used', qty: '' }];
                    }

                    this.updateMaterialOptions();
                  } catch (err) {
                    console.error('❌ Failed to load materials:', err);
                  }
                }
              }"
              x-init="loadAssignedMaterials(selected.request_id)"
              class="mt-3 border-t border-gray-200 pt-3"
            >
              <h3 class="text-xs text-text mb-1">Materials Used / Needed</h3>

              <!-- Editable materials (In Progress only) -->
              <div id="material-fields" class="space-y-3 mb-6">
                <template x-for="(item, index) in materials" :key="index">
                  <div class="flex gap-2 material-row items-end">
                    
                    <!-- Material Dropdown -->
                    <div class="w-1/2">
                      <label class="text-xs text-text mb-1 block">Material</label>
                      <select 
                        :name="'materials[' + index + '][material_code]'"
                        class="input-field w-full material-select"
                        x-model="item.material_code"
                        @change="updateMaterialOptions()">
                        <option value="">Select Material</option>
                        <?php foreach ($materials as $mat): ?>
                          <option 
                            value="<?= $mat['material_code'] ?>" 
                            x-show="!selectedMaterialCodes.includes('<?= $mat['material_code'] ?>') || item.material_code === '<?= $mat['material_code'] ?>'">
                            <?= htmlspecialchars($mat['material_desc']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <!-- Quantity -->
                    <div class="w-1/4">
                      <label class="text-xs text-text mb-1 block">Qty</label>
                      <input 
                        type="number" 
                        :name="'materials[' + index + '][qty]'"
                        x-model="item.qty"
                        min="1"
                        class="input-field w-full" />
                    </div>

                    <!-- Add / Remove Buttons -->
                    <div class="flex items-center gap-1">
                      <button 
                        type="button"
                        class="bg-primary hover:bg-secondary text-white rounded-full w-9 h-9 flex justify-center shadow-md items-center"
                        @click="materials.push({ material_code: '', qty: 1 }); updateMaterialOptions();"
                        title="Add Material">
                        <img src="<?php echo PUBLIC_URL; ?>/assets/img/add_white.png" alt="Add" class="w-3 h-3">
                      </button>

                      <button 
                        type="button"
                        class="bg-gray-700 hover:bg-gray-600 text-white rounded-full w-9 h-9 flex justify-center items-center"
                        @click="materials.splice(index, 1); updateMaterialOptions();"
                        title="Remove Material">
                        <img src="<?php echo PUBLIC_URL; ?>/assets/img/minus.png" alt="Remove" class="w-3 h-3">
                      </button>
                    </div>
                  </div>
                </template>
              </div>
            </div>
            <div class="flex justify-center pt-2 space-x-2">
                <button type="button" class="btn btn-primary" @click="viewDetails(selected)"> Full Details </button>

                <!-- Save button hidden if status is Completed -->
                <button type="submit" class="btn btn-primary" id="saveBtn" name="saveAssignment"
                        x-show="selected.req_status !== 'Completed'">
                    Save Changes
                </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </main>
  <script>
<?php if (isset($_SESSION['alert'])): ?>
  Swal.fire({
    icon: '<?= $_SESSION['alert']['type'] ?>',
    title: '<?= $_SESSION['alert']['title'] ?>',
    text: '<?= $_SESSION['alert']['message'] ?>',
    confirmButtonColor: '#3085d6',
  }).then(() => {
    <?php if (!empty($_SESSION['alert']['redirect'])): ?>
      window.location.href = '<?= $_SESSION['alert']['redirect'] ?>';
    <?php endif; ?>
  });
  <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
</script>

</body>
<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/export.js"></script>
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

</html>