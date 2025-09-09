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
  <!-- <p><b>Attached Image:</b> <?php echo $details['image_path'] ?: "N/A"; ?></p> -->
  <?php if (!empty($details['image_path'])): ?>
  <?php
    $rawPath = (string) $details['image_path'];
    $fileName = basename($rawPath);

    // Build correct URL (works in browser)
    $imageSrc = rtrim(PUBLIC_URL, '/') . '/uploads/' . $fileName;

    // Build absolute path on server for file_exists()
    $filePath = $_SERVER['DOCUMENT_ROOT'] . $imageSrc;
  ?>
  <?php if (file_exists($filePath)): ?>
    <div class="mt-2">
      <b>Attached Image:</b><br>
      <img src="<?php echo htmlspecialchars($imageSrc, ENT_QUOTES); ?>" 
           alt="Request Image" 
           class="mt-1 rounded-md border border-gray-200 max-h-48">
    </div>
  <?php else: ?>
    <p class="text-red-500 mt-2"><b>Image not found:</b> <?php echo htmlspecialchars($fileName); ?></p>
    <p class="text-gray-500 text-xs">Checked at: <?php echo htmlspecialchars($filePath); ?></p>
  <?php endif; ?>
<?php endif; ?>


</div>
