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
require_once __DIR__ . '/../../../config/constants.php';
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
  </head>
<body class="min-h-screen flex relative overflow-hidden">
  <div class="w-1/2 flex items-end justify-left relative z-10 text-white">
    <div class="text-right p-8">
      <p class="text-sm text-white">
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
  <div class="w-1/2 flex items-center justify-center relative z-10">
    <div class="w-1/2 max-w-md bg-background">
      <!-- Logo + Title -->
      <div class="text-center">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/logo_light.png" alt="U-Request Logo" class="mx-auto h-20 w-20">
        <p class="text-2xl font-bold">
            ADMIN PANEL
        </p>
      </div>

      <!-- Form -->
      <form method="post" action="../../../controllers/AdminController.php">
        <!-- Email -->
        <div>
          <label for="email" class="text-sm text-text mb-1">
            USeP Email Address
          </label>
          <input type="email" id="email" name="email" class="mb-1 w-full input-field" placeholder="your@usep.edu.ph" required>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="text-sm text-text mb-1">
            Password
          </label>
          <input type="password" id="password" name="password" class="w-full input-field" placeholder="atleast 8 characters" required>
        </div>

        <p class="text-right">
          <a href="../../shared/views/forgot_pass.php" class="w-full text-right text-sm text-primary hover:underline">
            Forgot Password?
          </a>
        </p>

        <!-- Login Button -->
        <button type="submit" name="signin" class="mt-3 w-full btn btn-primary">
          Login
        </button>
    </div>
  </div>

  <!-- Diagonal Background Layer -->
  <div class="absolute inset-0 clip-diagonal4 z-0"></div>
  <div class="absolute inset-0 clip-diagonal5"></div>
  <div class="absolute inset-0 clip-diagonal6"></div>

  </body>
<script>
  const ADMIN_LOGIN = "/app/modules/shared/views/admin_login.php";
  const USER_LOGIN  = "/app/modules/user/views/login.php";
</script>
<script src="<?php echo PUBLIC_URL; ?>/assets/js/admin-user.js"></script>
</html>

<script>
  // Pass PHP variable to JS
  let loginError = <?= json_encode($login_error) ?>;
  if (loginError) {
      showErrorAlert(loginError);
  }
</script>

