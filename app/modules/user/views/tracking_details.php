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
  <?php if (!empty($details['nature_request'])): ?>
  <!-- Repair Request -->
    <div class="flex gap-2 mb-2">
    <label class="text-xs text-text mb-1">
      Tracking No.:
    </label>
    <label class="text-xs text-text mb-1">
      Nature of Request:
    </label>
    </div>
    <div class="flex gap-2 mb-2">
      <p class="view-field w-1/2"><?php echo htmlspecialchars($details['tracking_id']); ?></p>
      <p class="view-field w-1/2"><?php echo htmlspecialchars($details['nature_request']); ?></p>
    </div>
    <div class="mb-2">
      <label class="text-xs text-text mb-1">
        Description:
      </label>
      <p class="view-field w-full"><?php echo htmlspecialchars($details['request_desc']); ?></p>
    </div>
    <div class="mb-2">
      <label class="text-xs text-text mb-1">
        Location:
      </label>
      <p class="view-field w-full"><?php echo htmlspecialchars($details['location']); ?></p>
    </div>
    <div class="flex gap-2 mb-2">
      <label class="text-xs text-text mb-1">
        Status:
      </label>
      <label class="text-xs text-text mb-1">
        Date Finished:
      </label>
    </div>
    <div class="flex gap-2 mb-2">
      <p class="view-field w-1/2"><?php echo htmlspecialchars($details['req_status']); ?></p>
      <p class="view-field w-1/2"><?php echo $details['date_finished'] ?: "N/A"; ?></p>
    </div>

    <?php if (!empty($details['image_path'])): ?>
      <?php
        $rawPath = (string) $details['image_path'];
        $fileName = basename($rawPath);
        $imageSrc = rtrim(PUBLIC_URL, '/') . '/uploads/' . $fileName;
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

  <?php else: ?>
    <!-- Vehicle Request -->
    <!-- <p><b>Control No.:</b> <?php echo htmlspecialchars($details['control_no']); ?></p> -->
    <p><b>Travel Destination:</b> <?php echo htmlspecialchars($details['travel_destination']); ?></p>
    <p><b>Purpose:</b> <?php echo htmlspecialchars($details['trip_purpose']); ?></p>
    <p><b>Date Requested:</b>
      <?php 
        if (!empty($details['date_request'])) {
            $date = date("F j, Y", strtotime($details['date_request']));
            $time = date("g:i A", strtotime($details['date_request']));
            echo htmlspecialchars($date) . 
                " <span style='font-size: 0.9em; color: gray;'>(" . htmlspecialchars($time) . ")</span>";
        } else {
            echo 'N/A';
        }
      ?>
    </p>
    <p><b>Travel Date:</b>
      <?php 
        echo !empty($details['travel_date']) 
          ? htmlspecialchars(date("F j, Y", strtotime($details['travel_date']))) 
          : 'N/A'; 
      ?>
    </p>
    <p><b>Return Date:</b>
      <?php 
        echo !empty($details['return_date']) 
          ? htmlspecialchars(date("F j, Y", strtotime($details['return_date']))) 
          : 'N/A'; 
      ?>
    </p>
    <p><b>Departure Time:</b>
      <?php 
        if (!empty($details['departure_time'])) {
            $time = date("g:i A", strtotime($details['departure_time']));
            echo "<span style='color: gray; font-size: 0.9em;'>" . htmlspecialchars($time) . "</span>";
        } else {
            echo 'N/A';
        }
      ?>
    </p>
    <p><b>Return Time:</b>
      <?php 
        if (!empty($details['return_time'])) {
            $time = date("g:i A", strtotime($details['return_time']));
            echo "<span style='color: gray; font-size: 0.9em;'>" . htmlspecialchars($time) . "</span>";
        } else {
            echo 'N/A';
        }
      ?>
    </p>
    <?php if (!empty($details['passengers'])): ?>
      <div class="mt-2">
        <b>Passengers:</b>
        <ul class="list-disc ml-6">
          <?php foreach ($details['passengers'] as $p): ?>
            <li><?php echo htmlspecialchars($p['first_name'] . " " . $p['last_name']); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>



</div>
