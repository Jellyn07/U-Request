<aside class="bg-primary w-64 h-screen rounded-r-2xl text-text flex flex-col fixed z-50">
  <!-- Logo / Title -->
  <div class="flex items-center flex-shrink-0 px-4 pt-6">
      <img id="logo-img" src="/public/assets/img/logo_dark.png" alt="Logo" class="h-10 w-10">
      <div class="pl-1">
      <p class="text-white font-bold">U<span class="text-accent">-</span>REQUEST</p>
      <p class="text-xs text-gray-300">Super Admin</p>        
      </div>
  </div>
  <!-- Sidebar content split into menu + logout -->
  <div class="flex flex-col flex-1">
    
    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto text-sm text-white">
      <ul class="space-y-2 p-2 mt-4">
        
        <!-- Dashboard -->
        <li class="flex p-2 rounded-lg hover:bg-accent transition">
          <img src="/public/assets/img/dashboard.png" alt="Dashboard" class="size-4 object-cover overflow-hidden self-center mr-2" />
          <a href="dashboard.php" class="flex items-center">
            Dashboard
          </a>
        </li>

        <!-- Account Management -->
        <li>
          <button class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition">
            <img src="/public/assets/img/accout-management.png" alt="Account Management" class="size-5 object-cover overflow-hidden mr-2" />
            <span class="flex items-center">
              Account Management
            </span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="size-5 object-cover overflow-hidden mr-2 p-1 ml-auto" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1 ">
            <li>
              <a href="manage_user.php" class="flex p-2 rounded-lg hover:bg-accent transition">
                <img src="/public/assets/img/manage-users.png" alt="Manage Users" class="size-5 object-cover overflow-hidden mr-2" />
                Manage Users
              </a>
            </li>
            <li>
              <a href="manage_admin.php" class="flex p-2 rounded-lg hover:bg-accent transition">
                <img src="/public/assets/img/manage-admins.png" alt="Manage Users" class="size-5 object-cover overflow-hidden mr-2" />
                Manage Admins
              </a>
            </li>
          </ul>
        </li>

        <!-- GSU Oversight -->
        <li>
          <button class="accordion flex items-center  w-full p-2 rounded-lg hover:bg-accent transition">
            <img src="/public/assets/img/repair-admin.png" alt="GSU Oversight" class="size-4 object-cover overflow-hidden self-center mr-2" />
              <span class="flex items-center">
                GSU Oversight
              </span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="size-5 object-cover overflow-hidden mr-2 p-1 ml-auto" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Personnels</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Materials</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Request</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Documents</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Campus Locations</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Feedback</a></li>  
          </ul>
        </li>

        <!-- Motorpool Oversight -->
        <li>
          <button class="accordion flex items-center  w-full p-2 rounded-lg hover:bg-accent transition">
            <img src="/public/assets/img/car-admin.png" alt="Motorpool Oversight" class="size-4 object-cover overflow-hidden self-center mr-2" />
              <span class="flex items-center">
                Motorpool Oversight
              </span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="size-5 object-cover overflow-hidden mr-2 p-1 ml-auto" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Drivers</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Vehicles</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Request</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Scheduling / Dispatch</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Fuel Monitoring</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Feedback</a></li>
          </ul>
        </li>

        <!-- Reports -->
        <li>
          <button class="accordion flex items-center  w-full p-2 rounded-lg hover:bg-accent transition">
            <img src="/public/assets/img/reports.png" alt="Reports & Analytics" class="size-4 object-cover overflow-hidden self-center mr-2" />
              <span class="flex items-center">
                Reports & Analytics
              </span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="size-5 object-cover overflow-hidden mr-2 p-1 ml-auto" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Requests Summary</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Activity Logs</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Export Data</a></li>
          </ul>
        </li>

        <!-- Settings -->
        <li>
          <button class="accordion flex items-center  w-full p-2 rounded-lg hover:bg-accent transition">
            <img src="/public/assets/img/settings.png" alt="System Settings" class="size-4 object-cover overflow-hidden self-center mr-2" />
              <span class="flex items-center">
                System Settings
              </span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="size-5 object-cover overflow-hidden mr-2 p-1 ml-auto" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">Logs & Audit Trail</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">User Permissions</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition">My Profile</a></li>
          </ul>
        </li>

      </ul>
    </nav>

    <!-- Logout fixed at bottom -->
    <div class="p-4 mt-auto text-sm">
      <a href="/app/modules/shared/views/admin_login.php" class="flex items-center  w-full p-2 rounded-lg hover:bg-accent transition">
        <img src="/public/assets/img/logout-white.png" alt="Logout" class="size-4 object-cover overflow-hidden self-center mr-2" />
          <span class="flex items-center text-white">
            Logout
          </span>
      </a>
    </div>
  </div>
</aside>
<!-- <main class="ml-64 flex-1 bg-primary rounded-l-2xl">

</main> -->
<!-- Accordion JS -->
<script>
  document.querySelectorAll(".accordion").forEach(button => {
    button.addEventListener("click", () => {
      const submenu = button.nextElementSibling;

      // Close all other submenus
      document.querySelectorAll(".submenu").forEach(menu => {
        if (menu !== submenu) {
          menu.classList.add("hidden");
        }
      });

      // Toggle the clicked submenu
      submenu.classList.toggle("hidden");
    });
  });
</script>
