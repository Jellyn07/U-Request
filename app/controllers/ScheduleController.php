<?php
require_once __DIR__ . '/../models/ScheduleModel.php';

class ScheduleController {
    public $model;

    public function __construct() {
        $this->model = new ScheduleModel();
    }

    public function fetchTrips() {
        return $this->model->getTrips();
    }
}
