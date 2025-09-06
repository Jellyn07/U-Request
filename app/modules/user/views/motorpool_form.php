<?php
require_once __DIR__ . '/../../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>U-Request</title>
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
    <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
  </head>
  <body class="bg-background">
    <form id="vehicle-form" action="tracking.php" class="w-1/2 m-5 mx-auto rounded-lg border border-gray-100 bg-white p-4 shadow-lg transition hover:shadow-lg sm:p-6">
    <div id="rrs-form">
      <div id="header" class="flex flex-col items-center justify-center mb-4">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 mb-2">
        <h4 class="text-center text-lg font-semibold">
          VEHICLE REQUEST FORM
        </h4>
      </div>

      <div class="mb-4">
        <label class="text-sm text-text mb-1">
          Purpose of Trip:
          <span class="text-accent">*</span>
        </label>
  <input type="text" required class="input-field w-full" name="purpose_of_trip" placeholder="e.g., Field Trip to Davao City.">
      </div>

      <div class="mb-4">
        <label class="text-sm text-text mb-1">
          Travel Destination:
          <span class="text-accent">*</span>
        </label>
  <input type="text" required class="input-field w-full" name="travel_destination">
      </div>

      <div class="mb-4">
        <label class="text-sm text-text mb-1">
          Date of Travel:
          <span class="text-accent">*</span>
        </label>
  <input type="date" required class="input-field w-full" name="date_of_travel">
      </div>

      <div class="mb-4">
      <label class="text-sm text-text mb-1">
        Date of Return:
        <span class="text-accent">*</span>
      </label>
  <input type="date" required class="input-field w-full" name="date_of_return">
      </div>

      <div class="mb-4">
        <label class="text-sm text-text mb-1">
          Time of Departure:
          <span class="text-accent">*</span>
        </label>
  <input type="time" required class="input-field w-full" name="time_of_departure">
      </div>

      <div class="mb-4">
      <label class="text-sm text-text mb-1">
        Time of Return:
        <span class="text-accent">*</span>
      </label>
  <input type="time" required class="input-field w-full" name="time_of_return">
      </div>

      <div class="mb-4">
        <label class="text-sm text-text mb-1">
          Name of Passengers:
          <span class="text-accent">*</span>
        </label>
        <div id="passenger-fields">
          <div class="flex gap-2 w-full passenger-row">
            <div class="flex flex-col w-1/2">
              <label class="text-sm text-text mb-1">
                First Name
              </label>
            </div>
            <div class="flex flex-col w-1/2">
              <label class="text-sm text-text mb-1">
                Last Name
              </label>
            </div>
          </div>
          <div class="flex gap-2 w-full passenger-row">
            <div class="flex flex-col w-1/2">
              <input type="text" required class="input-field" name="first_name[]">
            </div>
            <div class="flex flex-col w-1/2">
              <input type="text" required class="input-field" name="last_name[]">
            </div>
            <button type="button" id="add-passenger" onclick="addPassengerField()">
              <!-- <img src="<?php echo PUBLIC_URL; ?>/assets/img/plus.png" alt="Add Passenger" class="w-6 h-6"> -->
               <p class="text-xl">+</p>
            </button>
          </div>
        </div>
      </div>
      <br>
      <div class="flex flex-row items-center justify-center gap-4 mt-4">
        <button type="button" class="btn btn-secondary" onclick="location.href='request.php'">
          Cancel
        </button>
        <button class="btn btn-primary" type="submit">
          Submit
        </button>
      </div>
    </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/public/assets/js/user/forms.js"></script>
  </body>
</html>