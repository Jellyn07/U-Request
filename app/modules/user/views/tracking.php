<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/TrackingController.php';

$trackingController = new TrackingController();
$trackingList = $trackingController->listTracking($_SESSION['email']);
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
        <?php if (!empty($trackingList)) { ?>
        <?php foreach ($trackingList as $track): ?>
          <article class="w-1/2 m-5 mx-auto rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:shadow-lg transition sm:p-6">
            <!-- GIF at the top -->
            <div class="flex justify-start mb-3">
              <?php if (stripos($track['nature_request'], 'vehicle') !== false): ?>
                <img src="<?php echo PUBLIC_URL; ?>/assets/img/minicar1.gif" alt="Vehicle Logo" class="h-16 w-16">
              <?php else: ?>
                <img src="<?php echo PUBLIC_URL; ?>/assets/img/mechanic1.gif" alt="Repair Logo" class="h-16 w-16">
              <?php endif; ?>
            </div>

            <!-- Content -->
            <div>
              <h3 class="text-lg font-semibold text-gray-800">
                Tracking No. <?php echo htmlspecialchars($track['tracking_id']); ?>
              </h3>
              <p class="mt-2 text-xs text-gray-700 line-clamp-2">
                <span class="font-medium">Description:</span>
                <?php echo htmlspecialchars($track['request_desc']); ?>
              </p>
              <p class="mt-2 text-sm">
                <span class="font-medium">Status:</span>
                <?php
                  $status = strtolower($track['req_status']);
                  $statusClass = "bg-gray-100 text-gray-700";
                  if ($status === "pending") {
                      $statusClass = "bg-yellow-100 text-yellow-700";
                  } elseif ($status === "approved") {
                      $statusClass = "bg-green-100 text-green-700";
                  } elseif ($status === "fixed") {
                      $statusClass = "bg-green-100 text-green-700";
                  } elseif ($status === "disapproved") {
                      $statusClass = "bg-red-100 text-red-700";
                  }
                ?>
                <span class="inline-block rounded-full px-2 py-0.5 text-xs font-medium <?php echo $statusClass; ?>">
                  <?php echo htmlspecialchars($track['req_status']); ?>
                </span>
              </p>
            </div>

            <!-- Button -->
            <div class="mt-4 text-right">
            <button 
              class="btn btn-primary" 
              onclick="openDetails('<?php echo $track['tracking_id']; ?>')">
              View Details
            </button>
            </div>
          </article>
        <?php endforeach; ?>
        <?php } else { ?>
          <p class="text-center text-accent mt-20 text-sm">No tracking records found.</p>
        <?php } ?>
      </div>
    </main>
    <?php include COMPONENTS_PATH . '/footer.php'; ?>
  </body>
</html>
