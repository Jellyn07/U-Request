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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div>
            <label for="unit" class="text-sm  mb-1 block">
              Unit <span class="text-red-500">*</span>
            </label>
            <select id="unit" name="unit" required class="input-field w-full ">
              <option value="" selected disabled>Select Unit</option>
              <option value="Tagum Unit">Tagum Unit</option>
              <option value="Mabini Unit">Mabini Unit</option>
            </select>
          </div>

          <div>
            <label for="buildingLoc" class="text-sm  mb-1 block">
              Building Location <span class="text-red-500">*</span>
            </label>
            <input type="text" name="exLocb" id="exLocb" placeholder="Ex. PECC" required class="input-field w-full ">
          </div>

          <div>
            <label for="roomLoc" class="text-sm  mb-1 block">
              Room Location <span class="text-red-500">*</span>
            </label>
            <input type="text" name="exLocr" id="exLocr" placeholder="Ex. Clinic" required class="input-field w-full ">
          </div>

          <div>
            <label for="picture" class="text-sm  mb-1 block">
              Photo <span class="text-red-500">*</span>
            </label>
            <input type="file" id="img" name="picture" required class="block w-full px-3 py-1.5 input-field text-sm file:mr-3 file:py-1 file:text-xs file:px-3 file:rounded-md file:border-0 file:text-white file:hover:bg-secondary file:bg-primary">
          </div>
        </div>

        <hr class="my-6 border-gray-400">

        <!-- SECTION: Request Info -->
        <h4 class="text-base font-semibold mb-2">Request Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <div>
            <label for="dateNoticed" class="text-sm  mb-1 block">
              Date the Issue was Noticed <span class="text-red-500">*</span>
            </label>
            <input type="date" id="dateNoticed" name="dateNoticed" class="input-field w-full cursor-not-allowed" value="<?php echo date('Y-m-d'); ?>" readonly
            >
          </div>
        </div>

        <!-- Nature of Request -->
        <div class="mb-6">
          <label class="text-sm  mb-2 block font-medium">
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
              <label for="others" class="text-sm ">Others:</label>
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
  </body>
</html>
