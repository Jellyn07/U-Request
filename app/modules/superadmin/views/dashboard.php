<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';

// ✅ Date range display (example)
$startDate = "Jan 1";
$endDate = date('M d');
$dateRange = "$startDate - $endDate";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Superadmin Dashboard</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <!-- 📅 Date Display -->
    <div class="absolute top-7 right-8 bg-white p-2 px-4 rounded-xl shadow border border-gray-300 text-sm">
      Showing stats from <span class="font-semibold"><?= $dateRange ?></span>
    </div>
    <div class="p-6 space-y-6">
      <!-- Header -->
      <h1 class="text-2xl font-bold mb-4">Dashboard</h1>

      <!-- Summary Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-5 bg-white p-6 rounded-2xl shadow">
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Overall Requests</h2>
          <p class="text-4xl font-bold text-text mt-2">199</p>
          <p class="text-xs text-gray-500 font-medium mt-2">Overall request this year</p>
        </div>
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Pending Vehicle Requests</h2>
          <p class="text-4xl font-bold text-text mt-2">56</p>
          <p class="text-xs text-gray-500 font-medium mt-2">Total pending vehicle requests</p>
        </div>
        <div class="border-r-2 border-gray-300">
          <h2 class="font-medium mb-3">Pending Repair Requests</h2>
          <p class="text-4xl font-bold text-text mt-2">89</p>
          <p class="text-xs text-gray-500 font-medium mt-2">Total pending repair requests</p>
        </div>
        <div>
          <h2 class="font-medium mb-3">Average Rating</h2>
          <div class="flex items-center space-x-2 mt-2">
            <span class="text-4xl font-bold text-yellow-500">4.5</span>
            <div id="averageStars" class="flex"></div>
          </div>
          <p class="text-xs text-gray-500 font-medium mt-2">Average rating this year</p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid md:grid-cols-2 gap-6 mb-5">
       <div class="bg-white p-4 rounded-2xl shadow">
          <h3 class="font-semibold text-text text-base text-center mb-2">Monthly Request Overview</h3>
          <div class="w-full h-64 flex justify-center">
            <canvas id="monthlyLineChart"></canvas>
          </div>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow">
          <h3 class="font-semibold text-text text-base text-center mb-2">Request Distribution</h3>
          <div class="w-full h-64 flex justify-center">
            <canvas id="requestPieChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Activities -->
      <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Vehicle Requests -->
        <div class="bg-white rounded-2xl shadow-md pt-5 pb-0">
          <h2 class="text-lg font-bold mb-3 pl-5 text-primary">Recent Vehicle Requests</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-left">
              <thead class="text-xs uppercase text-gray-700 border-b-gray-400 border-b">
                <tr>
                  <th class="px-4 py-2">Date</th>
                  <th class="px-4 py-2">Requester</th>
                  <th class="px-4 py-2">Vehicle</th>
                  <th class="px-4 py-2">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php for($i=0;$i<10;$i++){
                  echo '<tr class="border-b hover:bg-gray-100 cursor-pointer text-xs">
                    <td class="px-4 py-3">Oct 25, 2025</td>
                    <td class="px-4 py-3">John Dela Cruz</td>
                    <td class="px-4 py-3">N/A</td>
                    <td class="px-4 py-3"><span class="font-semibold bg-orange-100 text-orange-700 px-3 py-1 rounded-full">Pending</span></td>
                  </tr>';
                 } ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Repair Requests -->
        <div class="bg-white rounded-2xl shadow-md pt-5 pb-0">
          <h2 class="text-lg font-bold mb-3 pl-5 text-primary">Recent Repair Requests</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-left">
              <thead class="text-xs uppercase text-gray-700 border-b-gray-400 border-b">
                <tr>
                  <th class="px-4 py-2">Date</th>
                  <th class="px-4 py-2">Requester</th>
                  <th class="px-4 py-2">Facility</th>
                  <th class="px-4 py-2">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php for($i=0;$i<10;$i++){
                  echo '<tr class="border-b hover:bg-gray-100 cursor-pointer text-xs">
                    <td class="px-4 py-3">Oct 25, 2025</td>
                    <td class="px-4 py-3">Ben Dela Cruz</td>
                    <td class="px-4 py-3">Admin Office</td>
                    <td class="px-4 py-3"><span class="font-semibold bg-green-200 text-green-800 px-3 py-1 rounded-full">Completed</span></td>
                  </tr>';
                 } ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
  </main>

  <script src="/public/assets/js/shared/menus.js"></script>

  <!-- Chart Scripts -->
  <script>
    // Monthly Line Chart
    const lineCtx = document.getElementById('monthlyLineChart').getContext('2d');
    new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [
          {
            label: 'Vehicle Requests',
            data: [10,15,12,18,20,25,22,19,30,28,35,40],
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.1)',
            tension: 0.3,
            fill: true
          },
          {
            label: 'Repair Requests',
            data: [8,10,14,16,19,22,20,23,25,29,32,38],
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22,163,74,0.1)',
            tension: 0.3,
            fill: true
          }
        ]
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
      }
    });

    document.addEventListener('DOMContentLoaded', () => {
      fetch('../../../controllers/DashboardController.php?request_status=1')
        .then(response => response.json())
        .then(data => {
          const ctx = document.getElementById('requestPieChart').getContext('2d');

          new Chart(ctx, {
            type: 'pie',
            data: {
              labels: ['Vehicle Requests', 'Repair Requests'],
              datasets: [{
                data: [
                  20,
                  30
                ],
                backgroundColor: [
                  '#16a34a',
                  '#2563eb'
                ],
                borderWidth: 1
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              layout: {
                padding: {
                  top: 10,
                  bottom: 10,
                  left: 10,
                  right: 10
                }
              },
              plugins: {
                legend: {
                  position: 'right',
                  labels: {
                    boxWidth: 15,
                    boxHeight: 8,
                    padding: 10
                  }
                }
              },
              radius: '100%' // smaller circle to add spacing inside the div
            }
          });
        })
        .catch(error => console.error('Error loading chart data:', error));
    });


  </script>
  <script src="/public/assets/js/shared/stars.js"></script>
</body>
</html>
