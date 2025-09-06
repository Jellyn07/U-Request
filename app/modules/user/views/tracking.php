<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
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
      <h1 class="text-lg font-bold mb-1 mt-8 md:flex flex-1 justify-center">Keep Track of Your Request</h1>
      <p class="text-text mb-1 mt-1 md:flex flex-1 justify-center text-sm">
        Check the progress and details of your submitted request.
      </p>
      <div class="">
        <article class="mx-auto w-1/2 m-5 rounded-lg border border-gray-100 bg-white p-4 shadow-lg transition hover:shadow-lg sm:p-6">
        <img id="logo-img" src="/public/assets/img/mechanic1.gif" alt="Repair Logo" class="h-20 w-20">
            <h3 class="mt-0.5 text-lg font-medium text-gray-900">
              Tracking no. 0001
            </h3>
            <p>Status: </p>
            <a class="bg-secondary text-background p-2 rounded-md text-sm" >More Details</a>
        </article>
        <!-- <article class="mx-auto w-1/2 m-5 rounded-lg border border-gray-100 bg-white p-4 shadow-lg transition hover:shadow-red-500 sm:p-6">
        <img id="logo-img" src="/public/assets/img/minicar1.gif" alt="Repair Logo" class="h-20 w-20">
            <h3 class="mt-0.5 text-lg font-medium text-gray-900">
              Tracking no. 0001
            </h3>
            <p>Status: </p>
            <a class="bg-secondary text-background p-2 rounded-md text-sm" >More Details</a>
        </article> -->
      </div>
    </main>
    <?php include COMPONENTS_PATH . '/footer.php'; ?>
  </body>
</html>