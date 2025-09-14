<?php
session_start();
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/UserController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Superadmin | Users</title>
  <link rel="stylesheet" href="/public/assets/css/output.css" />
  <link rel="icon" href="/public/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
  <!-- Superadmin Menu & Header -->
  <?php include COMPONENTS_PATH . '/superadmin_menu.php'; ?>
  <?php include COMPONENTS_PATH . '/admin_header.php'; ?>

  <main class="ml-64 flex flex-col p-4 min-h-screen">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Users</h1>
      <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        onclick="document.getElementById('addUserModal').classList.remove('hidden')">
        Add User
      </button>
    </div>

    <div class="mb-4">
      <input type="text" id="searchUser" placeholder="Search by name or email" class="border rounded px-2 py-1 w-full">
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody id="usersTable">
          <!-- Dummy Users -->
          <tr>
            <td class="px-4 py-2">1</td>
            <td class="px-4 py-2">John Doe</td>
            <td class="px-4 py-2">john@example.com</td>
            <td class="px-4 py-2">
              <button class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500">Edit</button>
              <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
            </td>
          </tr>
          <tr>
            <td class="px-4 py-2">2</td>
            <td class="px-4 py-2">Mary Lee</td>
            <td class="px-4 py-2">mary@example.com</td>
            <td class="px-4 py-2">
              <button class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500">Edit</button>
              <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Add User Modal -->
  <div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
      <h2 class="text-lg font-bold mb-4">Add User</h2>
      <form>
        <div class="mb-2">
          <label class="block text-sm font-medium">Name</label>
          <input type="text" class="border rounded px-2 py-1 w-full">
        </div>
        <div class="mb-2">
          <label class="block text-sm font-medium">Email</label>
          <input type="email" class="border rounded px-2 py-1 w-full">
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium">Role</label>
          <input type="text" value="user" disabled class="border rounded px-2 py-1 w-full bg-gray-200">
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
          <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('searchUser').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('#usersTable tr');
      rows.forEach(row => {
        const name = row.children[1].textContent.toLowerCase();
        const email = row.children[2].textContent.toLowerCase();
        row.style.display = (name.includes(filter) || email.includes(filter)) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
