<?php
session_start();
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../controllers/UserController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | U-Request</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
  <!-- Header -->
  <header class="bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold text-blue-600">U-Request Dashboard</h1>
    <a href="logout.php" class="text-sm text-gray-500 hover:text-red-500">Logout</a>
  </header>

  <!-- Dashboard Content -->
  <main class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Example Card -->
    <div class="bg-white p-6 rounded-2xl shadow-md">
      <h2 class="text-gray-700 font-semibold mb-2">Total Requests</h2>
      <p class="text-3xl font-bold text-indigo-600">120</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-md">
      <h2 class="text-gray-700 font-semibold mb-2">Completed Tasks</h2>
      <p class="text-3xl font-bold text-green-600">98</p>
    </div>

    <!-- ⭐ Feedback Summary Card -->
    <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-gray-700 font-semibold text-lg">Feedback Summary</h2>
        <a href="feedback.php" class="text-sm text-blue-500 hover:underline">View Details</a>
      </div>

      <div class="flex items-center mb-3">
        <!-- Average Rating -->
        <span class="text-3xl font-bold text-yellow-500 mr-2">4.6</span>
        <!-- Stars -->
        <div class="flex space-x-1">
          <?php for ($i = 0; $i < 5; $i++): ?>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.286 3.966c.3.921-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.176 0l-3.385 2.46c-.785.57-1.84-.197-1.54-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.047 9.393c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.966z"/>
            </svg>
          <?php endfor; ?>
        </div>
      </div>

      <p class="text-sm text-gray-500">Based on 250 feedback entries this month</p>
    </div>
  </main>
</body>
</html>
