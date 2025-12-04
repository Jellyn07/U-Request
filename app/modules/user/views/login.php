<?php

session_start();

$login_error = '';
if (isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

$signup_success = $_SESSION['signup_success'] ?? '';
unset($_SESSION['signup_success']);

$old_email = $_SESSION['old_email'] ?? '';
unset($_SESSION['old_email']);

$old_password = $_SESSION['old_password'] ?? '';
unset($_SESSION['old_password']);

$is_locked = isset($_SESSION['lock_time']) && time() < $_SESSION['lock_time'];
$remaining = $is_locked ? $_SESSION['lock_time'] - time() : 0;

require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../models/UserModel.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>U-Request</title>
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
    <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/public/assets/js/alert.js"></script>
    <link rel="stylesheet" href="/public/assets/css/remove.css">

  </head>
<body class="min-h-screen flex relative overflow-hidden">
  <!-- White Section -->
  <div class="w-full md:w-1/2 flex items-center justify-center relative z-10">
    <div id="loginForm" class="w-2/3 md:w-1/2 max-w-md min-w-[350px] bg-white p-5 rounded-2xl transition-all duration-1000 opacity-0 translate-y-5 border border-gray-200 shadow-lg md:border-none md:shadow-none">
      <!-- Logo + Title -->
      <div class="text-center">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/logo_light.png" alt="U-Request Logo" class="mx-auto h-14 w-14 mt-4 md:mt-0 md:h-20 md:w-20">
        <p class="md:text-2xl text-lg mb-3 font-bold">
          U<span class="text-accent">-</span>REQUEST
        </p>
      </div>

      <!-- Form -->
      <form method="post" action="../../../controllers/LoginController.php">
        <!-- Email -->
        <div>
          <label for="email" class="text-sm text-text mb-1">
            USeP Email Address
          </label>
          <input type="email" id="email" name="email" class="mb-1 w-full input-field" placeholder="your@usep.edu.ph" value="<?= htmlspecialchars($old_email) ?>"
            <?= $is_locked ? 'disabled' : '' ?> required>
        </div>

        <!-- Password -->
        <div class="relative">
          <label for="password" class="text-sm text-text mb-1">
            Password
          </label>
          <input type="password" id="password" name="password" class="w-full input-field" placeholder="atleast 8 characters"  value="<?= htmlspecialchars($old_password) ?>"
             <?= $is_locked ? 'disabled' : '' ?>  required>
          <span class="absolute right-3 cursor-pointer" data-password-toggle="password">
            <img src="/public/assets/img/view.png" class="size-4 eye-open my-2.5 transition-opacity duration-200">
            <img src="/public/assets/img/hide.png" class="size-4 eye-closed hidden my-2.5 transition-opacity duration-200">
          </span>
        </div>

        <p class="text-right">
          <a href="../../shared/views/forgot_pass.php" class="w-full text-right text-sm text-primary hover:underline">
            Forgot Password?
          </a>
        </p>

        <!-- Login Button -->
        <button type="submit" name="signin" class="mt-3 w-full btn btn-primary"   <?= $is_locked ? 'disabled' : '' ?> >
          Login
        </button>

        <p class="mt-1 text-center text-text text-sm">
          Don't have an account? 
          <a href="signup.php" class="text-primary hover:underline">Sign Up</a>
        </p>

           <?php if ($is_locked): ?>
        <p class="text-red-600 text-sm mt-3">
            Too many failed attempts. Please wait 
            <span id="countdown"><?= $remaining ?></span> seconds before trying again.
        </p>
    <?php elseif (!empty($_SESSION['login_error'])): ?>
        <p class="text-red-600 text-sm mt-3"><?= htmlspecialchars($_SESSION['login_error']) ?></p>
    <?php endif; ?>
    
      </form>
    </div>
  </div>

  <!-- Red Section -->
  <div class="md:w-1/2 items-end justify-end relative z-10 text-white hidden md:flex flex-col">
    <div class="text-right p-8">
      <p class="text-sm text-text/70">
          <span class="block sm:inline">&copy; 
            All rights reserved.
          </span>
          <a class="inline-block text-text underline transition" href="#">
            Terms & Conditions
          </a>
          <span class="text-text">&middot;</span>
          <a class="inline-block text-text underline transition" href="#">
            Privacy Policy
          </a>
        </p>
    </div>
  </div>

  <!-- Diagonal Background Layer -->
  <div class="absolute inset-0 clip-diagonal z-0"></div>
  <div class="absolute inset-0 clip-diagonal1"></div>
  <div class="absolute inset-0 clip-diagonal2"></div>
</body>
<script>
  const ADMIN_LOGIN = "/app/modules/shared/views/admin_login.php";
  const USER_LOGIN  = "/app/modules/user/views/login.php";
</script>
<script src="<?php echo PUBLIC_URL; ?>/assets/js/admin-user.js"></script> 
<script src="/public/assets/js/shared/password-visibility.js"></script>
<script src="/public/assets/js/shared/login-form-transition.js"></script>
</html>
<script>
  // Pass PHP variable to JS
  let loginError = <?= json_encode($login_error) ?>;
  if (loginError) {
      showErrorAlert(loginError);
  }
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const countdown = document.getElementById("countdown");
  if (countdown) {
    let timeLeft = parseInt(countdown.textContent);
    const timer = setInterval(() => {
      timeLeft--;
      countdown.textContent = timeLeft;
      if (timeLeft <= 0) {
        clearInterval(timer);
        location.reload();
      }
    }, 1000);
  }
});
  window.addEventListener('load', () => {
    const pwd = document.getElementById('password');
    if (pwd && performance.navigation.type === 1) pwd.value = '';
  });
</script>