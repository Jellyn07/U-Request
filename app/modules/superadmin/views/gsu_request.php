<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: admin_login.php");
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
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
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
                        <?php 
                            $status = $row['req_status'];
                            $statusClass = '';
                            $statusText = htmlspecialchars($status);
                            switch ($status) {
                                case 'Completed':
                                    $statusClass = 'bg-green-100 text-green-800';
                                    break;
                                case 'In progress':
                                    $statusClass = 'bg-blue-200 text-blue-800';
                                    break;
                                case 'To Inspect':
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    break;
                                default:
                                    $statusClass = 'bg-gray-100 text-gray-800';
                            }
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
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
                <select name="prio_level" id="prioritySelect" 
                        class="w-full input-field" 
                        x-model="selected.priority_status"
                        disabled>

                    <!-- Fallback if no priority -->
                    <!-- <option value="" x-show="!selected.priority_status">No Priority Level</option> -->

                    <option value="Low" x-show="!selected.priority_status">Low</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div>
                <label class="text-xs text-text mb-1">Status</label>

                <!-- Completed: show plain label -->
                <template x-if="selected.req_status === 'Completed'">
                  <span class="block w-full input-field bg-green-100 text-green-800 cursor-default" x-text="selected.req_status"></span>
                </template>

                <!-- In Progress: dropdown without "To Inspect" -->
                <template x-if="selected.req_status === 'In Progress'">
                  <select name="req_status" id="status" x-model="selected.req_status" class="w-full input-field" disabled>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                  </select>
                </template>

                <!-- Other (e.g. To Inspect) : full dropdown -->
                <template x-if="selected.req_status !== 'Completed' && selected.req_status !== 'In Progress'">
                  <select name="req_status" id="status" x-model="selected.req_status" class="w-full input-field  bg-yellow-100 text-yellow-800" disabled>
                    <option value="To Inspect">To Inspect</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                  </select>
                </template>
              </div>
              <div class="flex justify-center pt-2 space-x-2">
              <button type="button" class="btn btn-primary" @click="viewDetails(selected)"> Full Details </button>

                  <!-- Save button hidden if status is Completed -->
                  <!-- <button type="submit" class="btn btn-primary" id="saveBtn" name="saveAssignment"
                          x-show="selected.req_status !== 'Completed'">
                      Save Changes
                  </button> -->
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