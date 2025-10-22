<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
$req_id = $_SESSION['req_id'];
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../config/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>U-Request | Repair Request Form</title>
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
    <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
    <script src="<?php echo PUBLIC_URL; ?>/assets/js/helpers.js"></script>
  </head>

  <body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex items-center justify-center">
    <form 
      name="repair-request" 
      action="../../../controllers/RequestController.php" 
      method="post" 
      enctype="multipart/form-data" 
      class="w-full md:w-3/5 lg:w-1/2 my-10 mx-2 rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-lg transition hover:shadow-xl"
    >
      <div id="repair-form">
        <!-- HEADER -->
        <div id="header" class="flex flex-col items-center justify-center mb-6">
          <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 mb-2">
          <h2 class="text-center text-2xl font-semibold">
            REPAIR REQUEST FORM
          </h2>
          <p class="text-xs text-gray-500 mt-1">Fields marked with <span class="text-red-500">*</span> are required.</p>
        </div>

        <input type="hidden" name="req_id" value="<?php echo htmlspecialchars($req_id); ?>">

        <!-- SECTION: Location Info -->
        <h4 class="text-base font-semibold mb-2">Location Details</h4>
        <!-- <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6 items-start"> -->
          <div class="mb-6">
            <label for="unit" class="text-sm mb-1 block">
              Unit <span class="text-red-500">*</span>
            </label>
            <select id="unit" name="unit" required class="input-field w-full">
              <option value="" selected disabled>Select Unit</option>
              <option value="Tagum Unit">Tagum Unit</option>
              <option value="Mabini Unit">Mabini Unit</option>
            </select>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="exLocb" class="text-sm mb-1 block">Building Location <span class="text-red-500">*</span></label>
              <select id="exLocb" name="exLocb" required class="input-field w-full">
                <option value="" selected disabled>Select Building</option>
              </select>
            </div>

            <div>
              <label for="exLocr" class="text-sm mb-1 block">Room Location <span class="text-red-500">*</span></label>
              <select id="exLocr" name="exLocr" required class="input-field w-full">
                <option value="" selected disabled>Select Room</option>
              </select>
            </div>
          </div>

        <!-- </div> -->

          <hr class="my-6 border-gray-400">
          <div class="w-full">
            <label for="picture" class="text-sm mb-1 block font-medium">
              Photo Evidence <span class="text-red-500">*</span>
            </label>

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
                <img id="preview" src="#" alt="Preview" class="max-h-64 w-auto object-contain rounded-lg mx-auto transition duration-300 group-hover:opacity-80">
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

        <hr class="my-6 border-gray-400">

        <!-- SECTION: Request Info -->
        <h4 class="text-base font-semibold mb-2">Request Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div>
            <label for="dateNoticed" class="text-sm mb-1 block">
              Date the Issue was Noticed <span class="text-red-500">*</span>
            </label>
            <input type="date" id="dateNoticed" name="dateNoticed" class="input-field w-full cursor-not-allowed" value="<?php echo date('Y-m-d'); ?>" readonly>
          </div>
        </div>

        <!-- Nature of Request -->
        <div class="mb-6">
          <label class="text-sm mb-2 block font-medium">
            Nature of Request <span class="text-red-500">*</span>
          </label>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            <?php
            $natures = [
              "Carpentry/Masonry", "Welding", "Hauling", "Plumbing", 
              "Landscaping", "Electrical", "Air-Condition"
            ];
            foreach ($natures as $nature) {
              $id = strtolower(str_replace([' ', '/'], '', $nature));
              echo "
                <label class='flex items-center space-x-2 p-2 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer'>
                  <input type='radio' id='$id' name='nature-request' value='$nature' class='text-red-600 focus:ring-red-600'>
                  <span class='text-sm'>$nature</span>
                </label>
              ";
            }
            ?>
            <div class="flex items-center space-x-2 p-2 border border-gray-200 rounded-lg hover:bg-gray-50">
              <input type="radio" id="others" name="nature-request" value="Others" class="text-red-600 focus:ring-red-600">
              <label for="others" class="text-sm">Others:</label>
              <input type="text" name="other-details" class="border-b border-b-gray-700 focus:border-b-accent px-2 py-0 text-sm w-1/2 focus:outline-none">
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
          <label for="description" class="text-sm mb-1 block font-medium">
            Detailed Description of the Issue <span class="text-red-500">*</span>
          </label>
          <textarea required id="descrip" name="description" rows="3" class="input-field w-full" placeholder="Ex. Water is leaking from the faucet in the Faculty Restroom near Room 205."></textarea>
        </div>

        <!-- Certification -->
        <div class="flex items-start mb-6">
          <input type="checkbox" name="certify" required class="mt-0.5 mr-2 h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-600">
          <p class="text-sm">
            I hereby certify that all information provided in this form is true and correct.
          </p>
        </div>

        <!-- BUTTONS -->
        <div class="flex justify-center gap-4">
          <button type="button" class="btn btn-secondary" onclick="location.href='request.php'">
            Cancel
          </button>
          <button id="submitBtn" type="submit" class="btn btn-primary">
            Submit
          </button>
        </div>

        <p class="text-xs text-gray-500 text-center mt-6">
          © 2025 University of Southeastern Philippines — U-Request System
        </p>
      </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo PUBLIC_URL; ?>/assets/js/user/forms.js"></script>

    <!-- Image Preview & Drag-and-Drop JS -->
    <script>
      function previewImage(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById("preview-container");
        const preview = document.getElementById("preview");
        const uploadArea = document.getElementById("upload-area");

        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove("hidden");
            uploadArea.classList.add("hidden"); // Hide drag-drop area when uploaded
          };
          reader.readAsDataURL(file);
        }
      }

      function removePreview(e) {
        e.stopPropagation();
        const fileInput = document.getElementById("img");
        const previewContainer = document.getElementById("preview-container");
        const uploadArea = document.getElementById("upload-area");

        fileInput.value = "";
        previewContainer.classList.add("hidden");
        uploadArea.classList.remove("hidden");
      }

      function handleDragOver(e) {
        e.preventDefault();
        e.currentTarget.classList.add("bg-gray-200");
      }

      function handleDragLeave(e) {
        e.preventDefault();
        e.currentTarget.classList.remove("bg-gray-200");
      }

      function handleDrop(e) {
        e.preventDefault();
        e.currentTarget.classList.remove("bg-gray-200");
        const fileInput = document.getElementById("img");
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          fileInput.files = files;
          previewImage({ target: fileInput });
        }
      }
    </script>
    <script>
document.getElementById('unit').addEventListener('change', function() {
  const unit = this.value;

  // Reset dropdowns first
  const buildingSelect = document.getElementById('exLocb');
  const roomSelect = document.getElementById('exLocr');
  buildingSelect.innerHTML = '<option value="" selected disabled>Loading...</option>';
  roomSelect.innerHTML = '<option value="" selected disabled>Select Room</option>';

  fetch('../../../controllers/RequestController.php?action=getLocationsByUnit&unit=' + encodeURIComponent(unit))
    .then(response => response.json())
    .then(data => {
      buildingSelect.innerHTML = '<option value="" selected disabled>Select Building</option>';
      roomSelect.innerHTML = '<option value="" selected disabled>Select Room</option>';

      if (data.success && data.locations.length > 0) {
        const buildings = {};

        // Group rooms by building
        data.locations.forEach(loc => {
          if (!buildings[loc.building]) {
            buildings[loc.building] = [];
          }
          buildings[loc.building].push(loc.exact_location);
        });

        // Populate buildings
        Object.keys(buildings).forEach(building => {
          const option = document.createElement('option');
          option.value = building;
          option.textContent = building;
          buildingSelect.appendChild(option);
        });

        // When building changes, populate rooms
        buildingSelect.addEventListener('change', function() {
          const selectedBuilding = this.value;
          roomSelect.innerHTML = '<option value="" selected disabled>Select Room</option>';
          buildings[selectedBuilding].forEach(room => {
            const opt = document.createElement('option');
            opt.value = room;
            opt.textContent = room;
            roomSelect.appendChild(opt);
          });
        });

      } else {
        buildingSelect.innerHTML = '<option value="" selected disabled>No buildings found</option>';
      }
    })
    .catch(err => {
      console.error('Error fetching locations:', err);
      buildingSelect.innerHTML = '<option value="" selected disabled>Error loading</option>';
    });
});
</script>

  </body>
</html>
