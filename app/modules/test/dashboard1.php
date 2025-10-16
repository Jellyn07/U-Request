<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>U-Request Dashboards</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --text: #170202;
      --background: #ffffff;
      --primary: #750000;
      --secondary: #d11100;
      --accent: #ff0000;
    }

    body {
      background: var(--background);
      color: var(--text);
      font-family: 'Inter', sans-serif;
    }

    /* Universal Scroll Style */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary);
      border-radius: 20px;
    }

    ::-webkit-scrollbar-track {
      background: transparent;
    }
  </style>
</head>
<body class="overflow-y-scroll">

  <!-- SUPERADMIN DASHBOARD -->
  <section class="p-6 space-y-6 bg-gray-50 min-h-screen text-[var(--text)]">
    <h1 class="text-2xl font-bold text-[var(--primary)]">Superadmin Dashboard</h1>

    <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4">
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Total Requests</h2>
        <p class="text-2xl font-bold text-[var(--secondary)]">1,250</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Pending Requests</h2>
        <p class="text-2xl font-bold text-[var(--secondary)]">85</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Completion Rate</h2>
        <p class="text-2xl font-bold text-[var(--primary)]">92%</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Average Feedback</h2>
        <p class="text-2xl font-bold text-[var(--accent)]">4.6 ★</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Active Admins</h2>
        <p class="text-2xl font-bold text-[var(--secondary)]">5</p>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <div class="bg-white p-4 rounded-2xl shadow">
        <h3 class="font-semibold text-[var(--primary)] mb-2">Requests by Unit</h3>
        <div class="h-48 flex items-center justify-center text-gray-400">[ Chart Placeholder ]</div>
      </div>
      <div class="bg-white p-4 rounded-2xl shadow">
        <h3 class="font-semibold text-[var(--primary)] mb-2">Admin Performance</h3>
        <div class="h-48 flex items-center justify-center text-gray-400">[ Table Placeholder ]</div>
      </div>
    </div>
  </section>

  <!-- GSU ADMIN DASHBOARD -->
  <section class="p-6 space-y-6 bg-gray-50 min-h-screen text-[var(--text)] border-t border-gray-200">
    <h1 class="text-2xl font-bold text-[var(--primary)]">GSU Admin Dashboard</h1>

    <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4">
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Total Repair Requests</h2>
        <p class="text-2xl font-bold text-[var(--secondary)]">420</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Pending / Ongoing / Completed</h2>
        <p class="text-2xl font-bold text-[var(--primary)]">10 / 15 / 395</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Top Problem Areas</h2>
        <p class="text-2xl font-bold text-[var(--accent)]">Engineering Bldg.</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Average Completion Time</h2>
        <p class="text-2xl font-bold text-[var(--secondary)]">2.3 days</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Active Personnel</h2>
        <p class="text-2xl font-bold text-[var(--primary)]">12</p>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <div class="bg-white p-4 rounded-2xl shadow">
        <h3 class="font-semibold text-[var(--primary)] mb-2">Requests by Building</h3>
        <div class="h-48 flex items-center justify-center text-gray-400">[ Chart Placeholder ]</div>
      </div>
      <div class="bg-white p-4 rounded-2xl shadow">
        <h3 class="font-semibold text-[var(--primary)] mb-2">Personnel Workload Summary</h3>
        <div class="h-48 flex items-center justify-center text-gray-400">[ Table Placeholder ]</div>
      </div>
    </div>
  </section>

  <!-- MOTORPOOL ADMIN DASHBOARD -->
  <section class="p-6 space-y-6 bg-gray-50 min-h-screen text-[var(--text)] border-t border-gray-200">
    <h1 class="text-2xl font-bold text-[var(--primary)]">Motorpool Admin Dashboard</h1>

    <div class="grid md:grid-cols-5 sm:grid-cols-2 gap-4">
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Total Vehicle Requests</h2>
        <p class="text-2xl font-bold text-[var(--secondary)]">310</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Pending / Approved / Completed</h2>
        <p class="text-2xl font-bold text-[var(--primary)]">5 / 12 / 293</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Available vs In-Use Vehicles</h2>
        <p class="text-2xl font-bold text-[var(--accent)]">8 / 4</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Active Drivers</h2>
        <p class="text-2xl font-bold text-[var(--secondary)]">6</p>
      </div>
      <div class="p-4 bg-white shadow rounded-2xl text-center">
        <h2 class="text-sm font-semibold text-gray-600">Maintenance Alerts</h2>
        <p class="text-2xl font-bold text-[var(--primary)]">2</p>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <div class="bg-white p-4 rounded-2xl shadow">
        <h3 class="font-semibold text-[var(--primary)] mb-2">Upcoming Trips Schedule</h3>
        <div class="h-48 flex items-center justify-center text-gray-400">[ Schedule Placeholder ]</div>
      </div>
      <div class="bg-white p-4 rounded-2xl shadow">
        <h3 class="font-semibold text-[var(--primary)] mb-2">Driver Trip Summary</h3>
        <div class="h-48 flex items-center justify-center text-gray-400">[ Table Placeholder ]</div>
      </div>
    </div>
  </section>

</body>
</html>
