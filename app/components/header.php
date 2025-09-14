<?php
require_once __DIR__ . '/../../app/config/constants.php';
require_once __DIR__ . '/../controllers/ProfileController.php';
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../models/ProfileModel.php';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
  .logout-icon {
    content: url('/public/assets/img/logout.png');
    transition: content 0.2s ease-in-out;
  }

  button:hover .logout-icon {
    content: url('/public/assets/img/logout-white.png');
  }
</style>
<header class="sticky top-0 z-50 bg-background text-text p-3">
  <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between w-full">
      <!-- Logo Left -->
      <div class="flex items-center flex-shrink-0">
        <!-- <a href="#" class="block text-primary"> -->
          <img id="logo-img" src="/public/assets/img/logo_light.png" alt="Logo" class="h-10 w-10">
          <p class="text-text font-bold pl-2">U<span class="text-accent">-</span>REQUEST</p>
        <!-- </a> -->
      </div>
      <!-- Desktop Nav Right -->
      <nav class="hidden md:flex flex-1 justify-center mr-10">
        <ul class="flex items-center gap-6 text-sm">
          <li><a class="text-sm font-medium transition hover:text-accent <?php echo $current_page === 'request.php' ? 'active-underline' : ''; ?>" href="request.php">REQUEST</a></li>
          <li><a class="text-sm font-medium transition hover:text-accent <?php echo $current_page === 'tracking.php' ? 'active-underline' : ''; ?>" href="tracking.php">TRACKING</a></li>
        </ul>
      </nav>
      <!-- User Menu Right -->
      <div class="flex items-center gap-4">
        <div class="relative hidden md:block">
          <button id="profile-btn" type="button" class="flex items-center">
            <div class="flex items-center">
              <img 
                src="<?php echo htmlspecialchars(!empty($profile['profile_pic']) ? $profile['profile_pic'] : '/public/assets/img/user-default.png'); ?>" 
                alt="<?php echo htmlspecialchars($profile['cust_name'] ?? 'User Profile'); ?>" 
                class="w-9 h-9 rounded-full object-cover border border-secondary shadow-sm mr-2" 
              />
              <span>
                <?php echo htmlspecialchars($profile['firstName'] ?? ''); ?>
                <?php echo htmlspecialchars($profile['lastName'] ?? ''); ?>
              </span>
            </div>
          </button>
          
          <!-- Dropdown menu -->
          <div id="profile-dropdown" class="absolute right-0 z-10 mt-2 w-48 rounded-md border bg-background shadow-lg hidden">
            <div class="p-2">
              <a href="profile.php" class="block rounded-lg px-4 py-2 text-sm text-text hover:bg-secondary hover:text-white transition" role="menuitem">My Profile</a>
              <!-- <button id="dropdown-toggle-dark" type="button" class="flex items-center gap-2 w-full text-left rounded-lg px-4 py-2 text-sm text-text hover:bg-secondary hover:text-white transition" role="menuitem">
                <span id="dropdown-dark-toggle-label">Dark Mode</span>
                <span class="relative inline-block w-10 align-middle select-none transition duration-200 ease-in">
                  <span id="dropdown-dark-toggle-slider" class="absolute left-0 top-0 w-5 h-5 bg-background border border-accent rounded-full shadow transition-transform duration-200"></span>
                  <span class="block w-10 h-5 rounded-full border border-accent bg-background"></span>
                </span>
              </button> -->
              <form method="POST" onclick="window.location.href='/app/controllers/LogoutController.php';">
                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-sm text-accent hover:bg-secondary hover:text-white transition" role="menuitem">
                  <img src="/public/assets/img/logout.png" alt="User" class="size-4 object-cover overflow-hidden logout-icon" />
                  Logout
                </button>
              </form>
            </div>
          </div>
        </div>
        <!-- Mobile menu button -->
        <div class="block md:hidden">
          <button id="mobile-menu-btn" class="rounded-sm bg-secondary p-2 text-text transition hover:text-accent focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
    <!-- Mobile nav: only visible on mobile, slides down -->
    <nav id="mobile-menu" class="md:hidden mt-2 hidden">
      <ul class="flex flex-col items-start gap-2 text-sm bg-background p-4 rounded shadow">
        <li><a class="text-text transition hover:text-accent <?php echo $current_page === 'request.php' ? 'active-underline' : ''; ?>" href="/src/pages/user/request.php">Request</a></li>
        <li><a class="text-text transition hover:text-accent <?php echo $current_page === 'tracking.php' ? 'active-underline' : ''; ?>" href="/src/pages/user/tracking.php">Tracking</a></li>
        <li class="w-full border-t border-secondary my-2"></li>
        <li>
          <!-- <button id="mobile-toggle-dark" type="button" class="flex items-center gap-2 w-full text-left rounded-lg px-4 py-2 text-sm text-text hover:bg-secondary hover:text-accent transition">
            <span id="mobile-dark-toggle-label">Dark Mode</span>
            <span class="relative inline-block w-10 align-middle select-none transition duration-200 ease-in">
              <span id="mobile-dark-toggle-slider" class="absolute left-0 top-0 w-5 h-5 bg-background border border-secondary rounded-full shadow transition-transform duration-200"></span>
              <span class="block w-10 h-5 bg-accent rounded-full"></span>
            </span>
          </button> -->
        </li>
        <li>
          <a href="#" class="block rounded-lg px-4 py-2 text-sm text-text hover:bg-secondary hover:text-accent transition">My Profile</a>
        </li>
        <li>
          <form method="POST" action="#">
            <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-sm text-accent hover:bg-secondary transition" role="menuitem">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
              </svg>
              Logout
            </button>
          </form>
        </li>
      </ul>
    </nav>
  </div>
  <script src="/public/assets/js/user/header.js"></script>
</header>