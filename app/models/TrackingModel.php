<?php
require_once __DIR__ . '/../core/BaseModel.php';

class TrackingModel extends BaseModel {

    // Get all tracking requests by email
    public function getTrackingByEmail($email) {
        $sql = "
            SELECT t.tracking_id, 
                   t.request_Type as nature_request, 
                   t.location, 
                   t.req_status, 
                   t.date_finished, 
                   t.req_id, 
                   t.request_desc
            FROM vw_rqtrack t
            INNER JOIN requester r ON t.req_id = r.req_id
            WHERE r.email = ?
            ORDER BY t.tracking_id DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $tracking = [];
        while ($row = $result->fetch_assoc()) {
            $tracking[] = $row;
        }
        return $tracking;
    }

    // Get single tracking details by email + tracking_id
    public function getTrackingDetails($tracking_id, $email) {
        $sql = "
            SELECT 
                t.tracking_id,
                t.request_Type AS nature_request,
                t.location,
                t.req_status,
                t.date_finished,
                t.req_id,
                t.request_desc,
                r2.image_path
            FROM vw_rqtrack t
            INNER JOIN requester r 
                ON t.req_id = r.req_id
            LEFT JOIN request r2 
                ON t.tracking_id = r2.tracking_id   
            WHERE r.email = ? 
            AND t.tracking_id = ?               
            LIMIT 1;
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $email, $tracking_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

}
