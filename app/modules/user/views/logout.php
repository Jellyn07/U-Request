<?php
session_start();
$_SESSION = [];        // clear session variables
session_unset();
session_destroy();

header("Location: login.php");
exit;
