<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="bg-background text-text w-full m-5 p-5">
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
          <li><a class="text-sm font-medium transition hover:text-accent <?php echo $current_page === 'request.php' ? 'active-underline' : ''; ?>" href="/src/pages/user/request.php">REQUEST</a></li>
          <li><a class="text-sm font-medium transition hover:text-accent <?php echo $current_page === 'tracking.php' ? 'active-underline' : ''; ?>" href="/src/pages/user/tracking.php">TRACKING</a></li>
        </ul>
      </nav>
      <!-- User Menu Right -->
      <div class="flex items-center gap-4">
        <div class="relative hidden md:block">
          <button id="profile-btn" type="button" class="flex items-center">
            <img src="/public/assets/img/user-default.png" alt="User" class="size-10 object-cover overflow-hidden rounded-full border border-primary shadow-inner focus:outline-none" />
            <p class="pl-3">Username</p>
          </button>
          
          <!-- Dropdown menu -->
          <div id="profile-dropdown" class="absolute right-0 z-10 mt-2 w-48 rounded-md border bg-background shadow-lg hidden">
            <div class="p-2">
              <a href="profile.php" class="block rounded-lg px-4 py-2 text-sm text-text hover:bg-secondary hover:text-white transition" role="menuitem">My Profile</a>
              <button id="dropdown-toggle-dark" type="button" class="flex items-center gap-2 w-full text-left rounded-lg px-4 py-2 text-sm text-text hover:bg-secondary hover:text-white transition" role="menuitem">
                <span id="dropdown-dark-toggle-label">Dark Mode</span>
                <span class="relative inline-block w-10 align-middle select-none transition duration-200 ease-in">
                  <span id="dropdown-dark-toggle-slider" class="absolute left-0 top-0 w-5 h-5 bg-background border border-accent rounded-full shadow transition-transform duration-200"></span>
                  <span class="block w-10 h-5 rounded-full border border-accent bg-background"></span>
                </span>
              </button>
              <form method="POST" action="#">
                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-sm text-accent hover:bg-secondary hover:text-white transition" role="menuitem">
                  <img src="/public/assets/img/logout.png" alt="User" class="size-4 object-cover overflow-hidden" />
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
          <button id="mobile-toggle-dark" type="button" class="flex items-center gap-2 w-full text-left rounded-lg px-4 py-2 text-sm text-text hover:bg-secondary hover:text-accent transition">
            <span id="mobile-dark-toggle-label">Dark Mode</span>
            <span class="relative inline-block w-10 align-middle select-none transition duration-200 ease-in">
              <span id="mobile-dark-toggle-slider" class="absolute left-0 top-0 w-5 h-5 bg-background border border-secondary rounded-full shadow transition-transform duration-200"></span>
              <span class="block w-10 h-5 bg-accent rounded-full"></span>
            </span>
          </button>
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
  <script>
    // Dark mode toggle logic (dropdown and mobile)
    function setDarkModeUI(isDark) {
      var dropdownSlider = document.getElementById('dropdown-dark-toggle-slider');
      var dropdownLabel = document.getElementById('dropdown-dark-toggle-label');
      var mobileSlider = document.getElementById('mobile-dark-toggle-slider');
      var mobileLabel = document.getElementById('mobile-dark-toggle-label');
      if (isDark) {
        if (dropdownSlider) dropdownSlider.style.transform = 'translateX(1.25rem)';
        if (dropdownLabel) dropdownLabel.textContent = 'Light Mode';
        if (mobileSlider) mobileSlider.style.transform = 'translateX(1.25rem)';
        if (mobileLabel) mobileLabel.textContent = 'Light Mode';
      } else {
        if (dropdownSlider) dropdownSlider.style.transform = 'translateX(0)';
        if (dropdownLabel) dropdownLabel.textContent = 'Dark Mode';
        if (mobileSlider) mobileSlider.style.transform = 'translateX(0)';
        if (mobileLabel) mobileLabel.textContent = 'Dark Mode';
      }
    }
    function isDarkMode() {
      return document.documentElement.classList.contains('dark');
    }
    function updateLogoForMode() {
      var isDark = isDarkMode();
      var logoImg = document.getElementById('logo-img');
      var footerLogoImg = document.getElementById('footer-logo-img');
      if (logoImg) {
        logoImg.src = isDark ? '/public/assets/img/logo_dark.png' : '/public/assets/img/logo_light.png';
      }
      if (footerLogoImg) {
        footerLogoImg.src = isDark ? '/public/assets/img/logo_dark.png' : '/public/assets/img/logo_light.png';
      }
    }
    function toggleDarkMode() {
      document.documentElement.classList.toggle('dark');
      setDarkModeUI(isDarkMode());
      updateLogoForMode();
    }
    var dropdownToggle = document.getElementById('dropdown-toggle-dark');
    if (dropdownToggle) dropdownToggle.onclick = toggleDarkMode;
    var mobileToggle = document.getElementById('mobile-toggle-dark');
    if (mobileToggle) mobileToggle.onclick = toggleDarkMode;
    setDarkModeUI(isDarkMode());
    updateLogoForMode();

    // Profile dropdown logic
    var profileBtn = document.getElementById('profile-btn');
    var profileDropdown = document.getElementById('profile-dropdown');
    var dropdownOpen = false;
    if (profileBtn && profileDropdown) {
      profileBtn.onclick = function(e) {
        e.stopPropagation();
        dropdownOpen = !dropdownOpen;
        profileDropdown.classList.toggle('hidden', !dropdownOpen);
      };
      document.addEventListener('click', function(e) {
        if (dropdownOpen && !profileDropdown.contains(e.target) && e.target !== profileBtn) {
          profileDropdown.classList.add('hidden');
          dropdownOpen = false;
        }
      });
    }

    // Mobile menu logic
    var mobileMenuBtn = document.getElementById('mobile-menu-btn');
    var mobileMenu = document.getElementById('mobile-menu');
    var mobileMenuOpen = false;
    if (mobileMenuBtn && mobileMenu) {
      mobileMenuBtn.onclick = function(e) {
        e.stopPropagation();
        mobileMenuOpen = !mobileMenuOpen;
        mobileMenu.classList.toggle('hidden', !mobileMenuOpen);
      };
      document.addEventListener('click', function(e) {
        if (mobileMenuOpen && !mobileMenu.contains(e.target) && e.target !== mobileMenuBtn) {
          mobileMenu.classList.add('hidden');
          mobileMenuOpen = false;
        }
      });
    }
  </script>
</header>