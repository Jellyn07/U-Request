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
<div class="mt-4 text-sm text-gray-700">
  <p><b>Tracking No.:</b> <?php echo htmlspecialchars($details['tracking_id']); ?></p>
  <p><b>Nature:</b> <?php echo htmlspecialchars($details['nature_request']); ?></p>

  <?php if (stripos($details['nature_request'], 'vehicle') !== false): ?>
    <!-- Vehicle Request Details -->
    <?php if (!empty($details['trip_purpose'])): ?>
      <p><b>Trip Purpose:</b> <?php echo htmlspecialchars($details['trip_purpose']); ?></p>
      <p><b>Destination:</b> <?php echo htmlspecialchars($details['travel_destination']); ?></p>
      <p><b>Travel Date:</b> <?php echo htmlspecialchars($details['travel_date']); ?></p>
      <p><b>Return Date:</b> <?php echo htmlspecialchars($details['return_date']); ?></p>
      <p><b>Status:</b> <?php echo htmlspecialchars($details['req_status']); ?></p>
    <?php else: ?>
      <p class="text-red-500">No Vehicle Request</p>
    <?php endif; ?>
  <?php else: ?>
    <!-- Repair Request Details -->
    <p><b>Description:</b> <?php echo htmlspecialchars($details['request_desc']); ?></p>
    <p><b>Location:</b> <?php echo htmlspecialchars($details['location']); ?></p>
    <p><b>Status:</b> <?php echo htmlspecialchars($details['req_status']); ?></p>
    <p><b>Date Finished:</b> <?php echo !empty($details['date_finished']) ? htmlspecialchars($details['date_finished']) : "N/A"; ?></p>
  <?php endif; ?>
</div>

