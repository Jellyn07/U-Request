<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../core/BaseModel.php'; 
class ScheduleModel extends BaseModel {

    public function getTrips() {
        $query = "
        SELECT 
            vr.tracking_id,
            vr.req_id,
            vr.travel_date,
            vr.return_date,
            vr.travel_destination,
            vr.trip_purpose,
            vr.departure_time, 
            vr.return_time,
            vra.req_status,
            vra.reason,
            v.vehicle_name
        FROM vehicle_request vr
        LEFT JOIN vehicle_request_assignment vra 
            ON vr.control_no = vra.control_no   -- use control_no instead of req_id (more precise)
        LEFT JOIN vehicle v 
            ON vra.vehicle_id = v.vehicle_id
        ORDER BY vr.date_request DESC;
        ";

        $result = $this->db->query($query);
        $trips = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $startDate = $row['travel_date'];
                $endDate = $row['return_date'];

                $departure = date('g:i A', strtotime($row['departure_time']));
                $return = date('g:i A', strtotime($row['return_time']));
                $formattedReturnDate = date('F j, Y', strtotime($endDate));

                $period = new DatePeriod(
                    new DateTime($startDate),
                    new DateInterval('P1D'),
                    (new DateTime($endDate))->modify('+1 day')
                );

                foreach ($period as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $time = ($dateStr === $startDate)
                        ? "$departure - $return"
                        : (($dateStr === $endDate) ? "$departure - $return" : 'All Day');

                    $trips[$dateStr][] = [
                        'vehicle' => $row['vehicle_name'] ?? 'Unassigned',
                        'purpose' => $row['trip_purpose'],
                        'destination' => $row['travel_destination'],
                        'status' => $row['req_status'] ?? 'Pending',
                        'reason' => $row['reason'] ?? '',
                        'req_id' => $row['req_id'],
                        'time' => $time,
                        'return_date_formatted' => $formattedReturnDate
                    ];
                }
            }
        }

        return $trips;
    }

    public function __destruct() {
        $this->db->close();
    }
}
