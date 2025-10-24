<?php
if (!isset($_SESSION)) session_start();

/**
 * Set the MySQL session variable for triggers to know the current staff.
 *
 * @param mysqli $conn MySQLi connection object
 */
function setCurrentStaff($conn) {
    if (isset($_SESSION['staff_id'])) {
        $staffId = $conn->real_escape_string($_SESSION['staff_id']);
        $conn->query("SET @current_staff_id = '$staffId'");
    }
}
