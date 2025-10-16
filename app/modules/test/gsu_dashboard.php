<?php
session_start();
require_once __DIR__ . '/../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | GSU Dashboard</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[var(--background)] text-[var(--text)]">
  <?php include COMPONENTS_PATH . '/gsu_menu.php'; ?>

  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6 space-y-6">

      <h1 class="text-2xl font-bold text-[var(--primary)]">GSU Dashboard</h1>

      <!-- Summary Cards -->
      <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4">
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Total Repair Requests</h2>
          <p class="text-2xl font-bold text-[var(--secondary)]">420</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Pending / Ongoing / Completed</h2>
          <p class="text-2xl font-bold text-[var(--info)]">10 / 15 / 395</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Top Problem Areas</h2>
          <p class="text-2xl font-bold text-[var(--accent)]">Engineering Bldg.</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Average Completion Time</h2>
          <p class="text-2xl font-bold text-[var(--success)]">2.3 days</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Active Personnel</h2>
          <p class="text-2xl font-bold text-[var(--primary)]">12</p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-[var(--card-bg)] p-4 rounded-2xl shadow">
          <h3 class="text-lg font-semibold text-[var(--primary)] mb-3">Requests by Building</h3>
          <div class="w-full h-40">
            <canvas id="buildingChart"></canvas>
          </div>
        </div>
        <div class="bg-[var(--card-bg)] p-4 rounded-2xl shadow">
          <h3 class="text-lg font-semibold text-[var(--primary)] mb-3">Personnel Workload</h3>
          <div class="w-full h-40">
            <canvas id="workloadChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Requests Table -->
      <div class="bg-[var(--card-bg)] p-4 rounded-2xl shadow">
        <h3 class="text-lg font-semibold text-[var(--primary)] mb-3">Recent Repair Requests</h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-[var(--text)] border">
            <thead class="text-xs uppercase bg-[var(--primary)] text-white">
              <tr>
                <th class="px-4 py-2">Request ID</th>
                <th class="px-4 py-2">Building</th>
                <th class="px-4 py-2">Issue</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b hover:bg-gray-100">
                <td class="px-4 py-2">REQ-101</td>
                <td class="px-4 py-2">Engineering</td>
                <td class="px-4 py-2">Aircon Repair</td>
                <td class="px-4 py-2 text-[var(--secondary)] font-semibold">Pending</td>
                <td class="px-4 py-2">Oct 13, 2025</td>
              </tr>
              <tr class="hover:bg-gray-100">
                <td class="px-4 py-2">REQ-102</td>
                <td class="px-4 py-2">Library</td>
                <td class="px-4 py-2">Light Replacement</td>
                <td class="px-4 py-2 text-[var(--accent)] font-semibold">Ongoing</td>
                <td class="px-4 py-2">Oct 12, 2025</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>

  <script src="/public/assets/js/shared/menus.js"></script>
  <script>
    new Chart(document.getElementById('buildingChart'), {
      type: 'bar',
      data: {
        labels: ['Engineering', 'Library', 'Admin', 'Canteen', 'Gym'],
        datasets: [{
          label: 'Requests',
          data: [30, 25, 20, 10, 5],
          backgroundColor: ['#750000','#d11100','#ff0000','#16a34a','#2563eb'],
          borderRadius: 6
        }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('workloadChart'), {
      type: 'doughnut',
      data: {
        labels: ['John', 'Maria', 'Alex', 'Lara', 'Peter'],
        datasets: [{
          data: [12, 8, 15, 10, 5],
          backgroundColor: ['#750000','#d11100','#ff0000','#16a34a','#2563eb']
        }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, cutout: '60%' }
    });
  </script>
</body>
</html>
