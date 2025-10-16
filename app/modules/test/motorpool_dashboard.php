<?php
session_start();
require_once __DIR__ . '/../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>U-Request | Motorpool Dashboard</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[var(--background)] text-[var(--text)]">
  <?php include COMPONENTS_PATH . '/motorpool_menu.php'; ?>

  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6 space-y-6">

      <h1 class="text-2xl font-bold text-[var(--primary)]">Motorpool Dashboard</h1>

      <!-- Summary Cards -->
      <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4">
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Total Vehicle Requests</h2>
          <p class="text-2xl font-bold text-[var(--secondary)]">350</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Pending / Approved / Completed</h2>
          <p class="text-2xl font-bold text-[var(--info)]">20 / 50 / 280</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Available / In-Use Vehicles</h2>
          <p class="text-2xl font-bold text-[var(--success)]">30 / 15</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Active Drivers</h2>
          <p class="text-2xl font-bold text-[var(--primary)]">12</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Upcoming Trips</h2>
          <p class="text-2xl font-bold text-[var(--accent)]">5</p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-[var(--card-bg)] p-4 rounded-2xl shadow">
          <h3 class="text-lg font-semibold text-[var(--primary)] mb-3">Vehicle Usage</h3>
          <div class="w-full h-40">
            <canvas id="vehicleChart"></canvas>
          </div>
        </div>
        <div class="bg-[var(--card-bg)] p-4 rounded-2xl shadow">
          <h3 class="text-lg font-semibold text-[var(--primary)] mb-3">Driver Trip Summary</h3>
          <div class="w-full h-40">
            <canvas id="driverChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Requests Table -->
      <div class="bg-[var(--card-bg)] p-4 rounded-2xl shadow">
        <h3 class="text-lg font-semibold text-[var(--primary)] mb-3">Recent Vehicle Requests</h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-[var(--text)] border">
            <thead class="text-xs uppercase bg-[var(--primary)] text-white">
              <tr>
                <th class="px-4 py-2">Request ID</th>
                <th class="px-4 py-2">Vehicle</th>
                <th class="px-4 py-2">Driver</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b hover:bg-gray-100">
                <td class="px-4 py-2">REQ-201</td>
                <td class="px-4 py-2">Van A</td>
                <td class="px-4 py-2">John</td>
                <td class="px-4 py-2 text-[var(--secondary)] font-semibold">Pending</td>
                <td class="px-4 py-2">Oct 13, 2025</td>
              </tr>
              <tr class="hover:bg-gray-100">
                <td class="px-4 py-2">REQ-202</td>
                <td class="px-4 py-2">Bus B</td>
                <td class="px-4 py-2">Maria</td>
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
    new Chart(document.getElementById('vehicleChart'), {
      type: 'bar',
      data: {
        labels: ['Van A', 'Van B', 'Bus A', 'Bus B', 'Car A'],
        datasets: [{
          label: 'Trips',
          data: [12, 8, 15, 10, 5],
          backgroundColor: ['#d11100','#ff0000','#16a34a','#2563eb','#750000'],
          borderRadius: 6
        }]
      },
      options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('driverChart'), {
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
