<?php
session_start();

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/RequestVehicleModel.php'; // ✅ make sure this file and class name match

try {
    $model = new VehicleRequestModel();

    if (!isset($_SESSION['req_id'])) {
        throw new Exception("Requester ID not found in session.");
    }

    $req_id = $_SESSION['req_id'];

    // ✅ Generate tracking ID
    $tracking_id = "TRK-" . date("Ymd") . "-" . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 5);

    // ✅ Vehicle Request Inputs
    $purpose_of_trip    = $_POST['purpose_of_trip'] ?? '';
    $travel_destination = $_POST['travel_destination'] ?? '';
    $date_of_travel     = $_POST['date_of_travel'] ?? '';
    $date_of_return     = $_POST['date_of_return'] ?? '';
    $time_of_departure  = $_POST['time_of_departure'] ?? '';
    $time_of_return     = $_POST['time_of_return'] ?? '';

    // ✅ Source of Fund Inputs
    $source_of_fuel                   = $_POST['source_of_fuel'] ?? '';
    $source_of_oil                    = $_POST['source_of_oil'] ?? '';
    $source_of_repair_maintenance     = $_POST['source_of_repair_maintenance'] ?? '';
    $source_of_driver_assistant_per_diem = $_POST['source_of_driver_assistant_per_diem'] ?? '';

    // ✅ Create Vehicle Request

    $control_no = $model->addVehicleRequest(
        $req_id,
        $tracking_id,
        $purpose_of_trip,
        $travel_destination,
        $date_of_travel,
        $date_of_return,
        $time_of_departure,
        $time_of_return,
        $source_of_fuel,
        $source_of_oil,
        $source_of_repair_maintenance,
        $source_of_driver_assistant_per_diem
    );

    if (!$control_no) {
        throw new Exception("Failed to create vehicle request. " . ($model->getLastError() ?? 'Please try again.'));
    }

    // ✅ Add passengers
    if (!empty($_POST['first_name']) && is_array($_POST['first_name'])) {
        foreach ($_POST['first_name'] as $i => $fname) {
            $lname = $_POST['last_name'][$i] ?? '';
            if (!empty($fname) && !empty($lname)) {
                $passenger_id = $model->addPassenger($fname, $lname);
                if ($passenger_id) {
                    $model->linkPassenger($control_no, $passenger_id);
                }
            }
        }
    }

    // ✅ Success
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Request Submitted',
        'message' => 'Your vehicle request and source of fund have been submitted successfully!'
    ];

} catch (Exception $e) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Request Failed',
        'message' => $e->getMessage()
    ];
}

// ✅ Redirect back
header("Location: ../modules/user/views/request.php");
exit;
?>
