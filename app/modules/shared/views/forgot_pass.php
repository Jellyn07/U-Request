<?php

session_start();

$login_error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

$signup_success = $_SESSION['signup_success'] ?? '';
unset($_SESSION['signup_success']);

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
  </head>
<body class="bg-background min-h-screen flex relative overflow-hidden">
  <!-- White Section -->
  <div class="w-1/2 flex items-center justify-center relative z-10">
    <div class="w-1/2 max-w-md bg-background">
      <!-- Logo + Title -->
      <div class="text-center">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/logo_light.png" alt="U-Request Logo" class="mx-auto h-20 w-20">
        <p class="text-2xl font-bold">
          U<span class="text-accent">-</span>REQUEST
        </p>
      </div>

      <form method="post" action="" id="forgotForm">
      <!-- Step 1: Email -->
      <div id="step-email">
        <h1 class="text-center text-sm font-medium">
          Forgot Your Password?
        </h1>
        <div>
          <label for="email" class="text-sm text-text mb-1">
            USeP Email Address
          </label>
          <input type="email" id="email" name="email" class="mb-1 w-full input-field" placeholder="your@usep.edu.ph" required>
        </div>
        <button type="button" class="mt-3 w-full btn btn-primary" onclick="goToOtp()">
          Submit
        </button>
        <p class="w-full text-center mt-2">
          <a href="#" class="text-primary text-sm hover:underline">
            Return to Login
          </a>
        </p>
      </div>

      <!-- Step 2: OTP -->
      <div id="step-otp" class="hidden">
        <h1 class="text-center text-sm font-medium">
          Enter the OTP
        </h1>
        <div>
          <label for="otp" class="text-sm text-text mb-1">
            One-Time Password
          </label>
          <input type="text" id="otp" name="otp" placeholder="ex. 123456" required  class="mb-1 w-full input-field">
        </div>
        <button type="button" class="mt-3 w-full btn btn-primary" onclick="goToReset()">
          Verify OTP
        </button>
        <p class="w-full text-center mt-2">
          <a href="#" class="text-primary text-sm hover:underline">
            Return to Login
          </a>
        </p>
      </div>

      <!-- Step 3: Reset Password -->
      <div id="step-reset" class="hidden">
        <h1 class="text-center text-sm font-medium">
          Reset Your Password
        </h1>
        <div>
          <label for="new_password" class="text-sm text-text mb-1">
            New Password
          </label>
          <input type="password" id="new_password" name="new_password" class="mb-1 w-full input-field" required>
        </div>
        <div>
          <label for="confirm_password" class="text-sm text-text mb-1">
            Confirm Password
          </label>
          <input type="password" id="confirm_password" name="confirm_password" class="mb-1 w-full input-field" required>
        </div>
        <button type="submit" name="reset" class="mt-3 w-full btn btn-primary">
          Save New Password
        </button>
      </div>
    </form>

    </div>
  </div>

  <!-- Red Section -->
  <div class="w-1/2 flex items-end justify-end relative z-10 text-white">
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

  <script src="/public/assets/js/user/forgot_pass.js"></script>
</body>
</html>
<script>
  // Pass PHP variable to JS
  let loginError = <?= json_encode($login_error) ?>;
  if (loginError) {
      showErrorAlert(loginError);
  }
</script>