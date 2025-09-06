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
    <main class="flex-1 px-4 sm:px-8 lg:px-20">
      <!-- Page Heading -->
      <div class="text-center mt-8 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Keep Track of Your Requests</h1>
        <p class="text-sm text-gray-600 mt-1">
          Monitor the status and details of your submitted requests.
        </p>
      </div>

      <!-- Tracking List -->
      <div class="space-y-6">
        
        <!-- Repair Request Card -->
        <article class="rounded-xl border border-gray-200 bg-white p-5 mx-40 shadow-sm hover:shadow-md transition">
          <!-- GIF at the top -->
          <div class="flex justify-start mb-3">
            <img src="/public/assets/img/mechanic1.gif" alt="Repair Logo" class="h-16 w-16">
          </div>

          <!-- Content -->
          <div>
            <h3 class="text-lg font-semibold text-gray-800">Tracking No.0001</h3>
            <p class="mt-2 text-xs text-gray-700 line-clamp-2">
              <span class="font-medium">Issue:</span> Water is leaking from the faucet in the Faculty Restroom near Room 205. The water keeps dripping even when turned off, and it has caused a small puddle on the floor.
            </p>
            <p class="mt-2 text-sm">
              <span class="font-medium">Status:</span> 
              <span class="inline-block rounded-full bg-yellow-100 text-yellow-700 px-2 py-0.5 text-xs font-medium">Pending</span>
            </p>
          </div>

          <!-- Button -->
          <div class="mt-4 text-right">
            <a href="#" class="btn btn-primary" onclick="openDetails('repair', 1)">
              View Details
            </a>
          </div>
        </article>


        <!-- Vehicle Request Card -->
        <article class="rounded-xl border border-gray-200 bg-white p-5 mx-40 shadow-sm hover:shadow-md transition">
          <!-- GIF at the top -->
          <div class="flex justify-start mb-3">
            <img src="/public/assets/img/minicar1.gif" alt="Vehicle Logo" class="h-16 w-16">
          </div>

          <!-- Content -->
          <div>
            <h3 class="text-lg font-semibold text-gray-800">Tracking No.0002</h3>
            <p class="mt-2 text-xs text-gray-700 line-clamp-2">
              <span class="font-medium">Purpose:</span> Field Trip to Davao City for the College of Engineering students. The trip will include several company visits and an educational tour.
            </p>
            <p class="mt-2 text-sm">
              <span class="font-medium">Status:</span> 
              <span class="inline-block rounded-full bg-green-100 text-green-700 px-2 py-0.5 text-xs font-medium">Approved</span>
            </p>
          </div>

          <!-- Button -->
          <div class="mt-4 text-right">
            <a href="#" class="btn btn-primary" onclick="openDetails('vehicle', 2)">
              View Details
            </a>
          </div>
        </article>


        <!-- Another Repair Request -->
        <article class="rounded-xl border border-gray-200 bg-white p-5 mx-40 shadow-sm hover:shadow-md transition">
          <!-- GIF at the top -->
          <div class="flex justify-start mb-3">
            <img src="/public/assets/img/mechanic1.gif" alt="Repair Logo" class="h-16 w-16">
          </div>

          <!-- Content -->
          <div>
            <h3 class="text-lg font-semibold text-gray-800">Tracking No.0003</h3>
            <p class="mt-2 text-xs text-gray-700 line-clamp-2">
              <span class="font-medium">Issue:</span> Broken door hinge in the library study room. The door doesn’t close properly and may cause accidents if not fixed.
            </p>
            <p class="mt-2 text-sm">
              <span class="font-medium">Status:</span> 
              <span class="inline-block rounded-full bg-green-100 text-green-700 px-2 py-0.5 text-xs font-medium">Fixed</span>
            </p>
          </div>

          <!-- Button -->
          <div class="mt-4 text-right">
            <a href="#" class="btn btn-primary">
              View Details
            </a>
          </div>
        </article>


        <!-- Another Vehicle Request -->
        <article class="rounded-xl border border-gray-200 bg-white p-5 mx-40 shadow-sm hover:shadow-md transition">
          <!-- GIF at the top -->
          <div class="flex justify-start mb-3">
            <img src="/public/assets/img/minicar1.gif" alt="Vehicle Logo" class="h-16 w-16">
          </div>

          <!-- Content -->
          <div>
            <h3 class="text-lg font-semibold text-gray-800">Tracking No.0004</h3>
            <p class="mt-2 text-xs text-gray-700 line-clamp-2">
              <span class="font-medium">Purpose:</span> Transport service requested for faculty seminar in Tagum City.
            </p>
            <p class="mt-2 text-sm">
              <span class="font-medium">Status:</span> 
              <span class="inline-block rounded-full bg-red-100 text-red-700 px-2 py-0.5 text-xs font-medium">Disapproved</span>
            </p>
          </div>

          <!-- Button -->
          <div class="mt-4 text-right">
            <a href="#" class="btn btn-primary">
              View Details
            </a>
          </div>
        </article>


      </div>

      <!-- Overlay -->
      <div id="details-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-3/4 max-h-[90vh] overflow-y-auto rounded-lg shadow-lg relative p-6">
          <!-- Close button -->
          <button onclick="closeDetails()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-xl">&times;</button>
          
          <!-- Content will be injected dynamically -->
          <div id="details-content">
            <p class="text-center text-gray-500">Loading details...</p>
          </div>
        </div>
      </div>

    </main>
    <?php include COMPONENTS_PATH . '/footer.php'; ?>
  </body>
</html>