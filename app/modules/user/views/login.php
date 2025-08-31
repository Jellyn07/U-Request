<?php

session_start();
$login_error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

// $signup_success = $_SESSION['signup_success'] ?? '';
// unset($_SESSION['signup_success']);

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
  <div class="w-96 mt-20 bg-white rounded-lg dark:bg-gray-900 shadow-lg rounded-2xl p-8 space-y-6 border border-gray-200 dark:border-gray-800 flex flex-col justify-center">
    
      <!-- Logo + Title -->
      <div class="text-center">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png" alt="U-Request Logo" class="mx-auto h-20 w-20">
        <!-- <p class="text-text font-bold pl-2">U<span class="text-accent">-</span>REQUEST</p> -->
        <p class="text-text font-bold pl-2">WELCOME BACK</p>
      </div>

      <!-- Form -->
      <form method="post" action="../controllers/LoginController.php">
        
        <!-- Email -->
        <div>
          <label for="email" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
          <input type="email" id="email" name="email" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="your@email.com" required>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="mt-2 block text-sm text-gray-700 dark:text-gray-300 mb-1">Password</label>
          <input type="password" id="password" name="password" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-background text-text focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="Enter your password" required>
        </div>

        <!-- Remember + Create -->
        <!-- <div class="flex items-center justify-between text-sm mt-2">
          <label class="flex items-center gap-2">
            <input type="checkbox" id="remember" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary">
            <span class="text-gray-700 dark:text-gray-300">Remember me</span>
          </label>
          <div class="flex justify-end mb-1">
            <a href="#" class="text-xs text-primary dark:text-secondary hover:underline">Forgot Password?</a>
          </div>
        </div> -->
        
        <!-- Login Button -->
        <button type="submit" name="signin" class="mt-3 w-full py-2 px-4 rounded-lg font-medium text-white bg-primary hover:bg-secondary transition shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
          Login
        </button>

        <p class="text-left"><a href="#" class="w-full text-right text-sm text-primary dark:text-secondary hover:underline">Forgot Password?</p></a>
        <br>  
        <p class="mt-1 text-center text-text text-sm">Don't have an account? <a href="signup.php" class="text-primary hover:underline-text">Sign Up</a></p>

      </form>
    </div>
    
  </body>
</html>


  <script>
        // Pass PHP variable to JS
        let loginError = <?= json_encode($login_error) ?>;
        if (loginError) {
            showErrorAlert(loginError);
        }

    </script>