
<?php
// always start the session at the very top
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<aside id="sidebar" class="bg-primary w-16 md:w-64 h-screen rounded-r-2xl text-text flex flex-col fixed z-50 transition-all duration-300">
  <!-- Logo / Title -->
  <div class="flex items-center flex-shrink-0 px-4 pt-5 gap-1">
      <img id="logo-img" src="/public/assets/img/logo_dark.png" alt="Logo" class="h-8 w-8 sidebar-text">
      <div class="pl-1 hidden md:flex" id="logo-text">
        <p class="text-white font-bold">U<span class="text-accent">-</span>REQUEST</p>
      </div>

      <!-- <button id="toggleSidebar" class="text-white focus:outline-none ml-auto">
        <img src="/public/assets/img/burger-bar.png" alt="Menu" class="size-4 object-cover overflow-hidden self-center mr-2" />
      </button> -->
  </div>

  <!-- Sidebar content split into menu + profile -->
  <div class="flex flex-col flex-1">
    
    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto text-sm text-white mt-2">
      <ul class="space-y-2 p-2">
        <!-- Dashboard -->
        <a href="dashboard.php" class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
          <img src="/public/assets/img/dashboard.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
          <span class="hidden md:flex items-center sidebar-text">Dashboard</span>
        </a>

        <!-- Account Management -->
        <li>
          <button class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/personnel.png" alt="Account Management" class="size-8 p-1.5 md:p-0 md:size-4" />
            <span class="hidden md:flex items-center sidebar-text">Account Management</span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="hidden md:flex size-4 object-cover overflow-hidden mr-2 p-1 ml-auto sidebar-text arrows transform transition-transform duration-300" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li>
              <a href="manage_user.php" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">
                <img src="/public/assets/img/accout-management.png" alt="Manage Users" class="size-5 object-cover overflow-hidden mr-2" />
                Users
              </a>
            </li>
            <li>
              <a href="manage_admin.php" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">
                <img src="/public/assets/img/admin-management.png" alt="Manage Admins" class="size-5 object-cover overflow-hidden mr-2" />
                Admins
              </a>
            </li>
          </ul>
        </li>

        <!-- GSU Oversight -->
        <li>
          <button class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/repair-admin.png" alt="GSU Oversight" class="size-8 p-1.5 md:p-0 md:size-4" />
            <span class="hidden md:flex items-center sidebar-text">GSU Oversight</span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="hidden md:flex size-4 object-cover overflow-hidden mr-2 p-1 ml-auto sidebar-text arrows transform transition-transform duration-300" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li>
              <a href="gsu_request.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
                <img src="/public/assets/img/repair-request.png" alt="request" class="size-8 p-1.5 md:p-0 md:size-4" />
                <span class="hidden md:flex items-center sidebar-text">Request</span>
              </a>
            </li>
            <li>
              <a href="inventory.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
                <img src="/public/assets/img/inventory.png" alt="inventory" class="size-8 p-1.5 md:p-0 md:size-4" />
                <span class="hidden md:flex items-center sidebar-text">Inventory</span>
              </a>  
            </li>
            <li>
              <a href="personnel.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
                <img src="/public/assets/img/personnel.png" alt="personnel" class="size-8 p-1.5 md:p-0 md:size-4" />
                <span class="hidden md:flex items-center sidebar-text">Personnels</span>
              </a>
            </li>
            <li>
              <a href="location.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
                <img src="/public/assets/img/campus-locations.png" alt="location" class="size-8 p-1.5 md:p-0 md:size-4" />
                <span class="hidden md:flex items-center sidebar-text">Campus Locations</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Motorpool Oversight -->
        <li>
          <button class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/car2.png" alt="Motorpool Oversight" class="size-8 p-1.5 md:p-0 md:size-5 self-center" />
            <span class="hidden md:flex items-center sidebar-text">Motorpool Oversight</span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="hidden md:flex size-4 object-cover overflow-hidden mr-2 p-1 ml-auto sidebar-text arrows transform transition-transform duration-300" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li>
              <a href="motorpool_request.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
                <img src="/public/assets/img/request-for-proposal.png" alt="request" class="size-8 p-1.5 md:p-0 md:size-4" />
                <span class="hidden md:flex items-center sidebar-text">Request</span>
              </a>
            </li>
            <li>
              <a href="schedule.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
                <img src="/public/assets/img/calendar.png" alt="schedule" class="size-8 p-1.5 md:p-0 md:size-4" />
                <span class="hidden md:flex items-center sidebar-text">Schedule</span>
              </a>
            </li>
            <li>
              <a href="vehicle.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
                <img src="/public/assets/img/car-admin.png" alt="vehicle" class="size-8 p-1.5 md:p-0 md:size-4" />
                <span class="hidden md:flex items-center sidebar-text">Vehicle</span>
              </a>
            </li>
            <li>
              <a href="driver.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
                <img src="/public/assets/img/driver.png" alt="drivers" class="size-8 p-1.5 md:p-0 md:size-4" />
                <span class="hidden md:flex items-center sidebar-text">Drivers</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Activity Logs -->
        <a href="logs.php" class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
          <img src="/public/assets/img/activity-logs.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
          <span class="hidden md:flex items-center sidebar-text">Activity Logs</span>
        </a>

        <!-- Feedbacks -->
        <a href="feedback.php" class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
          <img src="/public/assets/img/feedback.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
          <span class="hidden md:flex items-center sidebar-text">Feedbacks</span>
        </a>

        <!-- Backup & Restore -->
        <a href="backup.php" class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
          <img src="/public/assets/img/backup.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
          <span class="hidden md:flex items-center sidebar-text">Backup & Restore</span>
        </a>
        
      </ul>
    </nav>

      <!-- Profile dropdown at bottom -->
      <div class="p-2 md:p-3 mt-auto text-sm relative">
        <button id="profileButton" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition">
          <img src="/public/assets/img/user-default.png" alt="Profile" class="w-8 h-8 md:h-10 md:w-10 border border-white rounded-full object-cover">

          <div class="hidden md:block pl-2 text-left">
            <p class="text-white text-sm font-medium">
              <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Guest'); ?>
            </p>
            <p class="text-xs text-gray-300">
              Super Admin
            </p>
          </div>
        </button>
      </div>

      <!-- Dropdown -->
      <div id="profileMenu" class="hidden absolute bottom-4 left-20 md:bottom-20 md:left-4 w-48 bg-white text-black rounded-lg border-gray-400 shadow-lg">
        <a href="#" class="block rounded-lg px-4 py-2 hover:bg-gray-100 text-sm">
          My Profile
        </a>
        <a href="/app/modules/shared/views/admin_login.php" class="flex rounded-lg px-4 py-2 text-accent hover:bg-gray-100 items-center gap-1 text-sm">
          <img src="/public/assets/img/logout.png" alt="Profile" class="h-4 w-4">
          Logout
        </a>
      </div>
    </div>
  </div>
</aside>
<script src="/public/assets/js/shared/superadmin_menu.js"></script>
