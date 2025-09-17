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
            <img src="/public/assets/img/dashboard.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:h-6 md:w-6" />
            <span class="hidden md:flex items-center sidebar-text">Dashboard</span>
          </a>

        <!-- Account Management -->
        <li>
          <button class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/accout-management.png" alt="Account Management" class="size-8 p-1.5 md:p-0 md:h-6 md:w-6" />
            <span class="hidden md:flex items-center sidebar-text">Account Management</span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="hidden md:flex size-5 object-cover overflow-hidden mr-2 p-1 ml-auto sidebar-text arrows transition-transform duration-300" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li>
              <a href="manage_user.php" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">
                <img src="/public/assets/img/manage-users.png" alt="Manage Users" class="size-5 object-cover overflow-hidden mr-2" />
                Manage Users
              </a>
            </li>
            <li>
              <a href="manage_admin.php" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">
                <img src="/public/assets/img/manage-admins.png" alt="Manage Admins" class="size-5 object-cover overflow-hidden mr-2" />
                Manage Admins
              </a>
            </li>
          </ul>
        </li>

        <!-- GSU Oversight -->
        <li>
          <button class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/repair-admin.png" alt="GSU Oversight" class="size-8 p-1.5 md:p-0 md:h-6 md:w-6" />
            <span class="hidden md:flex items-center sidebar-text">GSU Oversight</span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="hidden md:flex size-5 object-cover overflow-hidden mr-2 p-1 ml-auto sidebar-text arrows transition-transform duration-300" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Personnels</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Materials</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Request</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Documents</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Campus Locations</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Feedback</a></li>  
          </ul>
        </li>

        <!-- Motorpool Oversight -->
        <li>
          <button class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/car-admin.png" alt="Motorpool Oversight" class="size-8 p-1.5 md:p-0 md:h-6 md:w-6 self-center" />
            <span class="hidden md:flex items-center sidebar-text">Motorpool Oversight</span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="hidden md:flex size-5 object-cover overflow-hidden mr-2 p-1 ml-auto sidebar-text arrows transition-transform duration-300" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Drivers</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Vehicles</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Request</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Scheduling / Dispatch</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Fuel Monitoring</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Feedback</a></li>
          </ul>
        </li>

        <!-- Reports -->
        <li>
          <button class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/reports.png" alt="Reports & Analytics" class="size-8 p-1.5 md:p-0 md:h-6 md:w-6 self-center" />
            <span class="hidden md:flex items-center sidebar-text">Reports & Analytics</span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="hidden md:flex size-5 object-cover overflow-hidden mr-2 p-1 ml-auto sidebar-text arrows transition-transform duration-300" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Activity Logs</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Export Data</a></li>
          </ul>
        </li>

        <!-- Settings -->
        <!-- <li>
          <button class="accordion flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/settings.png" alt="System Settings" class="size-8 p-1.5 md:p-0 md:h-6 md:w-6 self-center" />
            <span class="hidden md:flex items-center sidebar-text">System Settings</span>
            <img src="/public/assets/img/arrow.png" alt="arrows" class="hidden md:flex size-5 object-cover overflow-hidden mr-2 p-1 ml-auto sidebar-text arrows transition-transform duration-300" />
          </button>
          <ul class="submenu hidden pl-5 space-y-2 mt-1">
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">Logs & Audit Trail</a></li>
            <li><a href="#" class="flex p-2 rounded-lg hover:bg-accent transition sidebar-text">User Permissions</a></li>
          </ul>
        </li> -->
      </ul>
    </nav>

    <!-- Profile dropdown at bottom -->
    <div class="p-2 md:p-3 mt-auto text-sm relative">
      <button id="profileButton" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition">
        <img src="/public/assets/img/user-default.png" alt="Profile" class="w-8 h-8 md:h-10 md:w-10 border border-white rounded-full object-cover">
        <div class="hidden md:block pl-2 text-left">
          <p class="text-white text-sm font-medium">Admin Name</p>
          <p class="text-xs text-gray-300">Super Admin</p>
        </div>
      </button>
      <!-- Dropdown -->
      <div id="profileMenu" class="hidden absolute bottom-4 left-20 md:bottom-20 md:left-4 w-48 bg-white text-black rounded-lg border-gray-400 shadow-lg">
        <a href="#" class="block rounded-lg px-4 py-2 hover:bg-gray-100">
          My Profile
        </a>
        <a href="/app/modules/shared/views/admin_login.php" class="flex rounded-lg px-4 py-2 text-accent hover:bg-gray-100 items-center gap-1">
          <img src="/public/assets/img/logout.png" alt="Profile" class="h-4 w-4">
          Logout
        </a>
      </div>
    </div>
  </div>
</aside>

<!-- Accordion & Profile JS -->
<script>
  // Accordion
  document.querySelectorAll(".accordion").forEach(button => {
    button.addEventListener("click", () => {
      const submenu = button.nextElementSibling;
      const arrow = button.querySelector("img.arrows");

      // Close all other submenus and reset their arrows
      document.querySelectorAll(".submenu").forEach(menu => {
        if (menu !== submenu) {
          menu.classList.add("hidden");
        }
      });
      document.querySelectorAll(".accordion img.arrows").forEach(img => {
        if (img !== arrow) {
          img.classList.remove("rotate-180");
        }
      });

      // Toggle the clicked one
      submenu.classList.toggle("hidden");
      arrow.classList.toggle("rotate-180");
    });
  });


  // Sidebar collapse
  // const sidebar = document.getElementById("sidebar");
  // const toggleSidebar = document.getElementById("toggleSidebar");
  // const logoText = document.getElementById("logo-text");
  // const sidebarTexts = document.querySelectorAll(".sidebar-text");

  // toggleSidebar.addEventListener("click", () => {
  //   sidebar.classList.toggle("w-64");
  //   sidebar.classList.toggle("w-15");
  //   logoText.classList.toggle("hidden");
  //   sidebarTexts.forEach(el => el.classList.toggle("hidden"));
  // });

  // Profile dropdown
  const profileButton = document.getElementById("profileButton");
  const profileMenu = document.getElementById("profileMenu");

  profileButton.addEventListener("click", () => {
    profileMenu.classList.toggle("hidden");
  });

  // Close dropdown on click outside
  document.addEventListener("click", (e) => {
    if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
      profileMenu.classList.add("hidden");
    }
  });
</script>
