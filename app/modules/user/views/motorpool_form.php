<?php
require_once __DIR__ . '/../../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>U-Request | Vehicle Request Form</title>
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
    <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo PUBLIC_URL; ?>/assets/js/helpers.js"></script>
  </head>

  <body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex items-center justify-center">
    <form 
      id="vehicle-form" 
      action="../../../controllers/VehicleRequestController.php" 
      method="POST" 
      class="w-full md:w-3/5 lg:w-1/2 my-10 mx-2 rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-lg transition hover:shadow-xl"
    >
      <input type="hidden" name="form_action" value="submitRequest">
      <!-- HEADER -->
      <div class="flex flex-col items-center justify-center mb-6">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 mb-2">
        <h2 class="text-center text-2xl font-semibold">
          VEHICLE REQUEST FORM
        </h2>
        <p class="text-xs text-gray-500 mt-1">Fields marked with <span class="text-red-500">*</span> are required.</p>
      </div>

      <!-- TRIP DETAILS -->
      <h4 class="text-base font-semibold mb-2">Trip Information</h4>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
          <label class="text-sm mb-1 block">Purpose of Trip <span class="text-red-500">*</span></label>
          <input type="text" name="purpose_of_trip" placeholder="Ex. Field Trip" required class="input-field w-full ">
        </div>
        <div>
          <label class="text-sm mb-1 block">Travel Destination <span class="text-red-500">*</span></label>
          <input type="text" name="travel_destination" required placeholder="Ex. Davao City" class="input-field w-full ">
        </div>
        <div>
          <label class="text-sm mb-1 block">Date of Travel <span class="text-red-500">*</span></label>
          <input type="date" id="date_of_travel" name="date_of_travel" required class="input-field w-full">
        </div>
        <div>
          <label class="text-sm mb-1 block">Date of Return <span class="text-red-500">*</span></label>
          <input type="date" id="date_of_return" name="date_of_return" required class="input-field w-full">
        </div>
        <div>
          <label class="text-sm mb-1 block">Time of Departure <span class="text-red-500">*</span></label>
          <input type="time" name="time_of_departure" required class="input-field w-full">
        </div>
        <div>
          <label class="text-sm mb-1 block">Time of Return <span class="text-red-500">*</span></label>
          <input type="time" name="time_of_return" required class="input-field w-full">
        </div>
      </div>

      <hr class="my-6 border-gray-400">

      <!-- PASSENGERS -->
      <h4 class="text-base font-semibold mb-2">Passenger Information</h4>
      <div id="passenger-fields" class="space-y-3 mb-6">
        <div class="flex gap-2 passenger-row items-end">
          <div class="w-1/2">
            <label class="text-sm mb-1 block">First Name <span class="text-red-500">*</span></label>
            <input type="text" name="first_name[]" required class="input-field w-full">
          </div>
          <div class="w-1/2">
            <label class="text-sm mb-1 block">Last Name <span class="text-red-500">*</span></label>
            <input type="text" name="last_name[]" required class="input-field w-full">
          </div>
          <button type="button" id="add-passenger" onclick="addPassengerField()" title="Add Passenger"
            class="bg-primary hover:bg-secondary text-white rounded-full w-9 h-9 flex justify-center shadow-md items-center"
          >
            <img src="<?php echo PUBLIC_URL; ?>/assets/img/add_white.png" alt="Add" class="w-3 h-3">
          </button>
        </div>
      </div>

      <hr class="my-6 border-gray-400">

      <!-- SOURCE OF FUND -->
      <h4 class="text-base font-semibold mb-2">Source of Fund</h4>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
          <label class="text-sm mb-1 block">Fuel <span class="text-red-500">*</span></label>
          <input type="text" name="source_of_fuel" placeholder="Ex. Donation" required class="input-field w-full">
        </div>
        <div>
          <label class="text-sm mb-1 block">Oil <span class="text-red-500">*</span></label>
          <input type="text" name="source_of_oil" placeholder="Ex. Collection" required class="input-field w-full">
        </div>
        <div>
          <label class="text-sm mb-1 block">Repair/Maintenance <span class="text-red-500">*</span></label>
          <input type="text" name="source_of_repair_maintenance" placeholder="Ex. Own Money" required class="input-field w-full">
        </div>
        <div>
          <label class="text-sm mb-1 block">Driver/Assistant Per Diem <span class="text-red-500">*</span></label>
          <input type="text" name="source_of_driver_assistant_per_diem" placeholder="Ex. Collection" required class="input-field w-full">
        </div>
      </div>

      <hr class="my-6 border-gray-400">

      <!-- CONTACT & CERTIFICATION -->
      <!-- <div class="mb-6">
        <label class="text-sm mb-1 block">Contact No <span class="text-red-500">*</span></label>
        <input type="text" name="contactNo" required class="input-field w-full">
      </div> -->

      <div class="flex items-start mb-6">
        <input type="checkbox" name="certify" required class="mt-0.5 mr-2 h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-600">
        <p class="text-sm">
          I hereby certify that all information provided in this form is true and correct.
        </p>
      </div>

      <!-- BUTTONS -->
      <div class="flex justify-center gap-4">
        <button type="button" onclick="location.href='request.php'"class="btn btn-secondary">
          Cancel
        </button>
        <button type="submit" class="btn btn-primary">
          Submit
        </button>
      </div>

      <!-- FOOTER -->
      <p class="text-xs text-gray-500 text-center mt-6">
        © 2025 University of Southeastern Philippines — U-Request System
      </p>
    </form>

    <!-- JS -->
    <script>
      function addPassengerField() {
          const container = document.getElementById('passenger-fields');
          const currentCount = container.querySelectorAll('.passenger-row').length;

          if (currentCount >= 60) {
              Swal.fire({
                  icon: 'warning',
                  title: 'Maximum Limit Reached',
                  text: 'You have reached the maximum of 60 passengers. Please submit and request again for the remaining passengers.',
                  confirmButtonColor: '#800000'
              });
              return;
          }

          const newRow = document.createElement('div');
          newRow.classList.add('flex', 'gap-2', 'passenger-row', 'items-end', 'mt-2');
          newRow.innerHTML = `
              <div class="w-1/2">
                  <input type="text" name="first_name[]" required placeholder="First Name" class="input-field w-full">
              </div>
              <div class="w-1/2">
                  <input type="text" name="last_name[]" required placeholder="Last Name" class="input-field w-full">
              </div>
              <button type="button" class="bg-gray-700 hover:bg-gray-600 text-gray-700 rounded-full w-9 h-9 flex items-center justify-center" onclick="this.parentElement.remove()" title="Remove Passenger">
                  <img src="<?php echo PUBLIC_URL; ?>/assets/img/minus.png" alt="Minus" class="w-3 h-3">
              </button>
          `;
          container.appendChild(newRow);
      }
      document.addEventListener('DOMContentLoaded', () => {
        const travelInput = document.getElementById('date_of_travel');
        const returnInput = document.getElementById('date_of_return');

        // Helper to format date as YYYY-MM-DD
        const formatDate = (date) => date.toISOString().split('T')[0];

        const today = new Date();

        // Travel date must be at least 3 days from today
        const minTravelDate = new Date(today);
        minTravelDate.setDate(today.getDate() + 3);

        travelInput.min = formatDate(minTravelDate);

        // When travel date changes, set return date min
        travelInput.addEventListener('change', () => {
          if (!travelInput.value) return;
          const travelDate = new Date(travelInput.value);

          // Return must be after travel date
          const minReturnDate = new Date(travelDate);
          minReturnDate.setDate(travelDate.getDate() + 0);

          returnInput.min = formatDate(minReturnDate);
        });
      });
    </script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
      const travelDate = document.getElementById('date_of_travel');
      const returnDate = document.getElementById('date_of_return');
      const timeDeparture = document.querySelector('input[name="time_of_departure"]');
      const timeReturn = document.querySelector('input[name="time_of_return"]');

      function validateTime() {
        if (!travelDate.value || !returnDate.value || !timeDeparture.value) return;

        // Convert times to minutes for comparison
        const [depHour, depMin] = timeDeparture.value.split(':').map(Number);
        const departureMinutes = depHour * 60 + depMin;

        const [retHour, retMin] = (timeReturn.value || "00:00").split(':').map(Number);
        const returnMinutes = retHour * 60 + retMin;

        // If travel and return date are same, enforce rule
        if (travelDate.value === returnDate.value) {

          // MINIMUM hours required (change 5 if you want)
          const minHours = 5;
          const minReturn = departureMinutes + minHours * 60;

          // Set min time allowed for return
          const minHour = Math.floor(minReturn / 60).toString().padStart(2, '0');
          const minMinute = (minReturn % 60).toString().padStart(2, '0');
          const minTimeString = `${minHour}:${minMinute}`;

          timeReturn.min = minTimeString;

          // If selected time is invalid, reset it
          if (returnMinutes < minReturn) {
            timeReturn.value = "";
            Swal.fire({
              icon: "warning",
              title: "Invalid Time",
              text: `If you return the SAME DAY, the return time must be at least ${minHours} hours after departure (${minTimeString}).`,
            });
          }
        } 
        else {
          // If dates are different, clear restrictions
          timeReturn.min = "";
        }
      }

      travelDate.addEventListener('change', validateTime);
      returnDate.addEventListener('change', validateTime);
      timeDeparture.addEventListener('change', validateTime);
      timeReturn.addEventListener('change', validateTime);
    });
    </script>
  </body>
</html>
