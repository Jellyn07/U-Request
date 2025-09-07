<?php
require_once __DIR__ . '/../../../config/constants.php';
require_once __DIR__ . '/../../../controllers/TrackingController.php';

if (!isset($_GET['id'])) {
    echo "<p class='text-red-500 text-center'>Invalid request.</p>";
    exit;
}

$trackingId = $_GET['id'];
$trackingController = new TrackingController();
$details = $trackingController->getTrackingDetails($trackingId);

if (!$details) {
    echo "<p class='text-gray-500 text-center'>No details found.</p>";
    exit;
}
?>

<div class="text-left space-y-2">
  <p><b>Tracking No.:</b> <?php echo htmlspecialchars($details['tracking_id']); ?></p>
  <p><b>Nature:</b> <?php echo htmlspecialchars($details['nature_request']); ?></p>
  <p><b>Description:</b> <?php echo htmlspecialchars($details['request_desc']); ?></p>
  <p><b>Location:</b> <?php echo htmlspecialchars($details['location']); ?></p>
  <p><b>Status:</b> <?php echo htmlspecialchars($details['req_status']); ?></p>
  <p><b>Date Finished:</b> <?php echo $details['date_finished'] ?: "N/A"; ?></p>

  <?php if (!empty($details['image_path'])): ?>
  <div class="mt-2">
    <b>Attached Image:</b><br>
    <img src="<?php echo PUBLIC_URL . $details['image_path']; ?>" 
         alt="Request Image" 
         class="mt-1 rounded-md border border-gray-200 max-h-48">
  </div>
<?php endif; ?>



</div>
