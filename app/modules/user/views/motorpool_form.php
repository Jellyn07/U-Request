<?php
require_once __DIR__ . '/../config/constants.php';
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
  <body class="bg-primary">
    <form class="w-1/2 m-5 mx-auto rounded-lg border border-gray-100 bg-white p-4 shadow-lg transition hover:shadow-lg sm:p-6">
    <div id="rrs-form">
      <div id="header" class="flex flex-col items-center justify-center mb-4">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 mb-2">
        <h4 class="text-center text-lg font-semibold">VEHICLE REQUEST FORM</h4>
      </div>

      <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Date of Travel:<span>*</span></label>
      <input type="date" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"><br>

      <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Date of Return:<span>*</span></label>
      <input type="date" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"><br>

      <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Time of Departure:<span>*</span></label>
      <input type="time" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"><br>

      <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Time of Return:<span>*</span></label>
      <input type="time" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition"><br>

      <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Name of Passengers:<span>*</span></label>
      <div id="passenger-fields">
        <div class="flex gap-2 w-full passenger-row">
          <div class="flex flex-col w-1/2">
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">First Name</label>
            <input type="text" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" name="first_name[]">
          </div>
          <div class="flex flex-col w-1/2">
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
            <input type="text" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" name="last_name[]">
          </div>
        </div>
      </div>
      <div class="flex justify-end w-full mt-2">
        <button type="button" id="add-passenger" class="bg-secondary text-white rounded-full w-8 h-8 flex items-center justify-center text-xl shadow" onclick="addPassengerField()">+</button>
      </div>
      <br>
      <div class="flex flex-row items-center justify-center gap-4 mt-4">
        <button type="button" class="inside-block bg-background text-primary p-2 rounded-md text-sm border border-primary" onclick="location.href='request.php'">Back</button>
        <button class="inside-block bg-secondary text-background p-2 rounded-md text-sm" type="submit" onclick="showReview()">Review</button>
      </div>
    </div>
    <script>
      function addPassengerField() {
        const container = document.getElementById('passenger-fields');
        const row = document.createElement('div');
        row.className = 'flex gap-2 w-full passenger-row mt-2';
        row.innerHTML = `
          <div class="flex flex-col w-1/2">
            <input type="text" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" name="first_name[]">
          </div>
          <div class="flex flex-col w-1/2">
            <input type="text" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" name="last_name[]">
          </div>
        `;
        container.appendChild(row);
      }
    </script>
    </form>
  </body>
</html>