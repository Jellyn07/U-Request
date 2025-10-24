<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/VehicleController.php';

$vehicleController = new VehicleController();
$vehicleController->addVehicle();
$drivers = $vehicleController->getDrivers();
$vehicles = $vehicleController->getVehicles();
// $drivers = $drivers ?? []; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Vehicles</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="/public/assets/js/motorpool_admin/vehicle.js"></script>
</head>
<body class="bg-gray-100">

  <?php include COMPONENTS_PATH . '/motorpool_menu.php'; ?>

  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Vehicles</h1>

      <div x-data="{ showDetails: false, selected: {} }" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-lg">
             <input 
                type="text" 
                id="searchUser" 
                placeholder="Search by vehicle name" 
                class="flex-1 min-w-[200px] input-field"
              >
              <select class="input-field" id="sortVehicle">
                <option value="">All Types</option>
                <option value="Sedan">Sedan</option>
                <option value="SUV">SUV</option>
                <option value="Van">Van</option>
                <option value="Truck">Truck</option>
                <option value="Bus">Bus</option>
              </select>
            </select>
            <button title="Print data" class="input-field">
              <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
            </button>
            <button class="input-field" title="Export to Excel">
              <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>

            <!-- Add Vehicle / Add Driver Modal -->
            <div x-data="{ showModal: false }">
              <button @click="showModal = true" title="Add new driver" class="btn btn-secondary">
                <img src="/public/assets/img/add.png" alt="User" class="size-3 my-1">
              </button>

              <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-auto">
                <div class="bg-white rounded-xl shadow-xl w-[90%] md:w-1/3 mx-auto relative overflow-auto">
                  <!-- Modal Content -->
                  <main class="flex flex-col transition-all duration-300 p-4 space-y-1 px-5">
                    <h2 class="text-lg font-bold">New Vehicle</h2>
                    <form method="post" action="../../../controllers/VehicleController.php" enctype="multipart/form-data" class="space-y-2">
                      <!-- Vehicle Name -->
                      <div>
                        <label class="text-xs text-text mb-1">Vehicle Name<span class="text-red-500">*</span></label>
                        <input type="text" name="vehicle_name" class="w-full input-field" required>
                      </div>

                      <!-- Plate Number and Capacity -->
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                          <label class="text-xs text-text mb-1">Plate Number<span class="text-red-500">*</span></label>
                          <input type="text" name="plate_no" class="w-full input-field" required>
                        </div>
                        <div>
                          <label class="text-xs text-text mb-1">Capacity<span class="text-red-500">*</span></label>
                          <input type="number" name="capacity" class="w-full input-field" required>
                        </div>
                      </div>

                      <!-- Vehicle Type -->
                      <div>
                        <label class="text-xs text-text mb-1">Vehicle Type<span class="text-red-500">*</span></label>
                        <select name="vehicle_type" class="w-full input-field" required>
                          <option value="">Select Type</option>
                          <option value="Sedan">Sedan</option>
                          <option value="SUV">SUV</option>
                          <option value="Van">Van</option>
                          <option value="Truck">Truck</option>
                          <option value="Bus">Bus</option>
                        </select>
                      </div>

                      <!-- Vehicle Type -->
                      <div>
                        <label class="text-xs text-text mb-1">Assign Driver<span class="text-red-500">*</span></label>
                        <select name="driver_id" class="w-full input-field" required>
                          <option value="">Select Driver</option>
                          <?php foreach($drivers as $driver): ?>
                              <option value="<?= $driver['driver_id'] ?>"><?= htmlspecialchars($driver['driver_name']) ?></option>
                          <?php endforeach; ?>
                      </select>
                      </div>

                      <div class="w-full">
                        <label class="text-xs text-text mb-1">Vehicle Photo: <span class="text-red-500">*</span></label>

                        <!-- Upload Area -->
                        <div 
                          id="upload-area" 
                          class="relative border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 transition duration-300 cursor-pointer p-6 flex flex-col items-center justify-center text-center"
                          onclick="document.getElementById('img').click()"
                          ondragover="handleDragOver(event)"
                          ondragleave="handleDragLeave(event)"
                          ondrop="handleDrop(event)"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16l5-5 4 4L21 7M16 7h5v5" />
                          </svg>
                          <p class="text-sm text-gray-600">Click or drag to upload a photo</p>
                          <p class="text-xs text-gray-400 mt-1">Accepted formats: JPG, PNG, JPEG</p>

                          <input 
                            type="file" 
                            id="img" 
                            name="picture" 
                            accept="image/*" 
                            required 
                            class="hidden"
                            onchange="previewImage(event)"
                          >
                        </div>

                        <!-- Image Preview -->
                        <div id="preview-container" class="mt-3 hidden">
                          <!-- <p class="text-sm font-medium text-gray-700 mb-1">Preview (click to change):</p> -->
                          <div 
                            id="preview-wrapper" 
                            class="relative border border-gray-200 rounded-lg overflow-hidden shadow-sm cursor-pointer group"
                            onclick="document.getElementById('img').click()"
                          >
                            <img id="preview" src="#" alt="Preview" class="max-h-52 w-auto object-contain rounded-lg mx-auto transition duration-300 group-hover:opacity-80">
                            <div class="absolute inset-0 bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-sm font-medium transition">
                              Click to change photo
                            </div>
                            <button 
                              type="button" 
                              onclick="removePreview(event)" 
                              class="absolute top-2 right-2 bg-white bg-opacity-70 hover:bg-opacity-100 text-gray-700 rounded-full p-1 shadow-sm transition"
                              title="Remove image"
                            >
                              <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
                            </button>
                          </div>
                        </div>
                      </div>

                      <!-- Modal Buttons -->
                      <div class="flex justify-center gap-2 pt-4">
                        <button type="button" @click="showModal = false" class="btn btn-secondary">Cancel</button>
                        <button type="submit" name="add_vehicle" class="btn btn-primary px-7">Save</button>
                      </div>

                    </form>
                  </main>
                </div>
              </div>
            </div>
          </div>

          <!-- Vehicle Cards Grid -->
         <div id="vehicleContainer" class="grid gap-4 p-4 h-[578px] overflow-y-auto"  :class="showDetails ? 'grid-cols-2' : 'sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4'">
          <?php if (!empty($vehicles)): ?>
              <?php foreach ($vehicles as $vehicle): 
                  $vehicleData = [
                      'name' => $vehicle['vehicle_name'],
                      'driver' => $vehicle['driver_name'] ?? 'Unassigned',
                      'status' => 'Available',
                      'photo' => !empty($vehicle['photo']) ? '/../uploads/vehicles/' . $vehicle['photo'] : '/public/assets/img/car.jpg'
                  ];
              ?>
              <div class="vehicle-card bg-white rounded-lg shadow hover:shadow-lg transition border border-gray-300 cursor-pointer"
                  @click='selected = <?= json_encode($vehicleData) ?>; showDetails = true'
                  data-name="<?= strtolower($vehicle['vehicle_name']) ?>"
                  data-type="<?= strtolower($vehicle['vehicle_type']) ?>"
              >
                  <div class="relative">
                      <span class="absolute top-2 right-2 px-3 py-1 text-[10px] font-semibold rounded-full bg-green-200 text-green-700 z-10">
                          Available
                      </span>
                      <img src="<?= !empty($vehicle['photo']) ? '/../uploads/vehicles/' . htmlspecialchars($vehicle['photo']) : '/public/assets/img/car.jpg' ?>"
                          alt="Vehicle" 
                          class="w-full h-52 mx-auto rounded-lg object-cover">
                  </div>
                  <div class="p-3 space-y-2">
                      <h2 class="text-base font-semibold"><?= htmlspecialchars($vehicle['vehicle_name']) ?></h2>
                      <p class="text-xs">Last Maintenance Date: <span class="font-medium">—</span></p>
                      <h2 class="text-xs font-semibold text-primary">
                          Assigned Driver: <span class="ml-2"><?= htmlspecialchars($vehicle['driver_name'] ?? 'Unassigned') ?></span>
                      </h2>
                      <div class="flex text-[9px] text-gray-700 space-x-2">
                          <p class="bg-gray-300 px-2 py-1 rounded-xl">Plate: <span class="font-medium text-text"><?= htmlspecialchars($vehicle['plate_no']) ?></span></p>
                          <p class="bg-gray-300 px-2 py-1 rounded-xl">Type: <span class="font-medium text-text"><?= htmlspecialchars($vehicle['vehicle_type']) ?></span></p>
                          <p class="bg-gray-300 px-2 py-1 rounded-xl">Capacity: <span class="font-medium text-text"><?= htmlspecialchars($vehicle['capacity']) ?></span></p>
                      </div>
                  </div>
              </div>
              <?php endforeach; ?>
              <?php else: ?>
                  <p class="col-span-full text-center text-gray-500">No vehicles found.</p>
              <?php endif; ?>
          </div>
        <!-- Right Section -->
        <div x-show="showDetails" x-cloak class="bg-white shadow rounded-lg p-4 max-h-[640px] overflow-y-auto relative md:col-span-1">
          <button @click="showDetails = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
            <img src="/public/assets/img/exit.png" class="w-4 h-4" alt="Close">
          </button>

          <div class="text-center mt-4">
            <img :src="selected.photo" alt="Vehicle" class="w-1/2 h-32 mx-auto rounded-lg mb-3 object-cover">
            <h2 class="text-lg font-bold" x-text="selected.name"></h2>
            <p class="text-sm mt-1">Driver: <span x-text="selected.driver"></span></p>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-700 z-10">
              <span x-text="selected.status"></span>
            </span>
          </div>

          <div class="mt-5">
            <h3 class="font-semibold mb-2">Travel History</h3>
            <ul class="space-y-2">
              <li class="p-2 border border-black rounded-lg text-sm hover:bg-gray-100 transition">
                Oct 22, 2025 - Field Trip to City A - Driver: Juan Dela Cruz
              </li>
              <li class="p-2 border border-black rounded-lg text-sm hover:bg-gray-100 transition">
                Oct 20, 2025 - Maintenance Delivery - Driver: Juan Dela Cruz
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="/public/assets/js/shared/menus.js"></script>
  <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
  <?php if (isset($_SESSION['alert'])): ?>
  <script>
  Swal.fire({
      icon: '<?= $_SESSION['alert']['icon'] ?>',
      title: '<?= $_SESSION['alert']['title'] ?>',
      text: '<?= $_SESSION['alert']['text'] ?>',
      confirmButtonText: 'OK'
  }).then(() => {
      // Optional: reload or redirect
  });
  </script>
  <?php unset($_SESSION['alert']); ?>
  <?php endif; ?>
</body>
</html>
