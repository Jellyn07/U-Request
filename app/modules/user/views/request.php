<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
// Prevent browser from caching protected pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
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
  </head>
  <body class="flex flex-col min-h-screen bg-background text-text">
    <?php include COMPONENTS_PATH . '/header.php'; ?>
    <main class="flex-1">
      <div class="text-center mt-8 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Welcome to U-Request</h1>
        <p class="text-sm text-gray-600 mt-1">How can we help you?</p>
      </div>
      <!-- Request Options -->
      <div class="">
        <article class="w-1/2 m-5 mx-auto rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:shadow-lg transition sm:p-6">
            <img id="logo-img" src="/public/assets/img/mechanic1.gif" alt="Repair Logo" class="h-20 w-20">
            <h3 class="mt-0.5 text-lg font-medium text-gray-900">
              Repair Request
            </h3>
            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
              Spotted something broken around campus? Let us know through a repair request so the GSU team can fix it right away.
            </p>
            <div class="flex justify-end">
              <button type="button" class="btn btn-primary" onclick="location.href='gsu_form.php'">Request Now</button>
            </div>
          
        </article>
        <article class="w-1/2 m-5 mx-auto rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:shadow-lg transition sm:p-6">
            <img id="logo-img" src="/public/assets/img/minicar1.gif" alt="Repair Logo" class="h-20 w-20">
            <h3 class="mt-0.5 text-lg font-medium text-gray-900">
              Vehicle Request
            </h3>
            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
              Need a ride for a school activity, event, or errand? Submit a vehicle request and the Motorpool team will help you get moving.
            </p>
            <div class="flex justify-end">
              <button type="button" class="btn btn-primary" onclick="location.href='motorpool_form.php'">Request Now</button>
            </div>
          
        </article>
      </div>
    </main>
    <?php include COMPONENTS_PATH . '/footer.php'; ?>
  </body>
</html>