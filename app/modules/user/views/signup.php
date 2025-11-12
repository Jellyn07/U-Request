<?php
session_start();

// Retrieve messages
$db_error = $_SESSION['db_error'] ?? '';
$signup_error = $_SESSION['signup_error'] ?? '';
$signup_success = $_SESSION['signup_success'] ?? '';

// ✅ Retrieve previously entered form data (if validation failed)
$form_data = $_SESSION['form_data'] ?? [];

// Do NOT clear here — clear after showing alerts
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
          U<span class="text-accent">-</span>REQUEST
        </p>
      </div>

      <form method="post" action="../../../controllers/SignupController.php" id="signupForm">
        <!-- Student/Staff ID -->
        <div>
          <label for="studstaID" class="text-sm text-text mb-1">
            Student/Staff ID:
            <span class="text-accent">*</span>
          </label>
          <input type="text" name="ssid" class="w-full input-field" required placeholder="2000-012345"
                 value="<?= htmlspecialchars($form_data['ssid'] ?? '') ?>">
        </div>

        <!-- Email -->
        <div class="form-group">        
          <label for="email" class="text-sm text-text mb-1">
            USeP Email:
            <span class="text-accent">*</span>
          </label>
          <input type="text" id="email" name="email" class="w-full input-field" required placeholder="your@usep.edu.ph"
                 value="<?= htmlspecialchars($form_data['email'] ?? '') ?>">
        </div>

        <!-- First + Last Name side by side -->
        <div class="grid grid-cols-2 gap-2">
          <div>
            <label for="fname" class="text-sm text-text mb-1">
              First Name:
              <span class="text-accent">*</span>
            </label>
            <input type="text" id="fname" name="fn" class="w-full input-field" required
                   value="<?= htmlspecialchars($form_data['fn'] ?? '') ?>">
          </div>
          <div>
            <label for="lname" class="text-sm text-text mb-1">
              Last Name:
              <span class="text-accent">*</span>
            </label>
            <input type="text" id="lname" name="ln" class="w-full input-field" required
                   value="<?= htmlspecialchars($form_data['ln'] ?? '') ?>">
          </div>
        </div>

        <!-- Password -->
        <div class="form-group relative">        
          <label for="password" class="text-sm text-text mb-1">
            Password:
            <span class="text-accent">*</span>
          </label>
          <input type="password" id="password" name="pass" class="w-full input-field" required
                 value="<?= htmlspecialchars($form_data['pass'] ?? '') ?>"> 
          <span class="absolute right-3 cursor-pointer" data-password-toggle="password">
            <img src="/public/assets/img/view.png" class="size-4 eye-open my-2.5 transition-opacity duration-200">
            <img src="/public/assets/img/hide.png" class="size-4 eye-closed hidden my-2.5 transition-opacity duration-200">
          </span>
        </div>

        <!-- Re-enter Password -->
        <div class="form-group relative">     
          <label for="repassword" class="text-sm text-text mb-1">
            Re-enter Password:
            <span class="text-accent">*</span>
          </label>                
          <input type="password" id="repassword" name="rpass" class="w-full input-field" required
                 value="<?= htmlspecialchars($form_data['rpass'] ?? '') ?>">
          <span class="absolute right-3 cursor-pointer" data-password-toggle="repassword">
            <img src="/public/assets/img/view.png" class="size-4 eye-open my-2.5 transition-opacity duration-200">
            <img src="/public/assets/img/hide.png" class="size-4 eye-closed hidden my-2.5 transition-opacity duration-200">
          </span>          
        </div>  

        <!-- Data Privacy -->
        <div class="flex items-start space-x-2">
          <input type="checkbox" id="dp" name="dp" class="mt-1.5" required>
          <a href="https://www.usep.edu.ph/usep-data-privacy-statement/" class="text-xs mt-1">
            By using U-Request, you agree to the <span class="text-primary hover:underline"> USeP Data Privacy Statement. </span>
          </a>     
        </div>

        <!-- Sign Up Button -->
        <button type="submit" class="btn btn-primary w-full mt-3" name="signup">
          Sign Up
        </button>

        <!-- Already have account -->
        <p class="mt-2 text-center text-text text-sm">Do you have an account? <a href="login.php" class="text-primary hover:underline">Login</a></p>
      </form>
    </div>
  </div>

  <!-- Diagonal Background Layer -->
  <div class="absolute inset-0 clip-diagonal4 z-0"></div>
  <div class="absolute inset-0 clip-diagonal5"></div>
  <div class="absolute inset-0 clip-diagonal6"></div>

  </body>
</html>

<script src="/public/assets/js/shared/password-visibility.js"></script>
<script>
  let signupError = <?= json_encode($signup_error) ?>;
  let signupSuccess = <?= json_encode($signup_success) ?>;

  if (signupError) {
      showErrorA(signupError);
  }
  if (signupSuccess) {
      showSuccessAlert(signupSuccess);
  }
</script>

<?php
// ✅ Clear messages and form data AFTER they’re displayed
unset($_SESSION['signup_error']);
unset($_SESSION['signup_success']);
unset($_SESSION['db_error']);
unset($_SESSION['form_data']);
?>
