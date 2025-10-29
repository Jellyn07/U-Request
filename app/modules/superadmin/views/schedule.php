<?php
session_start();
require_once __DIR__ . '/../../../controllers/ScheduleController.php';
$controller = new ScheduleController();
$trips = $controller->fetchTrips();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Motorpool Schedule</title>
  <link rel="stylesheet" href="/public/assets/css/output.css">
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
</head>
<body class="bg-gray-100">
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>

  <main class="ml-16 md:ml-64 p-6 flex flex-col min-h-screen">
    <h1 class="text-2xl font-bold mb-4">Schedules</h1>

    <div class="flex justify-center items-center mb-4 gap-5">
      <button id="prevMonth"><img src="/public/assets/img/left-arrow.png" class="w-4 h-4"></button>
      <h2 id="monthYear" class="text-lg font-semibold text-center"></h2>
      <button id="nextMonth"><img src="/public/assets/img/right-arrow.png" class="w-4 h-4"></button>
    </div>

    <div class="bg-white rounded-lg p-4 shadow-md max-h-[695px]">
      <div id="calendar" class="grid grid-cols-7 gap-2 text-sm p-1">
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Sun</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Mon</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Tue</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Wed</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Thu</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Fri</div>
        <div class="text-center font-semibold bg-red-200 py-2 rounded-lg">Sat</div>
      </div>
    </div>
  </main>

  <!-- Modal -->
  <div id="tripModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-md p-6 relative">
      <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
      <h3 class="font-bold text-lg mb-4">Trip Details</h3>
      <div id="modalBody" class="flex flex-col gap-2"></div>
    </div>
  </div>

  <!-- Pass data to JS -->
  <script>
    const trips = <?php echo json_encode($trips); ?>;
  </script>
  <script src="/public/assets/js/motorpool_admin/schedule.js"></script>
</body>
</html>
