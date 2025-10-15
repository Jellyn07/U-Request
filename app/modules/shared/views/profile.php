<?php
// if (!isset($_SESSION['email'])) {
//     header("Location: admin_login.php");
//     exit;
// }
// require_once __DIR__ . '/../../../config/auth-admin.php';
require_once __DIR__ . '/../../../config/constants.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header("Location: modules/shared/views/admin_login.php");
    exit;
}

// ✅ Load controller & fetch profile before including the view
require_once __DIR__ . '/../../../controllers/AdminProfileController.php';
$controller = new AdminProfileController();
$profile = $controller->getProfile($_SESSION['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>U-Request</title>
  <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/output.css" />
  <link rel="icon" href="<?php echo PUBLIC_URL; ?>/assets/img/upper_logo.png"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-100">
  <?php 
    include COMPONENTS_PATH . '/admin_profile.php'; 
  ?>
  <!-- Action Buttons -->
  <div class="flex w-1/2 mx-auto flex-col sm:flex-row gap-3 mb-10">
    <button 
        type="button"
        onclick="window.history.back();"
        class="flex-1 btn btn-secondary ml-4">Back</button>
    <button 
      type="button"
      onclick="window.location.href='/app/controllers/LogoutController.php';"
      class="flex-1 btn btn-primary mr-4">
      Logout
    </button>
  </div>
</body>
</html>
