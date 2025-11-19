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
        <a href="../../gsu_admin/views/dashboard.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
          <img src="/public/assets/img/dashboard.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
          <span class="hidden md:flex items-center sidebar-text">Dashboard</span>
        </a>
        <a href="../../shared/views/request.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
          <img src="/public/assets/img/repair-request.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
          <span class="hidden md:flex items-center sidebar-text">Request</span>
        </a>
        <a href="../../shared/views/inventory.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
          <img src="/public/assets/img/inventory.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
          <span class="hidden md:flex items-center sidebar-text">Inventory</span>
        </a>
        <a href="../../shared/views/personnel.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
          <img src="/public/assets/img/personnel.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
          <span class="hidden md:flex items-center sidebar-text">Personnels</span>
        </a>
        <!-- <a href="documents.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/documents.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
            <span class="hidden md:flex items-center sidebar-text">Documents</span>
          </a> -->
          <a href="../../gsu_admin/views/users.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/user.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
            <span class="hidden md:flex items-center sidebar-text">Users</span>
          </a>
          <a href="../../shared/views/location.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/campus-locations.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
            <span class="hidden md:flex items-center sidebar-text">Campus Locations</span>
          </a>
          <a href="../../gsu_admin/views/logs.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/activity-logs.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
            <span class="hidden md:flex items-center sidebar-text">Activity Logs</span>
          </a>
          <a href="../../gsu_admin/views/feedback.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
            <img src="/public/assets/img/feedback.png" alt="dashboard" class="size-8 p-1.5 md:p-0 md:size-4" />
            <span class="hidden md:flex items-center sidebar-text">Feedbacks</span>
          </a>
          <?php if (!empty($_SESSION['canSeeAdminManagement'])): ?>
          <a href="../../shared/views/manage_admin.php" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition gap-2">
              <img src="/public/assets/img/admin-management.png" class="size-8 p-1.5 md:p-0 md:size-4" />
              <span class="hidden md:flex sidebar-text">Admin Management</span>
          </a>
          <?php endif; ?>
      </ul>
    </nav>

    <!-- Profile dropdown at bottom -->
    <div class="p-2 md:p-3 mt-auto text-sm relative">
      <button id="profileButton" class="flex items-center w-full p-2 rounded-lg hover:bg-accent transition">
        <img id="profile-preview"
           src="<?php echo !empty($profile['profile_picture']) 
            ? '/public/uploads/profile_pics/' . htmlspecialchars($profile['profile_picture']) 
            : '/public/assets/img/user-default.png'; ?>" 
          class="w-10 h-10 rounded-full object-cover border border-secondary shadow-sm" />

        <?php
        $defaultPic = '/public/assets/img/user-default.png';
        $profilePic = (!empty($profile['profile_picture']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $profile['profile_picture']))
          ? $profile['profile_picture']
          : $defaultPic;
        ?>
        <div class="hidden md:block pl-2 text-left">
          <p class="text-white text-sm font-medium">
            <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Guest'); ?>
          </p>
          <p class="text-xs text-gray-300">
            GSU Admin
          </p>
        </div>
      </button>
    </div>
    <!-- Dropdown -->
    <div id="profileMenu" class="hidden absolute bottom-4 left-20 md:bottom-20 md:left-4 w-48 bg-white text-black rounded-lg border-gray-400 shadow-lg">
      <a href="../../shared/views/profile.php" class="block rounded-lg px-4 py-2 hover:bg-gray-100 text-sm">
        My Profile
      </a>
      <a href="/app/modules/shared/views/admin_login.php" class="flex text-sm rounded-lg px-4 py-2 text-accent hover:bg-gray-100 items-center gap-1">
        <img src="/public/assets/img/logout.png" alt="Profile" class="h-4 w-4">
        Logout
      </a>
    </div>
  </div>
  </div>
</aside>