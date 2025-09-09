<?php
session_start();

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/RequestVehicleModel.php';

error_log(print_r($_POST, true));
try {
    $model = new VehicleRequestModel();

    // Vehicle request
    $tracking_id       = "TRK-" . date("Ymd") . "-" . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 5);
    $purpose_of_trip   = $_POST['purpose_of_trip'];
    $travel_destination= $_POST['travel_destination'];
    $date_of_travel    = $_POST['date_of_travel'];
    $date_of_return    = $_POST['date_of_return'];
    $time_of_departure = $_POST['time_of_departure'];
    $time_of_return    = $_POST['time_of_return'];

    $control_no = $model->addVehicleRequest(
        $tracking_id,
        $purpose_of_trip,
        $travel_destination,
        $date_of_travel,
        $date_of_return,
        $time_of_departure,
        $time_of_return
    );

    if (!$control_no) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Request Failed',
            'message' => 'Failed to create vehicle request. Please try again.'
        ];
        header("Location: ../modules/user/views/request.php");
        exit;
    }

    // Passengers
    if (!empty($_POST['first_name']) && !empty($_POST['last_name'])) {
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

    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Request Submitted',
        'message' => 'Your vehicle request has been submitted successfully!'
    ];
    header("Location: ../modules/user/views/request.php");
    exit;

} catch (Exception $e) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Request Failed',
        'message' => $e->getMessage()
    ];
    header("Location: ../modules/user/views/request.php");
    exit;
}
