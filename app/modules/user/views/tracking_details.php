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
    <div id="repair-form text-black">
        <!-- HEADER -->
        <div id="header" class="flex flex-col items-center justify-center mb-4">
          <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 mb-2 mt-4">
          <h2 class="text-center text-lg font-semibold">
            REPAIR REQUEST DETAILS
          </h2>
        </div>

        <h4 class="text-base font-semibold mb-2">Tracking No.</h4>
          <p class="view-field w-full mb-4"><?php echo htmlspecialchars($details['tracking_id']); ?></p>

        <!-- SECTION: Location Info -->
        <h4 class="text-base font-semibold mb-2">Location Details</h4>
          <p class="view-field w-full mb-4"><?php echo htmlspecialchars($details['location']); ?></p>

          <div class="w-full">
            <label for="picture" class="text-base mb-1 block font-medium">
              Photo Evidence
            </label>
            <?php if (!empty($details['image_path'])): ?>
            <?php
              $rawPath = (string) $details['image_path'];
              $fileName = basename($rawPath);
              $imageSrc = rtrim(PUBLIC_URL, '/') . '/uploads/repair_images/' . $fileName;
              $filePath = $_SERVER['DOCUMENT_ROOT'] . $imageSrc;
            ?>
            <?php if (file_exists($filePath)): ?>
              <div class="mt-2">
                <!-- <b>Attached Image:</b><br> -->
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

        <hr class="my-6 border-gray-400">

        <!-- SECTION: Request Info -->
        <h4 class="text-base font-semibold mb-2">Request Information</h4>
        <div class=" mb-4">
          <div>
            <label for="dateNoticed" class="text-xs mb-1 block font-medium">
              Date the Issue was Noticed 
            </label>
            <input type="date" id="dateNoticed" name="dateNoticed" class="view-field w-full" value="<?php echo date('Y-m-d'); ?>" readonly>
          </div>
        </div>

        <!-- Nature of Request -->
        <div class="mb-4">
          <label class="text-xs mb-2 block font-medium">
            Nature of Request 
          </label>
          <p class="view-field w-full"><?php echo htmlspecialchars($details['nature_request']); ?></p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="mb-4">
            <label class="text-xs mb-2 block font-medium">
              Status
            </label>
            <p class="view-field w-full"><?php echo htmlspecialchars($details['req_status']); ?></p>
          </div>
          <div class="mb-4">
            <label class="text-xs mb-2 block font-medium">
              Date Finished
            </label>
            <p class="view-field w-full"><?php echo $details['date_finished'] ?: "N/A"; ?></p>
          </div>
        </div>

        <!-- Description -->
        <div class="mb-4">
          <label for="description" class="text-xs mb-1 block font-medium">
            Detailed Description of the Issue 
          </label>
          <p class="view-field w-full"><?php echo htmlspecialchars($details['request_desc']); ?></p>
        </div>

        <p class="text-xs text-gray-500 text-center mt-6">
          © 2025 University of Southeastern Philippines — U-Request System
        </p>
      </div>


  <?php else: ?>
    <!-- HEADER -->
      <div class="flex flex-col items-center justify-center mb-6">
        <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 mb-2 mt-4">
        <h2 class="text-center text-lg font-semibold">
          VEHICLE REQUEST DETAILS
        </h2>
      </div>

      <!-- TRIP DETAILS -->
      <h4 class="text-base font-semibold mb-2">Trip Information</h4>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
        <div>
          <label class="text-xs mb-1 block font-medium">Purpose of Trip </label>
          <p class="view-field w-full"><?php echo htmlspecialchars($details['trip_purpose']); ?></p>
        </div>
        <div>
          <label class="text-xs mb-1 block font-medium">Travel Destination
          <p class="view-field w-full font-normal"><?php echo htmlspecialchars($details['travel_destination']); ?></p>
        </div>
        <div>
          <label class="text-xs mb-1 block font-medium">Date of Travel </label>
          <p class="view-field w-full">
            <?php 
              echo !empty($details['travel_date']) 
                ? htmlspecialchars(date("F j, Y", strtotime($details['travel_date']))) 
                : 'N/A'; 
            ?>
          </p>
        </div>
        <div>
          <label class="text-xs mb-1 block font-medium">Date of Return </label>
          <p class="view-field w-full">
            <?php 
              echo !empty($details['return_date']) 
                ? htmlspecialchars(date("F j, Y", strtotime($details['return_date']))) 
                : 'N/A'; 
            ?>
          </p>
        </div>
        <div>
          <label class="text-xs mb-1 block font-medium">Time of Departure </label>
          <p class="view-field w-full">
            <?php 
              if (!empty($details['departure_time'])) {
                  $time = date("g:i A", strtotime($details['departure_time']));
                  echo "<span>" . htmlspecialchars($time) . "</span>";
              } else {
                  echo 'N/A';
              }
            ?>
          </p>
        </div>
        <div>
          <label class="text-xs mb-1 block font-medium">Time of Return </label>
          <p class="view-field w-full">
            <?php 
              if (!empty($details['return_time'])) {
                  $time = date("g:i A", strtotime($details['return_time']));
                  echo "<span>" . htmlspecialchars($time) . "</span>";
              } else {
                  echo 'N/A';
              }
            ?>
          </p>
        </div>
      </div>
      <label class="text-xs mb-1 block font-medium">Date & Time Requested</label>
      <p class="view-field w-full">
        <?php 
          if (!empty($details['date_request'])) {
            $date = date("F j, Y", strtotime($details['date_request']));
            $time = date("g:i A", strtotime($details['date_request']));
            echo htmlspecialchars($date) . 
                " <span>(" . htmlspecialchars($time) . ")</span>";
          } else {
              echo 'N/A';
          }
        ?>
      </p>

      <p class="text-xs">&nbsp;</p>
      <hr class="my-6 border-gray-400">

      <!-- PASSENGERS -->
      <h4 class="text-base font-semibold mb-2">Passenger Information</h4>
      <div id="passenger-fields" class="space-y-3 mb-6">
        <div class="flex gap-1 passenger-row items-end">
          <div class="w-1/2">
            <label class="text-xs mb-1 block font-medium">First Name</label>
            <?php foreach ($details['passengers'] as $p): ?>
              <p class="view-field w-full"><?php echo htmlspecialchars($p['first_name']); ?></p>
            <?php endforeach; ?>
          </div>
          <div class="w-1/2">
            <label class="text-xs mb-1 block font-medium">Last Name</label>
            <?php foreach ($details['passengers'] as $p): ?>
              <p class="view-field w-full"><?php echo htmlspecialchars($p['last_name']); ?></p>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <p class="text-xs">&nbsp;</p>
      <hr class="my-6 border-gray-400">

      <!-- SOURCE OF FUND -->
      <h4 class="text-base font-semibold mb-2">Source of Fund</h4>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
          <label class="text-xs mb-1 block font-medium">Fuel </label>
          <p class="view-field w-full"><?= htmlspecialchars($details['source_of_fuel'] ?? 'N/A') ?></p>
        </div>
        <div>
          <label class="text-xs mb-1 block font-medium">Oil </label>
          <p class="view-field w-full"><?= htmlspecialchars($details['source_of_oil'] ?? 'N/A') ?></p>
        </div>
        <div>
          <label class="text-xs mb-1 block font-medium">Repair/Maintenance </label>
          <p class="view-field w-full"><?= htmlspecialchars($details['source_of_repair_maintenance'] ?? 'N/A') ?></p>
        </div>
        <div>
          <label class="text-xs mb-1 block font-medium">Driver/Assistant Per Diem </label>
          <p class="view-field w-full"><?= htmlspecialchars($details['source_of_driver_assistant_per_diem'] ?? 'N/A') ?></p>
        </div>
      </div>
      <!-- FOOTER -->
      <p class="text-xs text-gray-500 text-center mt-6">
        © 2025 University of Southeastern Philippines — U-Request System
      </p>
  <?php endif; ?>
</div>



</div>
