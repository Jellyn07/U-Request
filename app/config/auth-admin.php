<?php
// Auto logout after 1 minute (60 seconds) of inactivity
$timeout_duration = 60;

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
