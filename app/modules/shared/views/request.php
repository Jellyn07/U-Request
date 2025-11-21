<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: /app/modules/shared/views/admin_login.php");
    exit;
}
require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/RequestController.php';

$materialController = new RequestController();
$materials = $materialController->getAllMaterials();

$controller = new RequestController();
$data = $controller->index();
$requests = $data['requests'];

$model = new RequestModel();
$personnels = $model->getAvailableStaff();

// Redirect if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: modules/shared/views/admin_login.php");
    exit;
}
// Fetch profile
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
  
  <!-- AlpineJS + SweetAlert + jsPDF -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

  <!-- Custom Scripts -->
  <script src="<?= PUBLIC_URL ?>/assets/js/admin-user.js"></script>
  <script src="<?= PUBLIC_URL ?>/assets/js/alert.js"></script>
  <script src="<?= PUBLIC_URL ?>/assets/js/shared/popup.js"></script>
  <script src="<?= PUBLIC_URL ?>/assets/js/gsu_admin/request.js"></script>
</head>
<body class="bg-gray-100">
  <!-- Menu -->
  <?php
    if ($_SESSION['access_level'] == 1) {
        include COMPONENTS_PATH . '/superadmin_menu.php';
    } elseif ($_SESSION['access_level'] == 2) {
        include COMPONENTS_PATH . '/gsu_menu.php';
    } else {
        echo "<p>No menu available for your access level.</p>";
    }
  ?>

  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Request</h1>

      <!-- Tabs -->
      <div id="tabs" class="flex gap-2 mt-4 text-xs text-text">
        <?php foreach (['All', 'To Inspect', 'In Progress', 'Completed'] as $tab): ?>
          <button class="ml-5 btn"><p><?= $tab ?></p></button>
        <?php endforeach; ?>
      </div>

      <div 
        x-data="{ 
          showDetails: false, 
          selected: {}, 
          addmaterial: false,
          materials: [{ material_code: '', qty: 1 }],
          selectedMaterialCodes: [],
          isLocked() {
            // Lock only if superadmin OR original status was completed
            return <?= $_SESSION['access_level'] == 1 ? 'true' : 'false' ?> || this.selected.original_status === 'Completed';
          },
          canSave() {
            // Show save button if status changed OR not locked
            return !this.isLocked() || this.selected.req_status !== this.selected.original_status;
          },
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
          <!-- Search & Filters -->
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <input type="text" id="searchRequests" placeholder="Search by ID or Requester Name" class="flex-1 min-w-[200px] input-field">
            
            <select class="input-field" id="filterCategory">
              <option value="all">All</option>
              <?php foreach (['Carpentry/Masonry','Welding','Hauling','Plumbing','Landscaping','Electrical','Air-Condition','Others'] as $cat): ?>
                <option><?= $cat ?></option>
              <?php endforeach; ?>
            </select>

            <select id="sortCategory" class="input-field">
              <option value="all">All Dates</option>
              <option value="today">Today</option>
              <option value="yesterday">Yesterday</option>
              <option value="7">Last 7 days</option>
              <option value="14">Last 14 days</option>
              <option value="30">Last 30 days</option>
            </select>

            <!-- DON'T REMOVE THE HIDDEN IMAGE -->
            <img id="logo" src="/public/assets/img/usep.png" class="hidden">
            <button title="Export" id="export" class="btn-upper">
              <img src="/public/assets/img/export.png" alt="Export" class="size-4 my-0.5">
            </button>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto h-[545px] overflow-y-auto rounded-b-lg shadow bg-white">
            <table id="table" class="min-w-full divide-y divide-gray-200 bg-white rounded-b-lg p-2">
              <thead class="bg-white sticky top-0">
                <tr>
                  <?php foreach (['Request ID','Requester','Category','Location','Date Request','Status'] as $header): ?>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase"><?= $header ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody id="requestsTable" class="text-sm">
                <?php foreach ($requests as $row): ?>
                  <tr 
                    data-category="<?= htmlspecialchars($row['request_Type']) ?>" 
                    data-status="<?= htmlspecialchars($row['req_status']) ?>"
                    @click="selected = <?= htmlspecialchars(json_encode($row)) ?>; selected.original_status = '<?= htmlspecialchars($row['req_status']) ?>'; showDetails = true"
                    class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100"
                  >
                    <td class="pl-8 py-3"><?= htmlspecialchars($row['request_id']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['Name']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['request_Type']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($row['location']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars(date("F d, Y", strtotime($row['request_date']))) ?></td>
                    <td class="px-4 py-3">
                        <?php if ($_SESSION['access_level'] == 1): ?>
                            <!-- READ-ONLY STATUS FOR SUPERADMIN -->
                            <?php if ($row['req_status'] === 'Completed'): ?>
                                <span class="px-5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Completed</span>
                            <?php elseif ($row['req_status'] === 'To Inspect'): ?>
                                <span class="px-5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">To Inspect</span>
                            <?php else: ?>
                                <span class="px-5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">In Progress</span>
                            <?php endif; ?>

                        <?php else: ?>
                            <!-- DROPDOWN FOR ACCESS LEVEL 2 or others -->
                            <?php if ($row['req_status'] === 'Completed'): ?>
                                <span class="px-5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Completed</span>

                            <?php elseif ($row['req_status'] === 'To Inspect'): ?>
                                <select class="status-dropdown px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800" 
                                    data-request-id="<?= $row['request_id'] ?>" data-current-status="<?= $row['req_status'] ?>">
                                    <option hidden disabled value="To Inspect" selected>To Inspect</option>
                                    <option value="In Progress" class="bg-blue-100 text-blue-800">In Progress</option>
                                    <option value="Completed" class="bg-green-100 text-green-800">Completed</option>
                                </select>

                            <?php else: ?>
                                <select class="status-dropdown px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800" 
                                    data-request-id="<?= $row['request_id'] ?>" data-current-status="<?= $row['req_status'] ?>">
                                    <option hidden disabled value="In Progress" selected>In Progress</option>
                                    <option value="Completed" class="bg-green-100 text-green-800">Completed</option>
                                </select>
                            <?php endif; ?>

                        <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Right Section (Details) -->
        <div x-show="showDetails" x-cloak class="bg-white shadow rounded-lg p-4 max-h-[602px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>

          <!-- Assignment Form -->
          <form id="assignmentForm" class="space-y-1" method="post" action="../../../controllers/RequestController.php">
            <input type="hidden" name="request_id" x-model="selected.request_id">
            <input type="hidden" name="action" value="saveAssignment">

            <h2 class="text-lg font-bold mb-2">Repair Information</h2>
            <img :src="selected.image_path ? '/public/uploads/' + selected.image_path : '/public/assets/img/default-img.png'" 
                 alt="Preview" 
                 class="w-10/12 shadow-lg mx-auto rounded-lg" 
                 @error="$el.src='/public/assets/img/default-img.png'"/>

            <div>
              <label class="text-xs text-text mb-1">Tracking No.</label>
              <input type="text" class="w-full view-field" x-model="selected.tracking_id" readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Request Date</label>
              <input type="text" class="w-full view-field"  
                     :value="new Date(selected.request_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: '2-digit' })" 
                     readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Requester</label>
              <input type="text" class="w-full view-field" x-model="selected.Name" readonly />
            </div>

<!-- Location Field with Edit/Save -->
<div x-data="{ editingLocation: false }" class="flex items-center gap-2 mt-2">
  
  <!-- Input Field -->
  <div class="w-full">
    <label class="text-xs text-text mb-1">Location</label>
    <div class="flex gap-2">
    <input type="text" class="w-full view-field"   x-model="selected.location" :readonly="!editingLocation" />
<!-- Edit / Save Buttons -->
<!-- Edit Button -->
    <button 
      type="button" 
      class="btn btn-secondary text-xs mt-1"
      x-show="!editingLocation"
      @click="editingLocation = true">
      Edit
    </button>

    <!-- Save Button -->
    <button 
      type="button" 
      class="btn btn-primary text-xs mt-1"
      x-show="editingLocation"
      @click="
        if(selected.location.trim() === '') {
          Swal.fire('Error', 'Location cannot be empty.', 'error');
          return;
        }
        $dispatch('update-location', selected.location); 
        editingLocation = false;
      ">
      Save
    </button>
    </div>

  </div>
  
  <!-- Edit / Save Buttons -->
  <div class="flex gap-1">
    
  </div>

  <!-- AlpineJS Listener (hidden, just for event handling) -->
<div x-data 
     @update-location.window="async (e) => {
       const newLocation = e.detail;
       try {
         console.log('Updating location for', selected.request_id, '->', newLocation);
         const formData = new FormData();
         formData.append('request_id', selected.request_id);
         formData.append('location', newLocation);
         formData.append('action', 'updateLocation');

         const res = await fetch('../../../controllers/RequestController.php', {
           method: 'POST',
           body: formData
         });

         // optional: log raw text for debugging if JSON parse fails
         const text = await res.text();
         try {
           const data = JSON.parse(text);
           if (data.success) {
             Swal.fire('Updated!', 'Location updated successfully.', 'success');
             selected.location = newLocation;
           } else {
             Swal.fire('Error', data.message || 'Failed to update location.', 'error');
           }
         } catch (parseErr) {
           console.error('Invalid JSON response:', text, parseErr);
           Swal.fire('Error', 'Invalid server response.', 'error');
         }
       } catch (err) {
         console.error(err);
         Swal.fire('Error', 'An unexpected error occurred.', 'error');
       }
     }">
</div>

</div>



            <div>
                <label class="text-xs text-text mb-1">Priority Level</label>
                <select name="prio_level" id="prioritySelect" 
                        class="w-full input-field" 
                        x-model="selected.priority_status"
                        :disabled="isLocked()">

                    <option value="" x-show="!selected.priority_status">No Priority Level</option>
                    <option value="Low" x-show="!selected.priority_status">Low</option>
                    <option value="High">High</option>
                </select>
            </div>

         <!-- PERSONNEL & STATUS SECTION -->
            <div x-data="{
                personnel: [{ staff_id: '' }],
                assignedPersonnel: [],
                selectedStaffIds: [],
                status: selected.req_status, // local status for binding
                // Check if there is at least one assigned personnel
                get hasAssignedPersonnel() {
                    return this.personnel.filter(p => p.staff_id !== '').length > 0 || this.assignedPersonnel.length > 0;
                },
                updatePersonnelOptions() {
                    this.selectedStaffIds = this.personnel.map(p => p.staff_id).filter(id => id !== '');
                    // Auto-set status to 'To Inspect' if personnel assigned and status is not already finalized
                    if (this.hasAssignedPersonnel && !['In Progress','Completed','To Inspect'].includes(this.status)) {
                        this.status = 'To Inspect';
                        selected.req_status = this.status; // sync with main selected object
                    }
                },
                async loadAssignedPersonnel(requestId) {
                    try {
                        const res = await fetch(`../../../controllers/RequestController.php?getAssignment=${requestId}`);
                        const data = await res.json();
                        if (Array.isArray(data) && data.length > 0) {
                            this.assignedPersonnel = data;
                            this.personnel = data.map(p => ({ staff_id: p.staff_id }));
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
                    <div id="personnel-fields" class="">
                        <label class="text-xs text-text mb-1" :hidden="isLocked()">Assign / Edit Personnel</label>

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
                                        :hidden="isLocked()"
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
                                        x-show="!isLocked()"  
                                        type="button"
                                        class="bg-primary hover:bg-secondary text-white rounded-lg w-10 h-10 flex justify-center shadow-md items-center"
                                        @click="personnel.push({ staff_id: '' }); updatePersonnelOptions();"
                                        title="Add Personnel">
                                        <img src="<?php echo PUBLIC_URL; ?>/assets/img/add_white.png" alt="Add" class="w-3 h-3">
                                    </button>
                                    <button 
                                        x-show="!isLocked() && personnel.length > 1"  
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

                <!-- Status Dropdown -->
                <div>
                    <label class="text-xs text-text mb-1">Status</label>

                    <!-- Completed: show plain label -->
                    <template x-if="selected.req_status === 'Completed'">
                        <span class="block w-full input-field bg-green-100 text-green-800 cursor-default" x-text="selected.req_status"></span>
                    </template>

                    <!-- Editable dropdown if not Completed -->
                    <template x-if="selected.req_status !== 'Completed'">
                        <select name="req_status" x-model="selected.req_status" class="w-full input-field" 
                                :disabled="!hasAssignedPersonnel || isLocked()">
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </template>
                </div>
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
                        @change="updateMaterialOptions()"
                        :hidden="isLocked()">
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
                        class="input-field w-full"
                        :hidden="isLocked()" />
                    </div>

                    <!-- Add / Remove Buttons -->
                    <div class="flex items-center gap-1">
                      <button 
                        type="button"
                        class="bg-primary hover:bg-secondary text-white rounded-full w-9 h-9 flex justify-center shadow-md items-center"
                        @click="materials.push({ material_code: '', qty: 1 }); updateMaterialOptions();"
                        title="Add Material"
                        :hidden="isLocked()">
                        <img src="<?php echo PUBLIC_URL; ?>/assets/img/add_white.png" alt="Add" class="w-3 h-3">
                      </button>

                      <button 
                        type="button"
                        class="bg-gray-700 hover:bg-gray-600 text-white rounded-full w-9 h-9 flex justify-center items-center"
                        @click="materials.splice(index, 1); updateMaterialOptions();"
                        title="Remove Material"
                        :hidden="isLocked()">
                        <img src="<?php echo PUBLIC_URL; ?>/assets/img/minus.png" alt="Remove" class="w-3 h-3">
                      </button>
                    </div>
                  </div>
                </template>
              </div>
            </div>
            <div class="flex justify-center pt-2 space-x-2">
              <button type="button" class="btn btn-primary" @click="viewDetails(selected)">Full Details</button>
              <button type="submit" class="btn btn-primary" id="saveBtn" name="saveAssignment"  x-show="canSave()" :disabled="isLocked()">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

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

  <?php if (isset($_SESSION['alert'])): ?>
  <script>
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
  </script>
  <?php unset($_SESSION['alert']); endif; ?>
</body>
</html>