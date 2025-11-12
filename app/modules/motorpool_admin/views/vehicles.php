<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: /app/modules/shared/views/admin_login.php");
    exit;
}
require_once __DIR__ . '/../../../config/auth-admin.php';
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body class="bg-gray-100">

  <?php include COMPONENTS_PATH . '/motorpool_menu.php'; ?>

  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Vehicles</h1>
      <div x-data="{showDetails: false,editing: false,selected: {}, openDetails(data) {this.selected = data;this.showDetails = true;this.editing = false;}}"
        class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Left Section -->
        <div :class="showDetails ? 'col-span-2' : 'col-span-3'">
          <div class="p-3 flex flex-wrap gap-2 justify-between items-center bg-white shadow rounded-t-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="searchUser" placeholder="Search by vehicle name" class="flex-1 min-w-[200px] input-field">
            <!-- <select class="input-field" id="statusFilter">
              <option value="all">All</option>
              <option value="Available">Available</option>
              <option value="Fixing">Fixing</option>
            </select> -->

            <select class="input-field" id="sortVehicle">
                <option value="">All Types</option>
                <option value="Sedan">Sedan</option>
                <option value="SUV">SUV</option>
                <option value="Van">Van</option>
                <option value="Truck">Truck</option>
                <option value="Bus">Bus</option>
              </select>

            <!-- <select class="input-field" id="sortUsers">
                <option value="az">Sort A-Z</option>
                <option value="za">Sort Z-A</option>
            </select> -->
            <!-- <button title="Print data in the table" class="input-field">
                <img src="/public/assets/img/printer.png" alt="User" class="size-4 my-0.5">
            </button> -->
            <img id="logo" src="/public/assets/img/usep.png" class="hidden">
            <button title="Export" id="export" class="btn-upper">
              <img src="/public/assets/img/export.png" alt="User" class="size-4 my-0.5">
            </button>
            <!-- Add Admin Modal -->
                <div x-data="{ showModal: false }">

            <!-- Modal Background -->
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
          </div>

          <!-- Table -->
          <div class="overflow-x-auto h-[578px] overflow-y-auto rounded-b-lg shadow bg-white">
          <div 
              id="vehicleContainer"
              class="grid gap-4 p-4 h-max-[500px] overflow-y-auto flex-1"
              :class="showDetails ? 'grid-cols-2' : 'sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4'"
            >
              <?php if (!empty($vehicles)): ?>
                <?php foreach ($vehicles as $vehicle): 
                    $vehicleData = [
                        'vehicle_id' => $vehicle['vehicle_id'],
                        'name' => $vehicle['vehicle_name'],
                        'driver' => $vehicle['driver_name'] ?? 'Unassigned',
                        'driver_id' => $vehicle['driver_id'] ?? 0,
                        'status' => 'Available',
                        'photo' => !empty($vehicle['photo']) ? '/../uploads/vehicles/' . $vehicle['photo'] : '/public/assets/img/car.jpg',
                        'plate' => $vehicle['plate_no'] ?? '',
                        'type' => $vehicle['vehicle_type'] ?? '',
                        'capacity' => $vehicle['capacity'] ?? '',
                        'status' => $vehicle['status'] ?? 'Available'
                    ];
                ?>
                <div 
                    class="vehicle-card bg-white rounded-lg shadow hover:shadow-lg transition border border-gray-300 cursor-pointer"
                    data-name="<?= htmlspecialchars(strtolower($vehicle['vehicle_name'])) ?>"
                    data-type="<?= htmlspecialchars(strtolower($vehicle['vehicle_type'])) ?>"
                    @click='openDetails(<?= json_encode($vehicleData) ?>)'
                >
                  <div class="relative">
                    <span class="absolute top-2 right-2 px-3 py-1 text-[10px] font-semibold rounded-full bg-green-200 text-green-700 z-10">
                      Available
                    </span>
                    <img src="<?= !empty($vehicle['photo']) ? '/../uploads/vehicles/' . htmlspecialchars($vehicle['photo']) : '/public/assets/img/car.jpg' ?>"
                        alt="Vehicle" class="w-full h-52 mx-auto rounded-lg object-cover">
                  </div>
                  <div class="p-3 space-y-2">
                    <h2 class="text-base font-semibold"><?= htmlspecialchars($vehicle['vehicle_name']) ?></h2>
                    <p class="text-xs">Last Maintenance Date: <span class="font-medium">—</span></p>
                    <h2 class="text-xs font-semibold text-primary">
                      Assigned Driver: <span class="ml-2"><?= htmlspecialchars($vehicle['driver_name'] ?? 'Unassigned') ?></span>
                    </h2>
                    <div class="flex text-[9px] text-gray-700 space-x-2">
                      <p class="bg-gray-300 px-2 py-1 rounded-xl">Plate: <span class="font-medium"><?= htmlspecialchars($vehicle['plate_no']) ?></span></p>
                      <p class="bg-gray-300 px-2 py-1 rounded-xl">Type: <span class="font-medium"><?= htmlspecialchars($vehicle['vehicle_type']) ?></span></p>
                      <p class="bg-gray-300 px-2 py-1 rounded-xl">Capacity: <span class="font-medium"><?= htmlspecialchars($vehicle['capacity']) ?></span></p>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="col-span-full text-center text-gray-500">No vehicles found.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>


        <!-- ✅ Hidden table for export -->
        <table id="table" class="hidden">
          <thead>
            <tr>
              <th>Vehicle Name</th>
              <th>Plate Number</th>
              <th>Capacity</th>
              <th>Vehicle Type</th>
              <th>Driver</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($vehicles as $v): ?>
              <tr>
                <td><?= htmlspecialchars($v['vehicle_name']) ?></td>
                <td><?= htmlspecialchars($v['plate_no']) ?></td>
                <td><?= htmlspecialchars($v['capacity']) ?></td>
                <td><?= htmlspecialchars($v['vehicle_type']) ?></td>
                <td><?= htmlspecialchars($v['driver_name'] ?? 'Unassigned') ?></td>
                <td><?= htmlspecialchars($v['status'] ?? 'Available') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>


        <!-- Right Section (Details) -->
        <div x-show="showDetails" x-cloak
            class="bg-white shadow rounded-lg p-4 max-h-[640px] overflow-y-auto">
          <button @click="showDetails = false" class="text-sm text-gray-500 hover:text-gray-800 float-right">
            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
          </button>

          <!-- ✅ View Mode -->
              <div x-show="!editing" x-transition>
                <div class="text-center mt-4">
                  <img :src="selected.photo" alt="Vehicle" class="w-1/2 h-32 mx-auto rounded-lg mb-3 object-cover">
                  <h2 class="text-lg font-bold" x-text="selected.name"></h2>
                  <p class="text-sm mt-1">Driver: <span x-text="selected.driver"></span></p>
                  <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-700 z-10">
                    <span x-text="selected.status"></span>
                  </span>
                </div>

                <div class="mt-5 text-center">
                  <button 
                    @click="editing = true" 
                    class="btn btn-secondary">
                    Edit Vehicle
                  </button>
                </div>      
                
                <!-- Button to show travel history -->
<div class="vehicle-card btn btn-secondary mt-4 text-center">
    <button @click="() => toggleHistory(selected.vehicle_id, $event.target)" class="mb-2">
        Travel History
        <img src="/public/assets/img/arrow.png" class="inline size-4 ml-1" alt="Arrow Down">
    </button>
    <div class="travel-history mt-2 hidden border-t pt-2"></div>

    <button @click="() => toggleSchedule(selected.vehicle_id, $event.target)" class="mt-2">
        Scheduled Trips
        <img src="/public/assets/img/arrow.png" class="inline size-4 ml-1" alt="Arrow Down">
    </button>
    <div class="scheduled-trips mt-2 hidden border-t pt-2"></div>
</div>


              </div>
              
              <!-- ✅ Edit Mode -->
              <div 
                x-show="editing" 
                x-transition 
                x-data="{
                  confirmEdit() {
                    Swal.fire({
                      title: 'Confirm Changes?',
                      text: 'Are you sure you want to update this vehicle\'s details?',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, save it!'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        this.saveEdit();
                      }
                    });
                  },

                  handlePhotoUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                      const reader = new FileReader();
                      reader.onload = (e) => {
                        this.selected.photo = e.target.result;
                      };
                      reader.readAsDataURL(file);
                    }
                  },

                  saveEdit() {
                    const formData = new FormData();
                    formData.append('vehicle_id', this.selected.vehicle_id); // corrected
                    formData.append('vehicle_name', this.selected.name);
                    formData.append('plate_no', this.selected.plate);
                    formData.append('capacity', this.selected.capacity);
                    formData.append('vehicle_type', this.selected.type);
                    formData.append('driver_id', this.selected.driver_id); // corrected
                    formData.append('status', this.selected.status);
                    formData.append('update_vehicle', true);

                    const fileInput = this.$refs.photo;
                    if (fileInput && fileInput.files.length > 0) {
                      formData.append('picture', fileInput.files[0]);
                  }

                    fetch('../../../controllers/VehicleController.php', {
                      method: 'POST',
                      body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                      if (data.success) {
                        Swal.fire({
                          icon: 'success',
                          title: 'Vehicle Updated!',
                          text: 'The vehicle details have been updated successfully.',
                          confirmButtonColor: '#3085d6'
                        });
                        this.editing = false;
                        // Optional: refresh vehicle list dynamically
                      } else {
                        Swal.fire({
                          icon: 'error',
                          title: 'Update Failed',
                          text: data.error || 'Something went wrong. Please try again.'
                        });
                      }
                    })
                    .catch(() => {
                      Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Network or server issue occurred.'
                      });
                    });
                  }
                }"
              >
                <h3 class="text-lg font-bold text-center mt-4 mb-3">Edit Vehicle Details</h3>

                <form @submit.prevent="confirmEdit()" enctype="multipart/form-data" class="space-y-2">

                  <!-- Vehicle Photo Upload -->
                  <div class="w-full">
                    <label class="text-xs text-text mb-1">Vehicle Photo:</label>

                    <div 
                      id="upload-area-edit"
                      class="relative border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 transition duration-300 cursor-pointer p-6 flex flex-col items-center justify-center text-center"
                      @click="$refs.photo.click()"
                    >
                      <template x-if="!selected.photo">
                        <div>
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16l5-5 4 4L21 7M16 7h5v5" />
                          </svg>
                          <p class="text-sm text-gray-600">Click or drag to upload a photo</p>
                          <p class="text-xs text-gray-400 mt-1">Accepted formats: JPG, PNG, JPEG</p>
                        </div>
                      </template>

                      <template x-if="selected.photo">
                        <div class="relative">
                          <img :src="selected.photo" alt="Vehicle" class="max-h-52 w-auto object-contain rounded-lg mx-auto">
                          <button 
                            type="button" 
                            @click="selected.photo = ''"
                            class="absolute top-2 right-2 bg-white bg-opacity-70 hover:bg-opacity-100 text-gray-700 rounded-full p-1 shadow-sm transition"
                            title="Remove image"
                          >
                            <img src="/public/assets/img/exit.png" class="size-4" alt="Close">
                          </button>
                        </div>
                      </template>

                      <input type="file" x-ref="photo" accept="image/*" class="hidden" @change="handlePhotoUpload($event)">
                    </div>
                  </div>

                  <!-- Vehicle Name -->
                  <div>
                    <label class="text-xs text-text mb-1">Vehicle Name<span class="text-red-500">*</span></label>
                    <input type="text" class="w-full input-field" x-model="selected.name" required>
                  </div>

                  <!-- Plate No and Capacity -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                      <label class="text-xs text-text mb-1">Plate Number<span class="text-red-500">*</span></label>
                      <input type="text" class="w-full input-field" x-model="selected.plate" required>
                    </div>
                    <div>
                      <label class="text-xs text-text mb-1">Capacity<span class="text-red-500">*</span></label>
                      <input type="number" class="w-full input-field" x-model="selected.capacity" required>
                    </div>
                  </div>

                  <!-- Type -->
                  <div>
                    <label class="text-xs text-text mb-1">Vehicle Type<span class="text-red-500">*</span></label>
                    <input type="text" class="w-full input-field" x-model="selected.type" required>
                  </div>

                  <!-- Driver -->
                  <div>
                    <label class="text-xs text-text mb-1">Driver<span class="text-red-500">*</span></label>
                    <select class="w-full input-field" x-model="selected.driver_id" required>
                      <!-- First option: current driver -->
                      <option :value="selected.driver_id" x-text="selected.driver" selected></option>

                      <!-- Other drivers from PHP -->
                      <?php foreach($drivers as $driver): ?>
                        <option 
                          value="<?= $driver['driver_id'] ?>" 
                          x-show="<?= $driver['driver_id'] ?> != selected.driver_id"
                        >
                          <?= htmlspecialchars($driver['driver_name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <!-- Status -->
                  <div>
                    <label class="text-xs text-text mb-1">Status<span class="text-red-500">*</span></label>
                    <select class="w-full input-field" x-model="selected.status">
                      <option>Available</option>
                      <option>In Use</option>
                      <option>Under Maintenance</option>
                    </select>
                  </div>

                  <!-- Buttons -->
                  <div class="flex justify-center gap-3 pt-3">
                    <button type="button" @click="editing = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary px-7">Save Changes</button>
                  </div>
                </form>
              </div>
        </div>
      </div>
    </div>
  </main>
  <script src="/public/assets/js/shared/export.js"></script>
</body>
</html>