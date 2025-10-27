<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../config/auth.php';
require_once __DIR__ . '/../../../controllers/TrackingController.php';
require_once __DIR__ . '/../../../models/FeedbackModel.php';
$feedbackModel = new FeedbackModel();
$trackingController = new TrackingController();

$type = $_GET['type'] ?? 'repair';
$status = $_GET['status'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$list = $trackingController->getFilteredTracking($_SESSION['email'], $type, $status, $sort);
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
    <script src="/public/assets/js/user/tracking-filter.js"></script>
  </head>
  <body class="flex flex-col min-h-screen bg-gray-200 text-text">
    <?php include COMPONENTS_PATH . '/header.php'; ?>
    <main class="flex-1 px-4 sm:px-8 lg:px-20">
      <!-- Page Heading -->
      <div class="text-center mt-8 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Keep Track of Your Requests</h1>
        <p class="text-sm text-gray-600 mt-1">
          Monitor the status and details of your submitted requests.
        </p>
      </div>

      <!-- ================= Filter & Sort Controls ================= -->
    <div class="flex justify-center gap-4 mb-6">
      <form method="GET" class="flex flex-wrap items-center gap-3" id="filterForm">
        <input type="hidden" name="type" id="typeInput" value="<?= htmlspecialchars($type) ?>">
        <ul class="flex items-center gap-6 text-sm">
          <li>
            <button type="button" data-type="repair"
              class="text-sm font-medium <?= ($_GET['type'] ?? 'repair') === 'repair' ? 'text-accent' : 'hover:text-accent' ?>">
              Repair Request
            </button>
          </li>
          <li>
            <button type="button" data-type="vehicle"
              class="text-sm font-medium <?= ($_GET['type'] ?? '') === 'vehicle' ? 'text-accent' : 'hover:text-accent' ?>">
              Vehicle Request
            </button>
          </li>
        </ul>

        <?php 
          $type = $_GET['type'] ?? 'repair'; 
          $statusOptions = ($type === 'vehicle') 
            ? ['Pending', 'Approved', 'Disapproved', 'On Going' ,'Completed']
            : ['To Inspect', 'In Progress', 'Completed'];
        ?>

        <select name="status" id="statusSelect" class="input-field">
          <option value="" <?= empty($_GET['status']) ? 'selected' : '' ?>>All Status</option>
          <?php foreach ($statusOptions as $opt): ?>
            <option value="<?= $opt ?>" <?= ($_GET['status'] ?? '') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
          <?php endforeach; ?>
        </select>

        <select name="sort" class="input-field">
          <option value="oldest" <?= ($_GET['sort'] ?? '') === 'oldest' ? 'selected' : '' ?>>Oldest</option>
          <option value="newest" <?= ($_GET['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Newest</option>
        </select>
      </form>
    </div>


    <div class="mt-10">
      <?php if (!empty($list)) { ?>
        <?php foreach ($list as $track): ?>
          <article class="w-3/4 md:w-1/2 m-5 mx-auto rounded-lg border border-gray-200 bg-white p-4 shadow-sm hover:shadow-lg transition sm:p-6">
            <?php if ($type === 'repair'): ?>
              <div class="flex justify-start mb-3">
                <img src="<?php echo PUBLIC_URL; ?>/assets/img/mechanic1.gif" alt="Repair Logo" class="h-16 w-16">
              </div>
              <h3 class="text-lg font-semibold text-gray-800">
                Tracking No. <?php echo htmlspecialchars($track['tracking_id']); ?>
              </h3>
              <p class="mt-2 text-xs text-gray-700">
                <span class="font-medium">Description:</span>
                <?php echo htmlspecialchars($track['request_desc']); ?>
              </p>
              <p class="mt-2 text-sm">
                <span class="font-medium">Status:</span>
                <?php
                  $req_status = htmlspecialchars($track['req_status']);
                  $statusClass = match($req_status) {
                    "To Inspect" => "bg-yellow-100 text-black-100",
                    "In Progress" => "bg-green-100 text-black-100",
                    "Completed"  => "bg-red-100 text-black-100",
                    default => "bg-gray-100 text-gray-700",
                  };
                ?>
                <span class="px-2 py-1 rounded-full <?= $statusClass ?>">
                  <?= $req_status ?>
                </span>
              </p>
            <?php else: ?>
              <div class="flex justify-start mb-3">
                <img src="<?php echo PUBLIC_URL; ?>/assets/img/minicar1.gif" alt="Vehicle Logo" class="h-16 w-16">
              </div>
              <h3 class="text-lg font-semibold text-gray-800">
                Tracking No. <?php echo htmlspecialchars($track['tracking_id']); ?>
              </h3>
              <p class="mt-2 text-xs text-gray-700">
                <span class="font-medium">Trip Purpose:</span> <?= htmlspecialchars($track['trip_purpose']); ?><br>
                <span class="font-medium">Destination:</span> <?= htmlspecialchars($track['travel_destination']); ?>
              </p>
              <p class="mt-2 text-sm">
                <span class="font-medium">Status:</span>
                <span class="inline-block rounded-full px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-700">
                  <?= htmlspecialchars($track['req_status'] ?? 'Pending'); ?>
                </span>
              </p>
            <?php endif; ?>

            <div class="mt-4 text-right">
              <!-- Hidden Form to carry tracking_id --> 
                <form action="feedback.php" method="GET" class="hidden" id="form_<?php echo $track['tracking_id']; ?>"> 
                  <input type="hidden" name="tracking_id" value="<?php echo htmlspecialchars($track['tracking_id']); ?>"> 
                </form> <div class="mt-4 text-right"> 
                  <?php if ($track['req_status'] === 'Completed') { ?> 
                  <?php if ($feedbackModel->hasFeedback($track['tracking_id'])) { ?> 
                    <button class="btn btn-secondary mr-3" disabled>Feedback Completed</button> <?php } else { ?> 
                    <button type="button" class="btn btn-secondary mr-3" onclick="document.getElementById('form_<?php echo $track['tracking_id']; ?>').submit();"> Give Feedback </button> 
                    <?php } ?> 
                  <?php } ?>
              <button class="btn btn-primary" onclick="openDetails('<?php echo $track['tracking_id']; ?>')">
                View Details
              </button>
            </div>
          </article>
        <?php endforeach; ?>
      <?php } else { ?>
        <p class="text-center text-accent mt-4 text-sm">
          No <?= ($type === 'vehicle') ? 'Vehicle' : 'Repair' ?> Request Found
        </p>
      <?php } ?>
    </div>
    </main>
    <?php include COMPONENTS_PATH . '/footer.php'; ?>
  </body>
</html>