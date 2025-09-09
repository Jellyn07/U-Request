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

<p class="mt-2 text-xs text-gray-700 line-clamp-2">
  <span class="font-medium">Description:</span>
  <?php if (!empty($track['request_desc'])): ?>
    <?php echo htmlspecialchars($track['request_desc']); ?>
  <?php elseif (!empty($track['trip_purpose'])): ?>
    <?php echo htmlspecialchars($track['trip_purpose']); ?> 
    (Destination: <?php echo htmlspecialchars($track['travel_destination']); ?>)
  <?php else: ?>
    No description available.
  <?php endif; ?>
</p>
