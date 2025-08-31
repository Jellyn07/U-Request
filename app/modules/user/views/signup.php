<?php
session_start();

$db_error = $_SESSION['db_error'] ?? '';
unset($_SESSION['db_error']);

$signup_error = $_SESSION['signup_error'] ?? '';
unset($_SESSION['signup_error']);

$signup_success = $_SESSION['signup_success'] ?? '';
unset($_SESSION['signup_success']);

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/UserModel.php';
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
<body class="bg-primary text-text min-h-screen flex items-center justify-center dark:bg-gray-950">
  <div class="w-3/4 mt-20 bg-white rounded-lg dark:bg-gray-900 shadow-lg rounded-2xl p-8 space-y-6 border border-gray-200 dark:border-gray-800 flex flex-col justify-center">
    
      <!-- Logo + Title -->
      <div class="text-center">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png" alt="U-Request Logo" class="mx-auto h-20 w-20">
        <p class="text-text font-bold pl-2">CREATE ACCOUNT</p>
      </div>

      <form method="post" action="../controllers/SignupController.php" id="signupForm">
        <!-- Student/Staff ID -->
        <div class="form-group">
          <label for="studstaID" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Student/Staff ID:<span class="text-accent">*</span></label>
          <input type="text" name="ssid" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" required placeholder="2000-012345">
        </div>

        <!-- Email -->
        <div class="form-group">        
          <label for="email" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Email:<span class="text-accent">*</span></label>
          <input type="text" id="email" name="email" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" required placeholder="your@usep.edu.ph">
        </div>

        <!-- First + Last Name side by side -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="fname" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">First Name:<span class="text-accent">*</span></label>
            <input type="text" id="fname" name="fn" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" required>
          </div>
          <div>
            <label for="lname" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Last Name:<span class="text-accent">*</span></label>
            <input type="text" id="lname" name="ln" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" required>
          </div>
        </div>

        <!-- Password -->
        <div class="form-group">        
          <label for="password" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Password:<span class="text-accent">*</span></label>
          <input type="password" id="password" name="pass" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" required>
        </div>

        <!-- Re-enter Password -->
        <div class="form-group">     
          <label for="repassword" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Re-enter Password:<span class="text-accent">*</span></label>                
          <input type="password" id="repassword" name="rpass" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" required>
        </div>  

        <!-- Data Privacy -->
        <div class="flex items-start space-x-2">
          <input type="checkbox" id="dp" name="dp" class="mt-1" required>
          <a href="https://www.usep.edu.ph/usep-data-privacy-statement/" class="text-sm leading-5">By continuing to use the U-Request, you agree to the University of Southeastern Philippines' Data Privacy Statement.</a>     
        </div>

        <!-- Sign Up Button -->
        <button type="submit" class="mt-3 w-full py-2 px-4 rounded-lg font-medium text-white bg-primary hover:bg-secondary transition shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
          Sign Up
        </button>

        <!-- Already have account -->
        <p class="mt-2 text-center text-text text-sm">Do you have an account? <a href="login.php" class="text-primary hover:underline">Login</a></p>
      </form>
    </div>
  </body>
</html>

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