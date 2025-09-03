<?php
require_once __DIR__ . '/../config/constants.php';
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
  <body class="bg-primary">
    <form class="w-1/2 m-5 mx-auto rounded-lg border border-gray-100 bg-white p-4 shadow-lg transition hover:shadow-lg sm:p-6">
    <div id="rrs-form">
      <img src="<?php echo PUBLIC_URL; ?>/assets/img/usep.png" alt="USeP Logo" class="w-20 h-20 flex justify-center items-center">
      <h4>VEHICLE REQUEST FORM</h4>

      <label>Date of Travel:<span>*</span></label>
      <input type="date"><br>

      <label>Date of Return:<span>*</span></label>
      <input type="date"><br>

      <label>Time of Departure:<span>*</span></label>
      <input type="time"><br>

      <label>Time of Return:<span>*</span></label>
      <input type="time"><br>

      <button type="button" class="inside-block bg-background text-primary p-2 rounded-md text-sm border border-primary" onclick="location.href='request.php'">Back</button>
      <button class="inside-block bg-secondary text-background p-2 rounded-md text-sm" type="submit" onclick="showReview()">Review</button>
    </div>
    </form>
  </body>
</html>