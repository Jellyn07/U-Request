<?php
require_once __DIR__ . '/../../app/config/constants.php';
require_once __DIR__ . '/../controllers/ProfileController.php';
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../models/ProfileModel.php';

$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$controller = new ProfileController();
$requester_email = $_SESSION['email'] ?? null;
$profile = $requester_email ? $controller->getProfile($requester_email) : null;
?>
<header class="sticky top-0 z-50 bg-red-30 text-text p-1 md:p-3 md:bg-gray-200">
  <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-end md:justify-between w-auto md:w-full">
      <!-- Logo Left -->
      <div class="hidden md:flex items-center flex-shrink-0">
        <img id="logo-img" src="/public/assets/img/logo_light.png" alt="Logo" class="h-10 w-10">
        <p class="text-text font-bold pl-2">U<span class="text-accent">-</span>REQUEST</p>
      </div>

      <!-- Desktop Nav Right -->
      <nav class="hidden md:flex flex-1 justify-center mr-10">
        <ul class="flex items-center gap-6 text-sm">
          <li>
            <a class="text-sm font-medium transition hover:text-accent <?php echo $current_page === 'request.php' ? 'active-underline' : ''; ?>" href="request.php">REQUEST</a>
          </li>
          <li>
            <a class="text-sm font-medium transition hover:text-accent <?php echo $current_page === 'tracking.php' ? 'active-underline' : ''; ?>" href="tracking.php">TRACKING</a>
          </li>
        </ul>
      </nav>

      <!-- User Menu Right -->
      <div class="flex items-center gap-4">
        <div class="relative hidden md:block">
          <button id="profile-btn" type="button" class="flex items-center">
            <div class="flex items-center">

              <?php
              // Default profile picture path
              $defaultPic = '/public/assets/img/user-default.png';

              // Check if the profile picture exists and is a valid file
              $profilePicPath = !empty($profile['profile_pic']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/uploads/profile_pics/' . $profile['profile_pic'])
                  ? '/public/uploads/profile_pics/' . $profile['profile_pic']  // Correct relative path
                  : $defaultPic;
              ?>

              <img 
                  id="profile-preview"
                  src="<?php echo htmlspecialchars($profilePicPath); ?>"
                  alt="<?php echo htmlspecialchars($profile['cust_name'] ?? 'User Profile'); ?>"
                  class="w-9 h-9 rounded-full object-cover border border-primary shadow-sm mr-2" 
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
              <a href="profile.php" class="block rounded-lg px-4 py-2 text-sm text-text hover:bg-red-100 transition" role="menuitem">My Profile</a>
              <form method="POST" onclick="window.location.href='/app/controllers/LogoutController.php';">
                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-4 py-2 text-sm text-accent hover:bg-red-100 transition" role="menuitem">
                  <img src="/public/assets/img/logout.png" alt="User" class="size-4 object-cover overflow-hidden" />
                  Logout
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn"
                class="flex md:hidden rounded-md p-2 text-white bg-primary hover:bg-gray-300 ml-auto transition duration-200">
          <svg class="w-6 h-6" fill="none"
              viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <!-- Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-40 opacity-0 hidden z-40 transition-opacity duration-300 ease-in-out"></div>

        <!-- Mobile Menu -->
        <nav id="mobile-menu"
            class="fixed top-0 right-0 w-2/3 max-w-xs rounded-l-xl h-full bg-white shadow-lg z-50 transform translate-x-full transition-transform duration-300 ease-in-out md:hidden flex flex-col">
          <div class="flex items-center p-4 border-b border-gray-200">
            <div class="flex items-center flex-shrink-0">
              <img id="logo-img" src="/public/assets/img/logo_light.png" alt="Logo" class="h-10 w-10">
              <p class="text-text font-bold pl-2">U<span class="text-accent">-</span>REQUEST</p>
            </div>
          </div>

          <ul class="flex flex-col p-3 space-y-2 text-sm text-text font-medium">
            <li>
              <a href="request.php"
                class="block py-2 px-3 rounded-lg hover:bg-red-100  transition duration-200 <?php echo $current_page === 'request.php' ? 'bg-primary text-white' : 'text-text'; ?>">
                Request
              </a>
            </li>
            <li>
              <a href="tracking.php"
                class="block py-2 px-3 rounded-lg hover:bg-red-100 transition duration-200 <?php echo $current_page === 'tracking.php' ? 'bg-primary text-white' : 'text-text'; ?>">
                Tracking
              </a>
            </li>
            <li>
              <a href="profile.php"
                class="block py-2 px-3 rounded-lg hover:bg-red-100 transition duration-200 <?php echo $current_page === 'profile.php' ? 'bg-primary text-white' : 'text-text'; ?>">
                My Profile
              </a>
            </li>
            <li>
              <form method="POST" onclick="window.location.href='/app/controllers/LogoutController.php';">
                <button type="submit"
                        class="flex w-full items-center gap-2 py-2 px-3 rounded-lg hover:bg-red-100 text-red-600 transition duration-200">
                  <img src="/public/assets/img/logout.png" alt="Logout" class="w-5 h-5">
                  Logout
                </button>
              </form>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
  <script src="/public/assets/js/user/header.js"></script>
</header>
