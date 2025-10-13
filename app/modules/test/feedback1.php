<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
require_once __DIR__ . '/../../../controllers/AdminController.php';

// ⭐ Function to display dynamic star ratings (supports half stars)
function renderStars($rating) {
  $stars = '<div class="flex items-center space-x-1">';
  for ($i = 1; $i <= 5; $i++) {
    if ($rating >= $i) {
      // Full star
      $stars .= '<svg class="w-4 h-4 text-yellow-400 fill-current"  viewBox="0 0 24 24">
                  <path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 
                           12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/>
                </svg>';
    } elseif ($rating >= $i - 0.5) {
      // Half star using gradient
      $stars .= '<svg  class="w-4 h-4 text-yellow-400" viewBox="0 0 24 24">
        <defs>
          <linearGradient id="half-' . $i . '">
            <stop offset="50%" stop-color="currentColor"/>
            <stop offset="50%" stop-color="lightgray"/>
          </linearGradient>
        </defs>
        <path fill="url(#half-' . $i . ')" 
              d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 
                 12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/>
      </svg>';
    } else {
      // Empty star
      $stars .= '<svg class="w-4 h-4 text-gray-300 fill-current"  viewBox="0 0 24 24">
                  <path d="M12 .587l3.668 7.431L24 9.753l-6 5.847L19.335 24 
                           12 19.897 4.665 24 6 15.6 0 9.753l8.332-1.735z"/>
                </svg>';
    }
  }
  $stars .= '</div>';
  return $stars;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/gsu_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Feedback Insights</h1>
        <div class="grid grid-cols-3 md:grid-cols-3 gap-2 mb-4">
          <div class="bg-white shadow rounded-lg p-4 text-center">
            <h2 class="text-gray-600 text-xs">Average Requests Feedback</h2>
            <p class="text-xl font-bold text-primary" id="total_pending">
              4.5
            </p>
          </div>
          <div class="bg-white shadow rounded-lg p-4 text-center">
            <h2 class="text-gray-600 text-xs">Average Personnels Feedback</h2>
            <p class="text-xl font-bold text-primary" id="total_pending">
              4.5
            </p>
          </div>
          <div class="bg-white shadow rounded-lg p-4 text-center">
            <h2 class="text-gray-600 text-xs">Total Feedback</h2>
            <p class="text-xl font-bold text-primary" id="total_pending">
              100
            </p>
          </div>
        </div>

        <div class="col-span-3 transition-all duration-300">
          <div class="p-3 flex  bg-white shadow rounded-lg">
            <!-- Search + Filters + Buttons -->
            <input type="text" id="search" placeholder="Search by name" class="flex-1 min-w-[200px] max-w-[150px] input-field">
            <form method="get" id="sortForm">
              <select name="order" class="input-field">
                <option value="az">Sort A-Z</option>
                <option value="za">Sort Z-A</option>
                <option value="date">Date Modified</option>
              </select>
            </form>
          </div>
        </div>

        <!-- Feedback Cards -->
        <div class="grid grid-cols-1 gap-4 mt-5">
          <?php
          // Example feedbacks — replace this with DB query later
          $feedbacks = [
            ['user' => 'John Doe', 'rating' => 4.5, 'message' => 
                'The repair request was handled quickly. Personnel were professional and polite. The repair request was handled quickly. Personnel were professional and polite.', 
                'type' => 'Repair', 'date' => 'Oct 4, 2025'],
            ['user' => 'Jane Smith', 'rating' => 3.0, 'message' => 
                'Good service, but the response time can still improve.', 
                'type' => 'Vehicle', 'date' => 'Oct 3, 2025'],
            ['user' => 'Carlos Reyes', 'rating' => 5.0, 'message' => 
                'Excellent! Very smooth process.', 
                'type' => 'Repair', 'date' => 'Oct 2, 2025']
          ];

          foreach ($feedbacks as $fb):
          ?>
            <div class="bg-white shadow-sm h-42 rounded-xl p-4 border border-gray-100 hover:shadow-md transition">
              <div class="flex justify-between items-start mb-2">
                <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($fb['user']) ?></h3>
                <?= renderStars($fb['rating']) ?>
              </div>
              <p class="text-gray-600 text-sm"><?= htmlspecialchars($fb['message']) ?></p>
              <div class="mt-3 text-xs text-gray-400 flex justify-between">
                <span><?= htmlspecialchars($fb['date']) ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
    </div>
  </main>
</body>

<script src="/public/assets/js/shared/menus.js"></script>
