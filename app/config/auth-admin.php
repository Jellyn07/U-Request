<?php
// Prevent going back to previous pages after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Start session

// Auto logout after 1 minute (60 seconds) of inactivity
$timeout_duration = 300;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Session expired
    session_unset();
    session_destroy();
    header("Location: /app/modules/shared/views/admin_login.php?expired=1");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: /app/modules/shared/views/admin_login.php");
    exit();
}
