<?php
session_start();
require_once __DIR__ . '/../../config/constants.php';
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
<body class="bg-[var(--background)] text-[var(--text)]">
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>

  <main class="ml-16 md:ml-64 flex flex-col min-h-screen transition-all duration-300">
    <div class="p-6 space-y-6">

      <h1 class="text-2xl font-bold text-[var(--primary)]">Superadmin Dashboard</h1>

      <!-- Summary Cards -->
      <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4">
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Total Requests</h2>
          <p class="text-2xl font-bold text-[var(--secondary)]">1,250</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Pending Requests</h2>
          <p class="text-2xl font-bold text-[var(--secondary)]">85</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Completion Rate</h2>
          <p class="text-2xl font-bold text-[var(--success)]">92%</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Average Feedback</h2>
          <p class="text-2xl font-bold text-[var(--accent)]">4.6 ★</p>
        </div>
        <div class="p-4 bg-[var(--card-bg)] shadow rounded-2xl text-center">
          <h2 class="text-sm text-gray-600">Active Admins</h2>
          <p class="text-2xl font-bold text-[var(--info)]">5</p>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-[var(--card-bg)] p-4 rounded-2xl shadow">
          <h3 class="text-lg font-semibold text-[var(--primary)] mb-3">Requests by Unit</h3>
          <div class="w-full h-40">
            <canvas id="unitChart"></canvas>
          </div>
        </div>
        <div class="bg-[var(--card-bg)] p-4 rounded-2xl shadow">
          <h3 class="text-lg font-semibold text-[var(--primary)] mb-3">Admin Performance</h3>
          <div class="w-full h-40">
            <canvas id="adminChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Requests Table -->
      <div class="bg-[var(--card-bg)] p-4 rounded-2xl shadow">
        <h3 class="text-lg font-semibold text-[var(--primary)] mb-3">Recent Requests</h3>
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-[var(--text)] border">
            <thead class="text-xs uppercase bg-[var(--primary)] text-white">
              <tr>
                <th class="px-4 py-2">Request ID</th>
                <th class="px-4 py-2">Unit</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b hover:bg-gray-100">
                <td class="px-4 py-2">REQ-001</td>
                <td class="px-4 py-2">Motorpool</td>
                <td class="px-4 py-2">Vehicle Request</td>
                <td class="px-4 py-2 text-[var(--secondary)] font-semibold">Pending</td>
                <td class="px-4 py-2">Oct 13, 2025</td>
              </tr>
              <tr class="hover:bg-gray-100">
                <td class="px-4 py-2">REQ-002</td>
                <td class="px-4 py-2">GSU</td>
                <td class="px-4 py-2">Repair Request</td>
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
    // Requests by Unit (Bar Chart)
    new Chart(document.getElementById('unitChart'), {
      type: 'bar',
      data: {
        labels: ['GSU', 'Motorpool', 'IT', 'Finance', 'Registrar'],
        datasets: [{
          label: 'Requests',
          data: [80, 65, 50, 35, 20],
          backgroundColor: ['#d11100','#ff0000','#2563eb','#16a34a','#750000'],
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
      }
    });

    // Admin Performance (Line Chart)
    new Chart(document.getElementById('adminChart'), {
      type: 'line',
      data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
          label: 'Completed Requests',
          data: [20, 35, 50, 40],
          borderColor: '#750000',
          backgroundColor: 'rgba(117,0,0,0.2)',
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });
  </script>
</body>
</html>
