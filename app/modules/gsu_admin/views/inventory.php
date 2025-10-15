<?php
session_start();
// if (!isset($_SESSION['email'])) {
//     header("Location: admin_login.php");
//     exit;
// }
// require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
require_once __DIR__ . '/../../../controllers/AdminController.php';

require_once __DIR__ . '/../../../controllers/MaterialController.php';
$materialController = new MaterialController();
$materials = $materialController->index();

$controller = new AdminController();
$admins = $controller->getAllAdmins();

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? 'all';
$order = $_GET['order'] ?? 'az';

$materials = $materialController->getFiltered($search, $status, $order);
$nextCode = $materialController->getNextMaterialCode();

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
  <title>U-Request | Inventory</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/admin-user.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/alert.js"></script>
  <script src="<?php echo PUBLIC_URL; ?>/assets/js/helpers.js"></script>
</head>

<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/gsu_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Inventory</h1>
      <div x-data="{ showDetails: false, selected: {}, addmaterial: false }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="search" placeholder="Search by material name" class="flex-1 min-w-[200px] input-field">
            <!-- Filter -->
            <select id="statusFilter" class="input-field">
              <option value="all">All</option>
              <option value="available">Available</option>
              <option value="unavailable">Unavailable</option>
            </select>

            <!-- Sort -->
            <select id="sortMaterials" class="input-field">
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
              <button @click="showModal = true" title="Add new material" class="btn btn-secondary">
                <img src="/public/assets/img/add-material.png" alt="User" class="size-5 ">
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
                          <h2 class="text-base font-medium mb-3">Add Materials</h2>
                          <div>
                            <label class="text-xs text-text mb-1">Material Code No.<span class="text-secondary"></span></label>
                            <input type="text" name="material_code" value="<?= htmlspecialchars($nextCode) ?>" class="w-full input-field" required readonly />
                          </div>

                          <div>
                            <label class="text-xs text-text mb-1">Description<span class="text-secondary"></span></label>
                            <input type="text" name="material_desc" class="w-full input-field" required />
                          </div>

                          <div>
                            <label class="text-xs text-text mb-1">Quantity<span class="text-secondary"></span></label>
                            <input type="number" name="qty" class="w-full input-field" required />
                          </div>

                          <!-- <div>
                            <label class="text-xs text-text mb-1">Status<span class="text-secondary"></span></label>
                            <select name="material_status" class="w-full input-field">
                              <option value="Available">Available</option>
                              <option value="Unavailable">Unavailable</option>
                            </select>

                          </div> -->
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
          <div class="overflow-x-auto max-h-[550px] overflow-y-auto mt-4 rounded-lg shadow bg-white">
            <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg p-2">
              <thead class="bg-gray-50">
                <tr>
                  <th class="pl-8 py-2 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Status</th>
                </tr>
              </thead>
              <tbody id="table" class="text-sm">
                <?php if (!empty($materials)) { ?>
                  <?php foreach ($materials as $row) {
                    $status = ($row['qty'] == 0) ? 'Unavailable' : 'Available';
                    $rowJson = htmlspecialchars(json_encode([
                      'material_code'   => $row['material_code'],
                      'material_desc'   => $row['material_desc'],
                      'qty'             => $row['qty'],
                      'material_status' => $status,
                    ]), ENT_QUOTES, 'UTF-8');
                  ?>
                    <tr
                      @click="selected = <?= $rowJson ?>; showDetails = true"
                      class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100">
                      <td class="pl-8 py-3"><?= htmlspecialchars($row['material_code']) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['material_desc']) ?></td>
                      <td class="px-4 py-3"><?= htmlspecialchars($row['qty']) ?></td>
                      <td><?php echo $status; ?></td>
                    </tr>
                  <?php } ?>
                <?php } else { ?>
                  <tr>
                    <td colspan="4" class="text-center py-3">No materials found</td>
                  </tr>
                <?php } ?>
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

          <!-- Form -->
          <form id="materialForm" method="post" action="../../../controllers/MaterialController.php" class="space-y-5">
            <h2 class="text-lg font-bold">Material Information</h2>

            <!-- Hidden field for ID -->
            <input type="hidden" name="id" :value="selected.id || ''">

            <div>
              <label class="text-xs text-text mb-1">Material Code No.</label>
              <input type="text" name="material_code" :value="selected.material_code || ''" class="w-full input-field" required readonly />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Description</label>
              <input type="text" name="material_desc" :value="selected.material_desc || ''" class="w-full input-field" required />
            </div>

            <div>
              <label class="text-xs text-text mb-1">Current Quantity</label>
              <div class="w-full flex gap-2">
                <input type="number" name="qty" :value="selected.qty || ''" class="w-full input-field" required readonly />
                <button type="button" @click="addmaterial = true" title="Add Stock" class="btn btn-secondary py-0.5 px-4">
                  <img src="/public/assets/img/add.png" class="size-3" alt="Add Stock">
                </button>
              </div>
            </div>

            <!-- <div>
              <label class="text-xs text-text mb-1">Status</label>
                  <div>
                    <select name="material_status" class="input-field">
                      <option value="Available" :selected="selected.material_status === 'available'">Available</option>
                      <option value="Unavailable" :selected="selected.material_status === 'unavailable'">Unavailable</option>
                    </select>
                  </div>
            </div> -->

            <div class="flex justify-center gap-2 pt-2">
              <!-- <button type="button" title="Material Used History" class="btn btn-secondary"> -->
              <!-- <button type="button" title="Material Used History" class="btn btn-secondary">
                <img src="/public/assets/img/work-history.png" class="size-4" alt="Material Used History">
              </button>
              </button> -->
              <button type="submit" name="update_material" class="btn btn-primary">Save Changes</button>
            </div>
          </form>

        </div>


        <!-- Stock Management Modal -->
        <div x-show="addmaterial" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center  z-50 overflow-auto">
          <div class="bg-white rounded-lg shadow-lg w-1/5 p-6">
            <!-- <h2 class="text-lg font-semibold mb-4">Stock Management</h2> -->
            <form id="addStockForm" method="post" action="../../../controllers/MaterialController.php" class="space-y-4">
              <input type="hidden" name="add_stock" value="1"> <!-- ensures correct handling -->
              <input type="hidden" name="material_code" :value="selected.material_code">

              <div class="mb-4">
                <label class="block text-xs mb-1">Quantity to Add</label>
                <input type="number" name="quantity" class="w-full input-field" required>
              </div>

              <div class="flex justify-center gap-2">
                <button type="button" @click="addmaterial = false" class="btn btn-secondary">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="validateAndSubmit()">Add</button>
              </div>
            </form>
          </div>
        </div>


      </div>
    </div>
    </div>
  </main>

  <script type="module">
    import {
      initTableFilters
    } from "/public/assets/js/shared/table-filters.js";

    initTableFilters({
      tableId: "table", // tbody id
      searchId: "search", // search box id
      filterId: "statusFilter", // dropdown id for status
      sortId: "sortMaterials", // dropdown id for sorting
      searchColumns: [1], // search in Description column (index starts at 0)
      filterColumn: 3 // Status column index (Code=0, Desc=1, Qty=2, Status=3)
    });

    function previewProfile(event) {
      const output = document.getElementById('profile-preview');
      output.src = URL.createObjectURL(event.target.files[0]);
    }
    window.adminSuccess = <?= isset($_SESSION['admin_success']) ? json_encode($_SESSION['admin_success']) : 'null' ?>;
    window.adminError = <?= isset($_SESSION['admin_error']) ? json_encode($_SESSION['admin_error']) : 'null' ?>;

    document.addEventListener("DOMContentLoaded", () => {
      <?php if (isset($_SESSION['material_success'])): ?>
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: <?= json_encode($_SESSION['material_success']) ?>
        });
      <?php unset($_SESSION['material_success']);
      endif; ?>

      <?php if (isset($_SESSION['material_error'])): ?>
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: <?= json_encode($_SESSION['material_error']) ?>
        });
      <?php unset($_SESSION['material_error']);
      endif; ?>
    });
  </script>
</body>
<script src="/public/assets/js/shared/menus.js"></script>
<script src="/public/assets/js/shared/search.js"></script>

//new alert
<script>
  function validateAndSubmit() {
    const form = document.getElementById('addStockForm');
    const quantityInput = form.querySelector('input[name="quantity"]');
    const quantity = parseInt(quantityInput.value);

    if (isNaN(quantity) || quantity <= 0) {
      Swal.fire({
        icon: 'error',
        title: 'Invalid Quantity',
        text: 'The entered quantity must be above 0.',
      });
    } else {
      form.submit(); // Just submit — no SweetAlert here
    }
  }
</script>


</html>

<?php
// Clear session variables after outputting
unset($_SESSION['admin_success']);
unset($_SESSION['admin_error']);
?>