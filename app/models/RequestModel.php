<?php

require_once __DIR__ . '/../core/BaseModel.php'; 

class RequestModel extends BaseModel {
    public $lastError = null;

    public function createRequest($tracking_id, $nature, $req_id, $description, $unit, $location, $dateNoticed, $filePath) {
        // Step 1: Insert into REQUEST
        $stmt = $this->db->prepare("
            INSERT INTO REQUEST 
            (tracking_id, request_Type, req_id, request_desc, unit, location, request_date, image_path)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssisssss",
            $tracking_id, $nature, $req_id, $description,
            $unit, $location, $dateNoticed, $filePath
        );

        if (!$stmt->execute()) {
            $this->lastError = $stmt->error ?: $this->db->error;
            $stmt->close();
            return false; // ❌ Insert failed
        }

        // Get the newly inserted request_id directly
        $request_id_int = $this->db->insert_id;
        $stmt->close();

        if (!$request_id_int) {
            $this->lastError = 'Failed to retrieve insert_id';
            return false; // ❌ Could not get insert ID
        }

        // Step 2: Insert into REQUEST_ASSIGNMENT
        $req_status = 'To Inspect';
        $date_finished = null;

        $stmt2 = $this->db->prepare("CALL spAddRequestAssignment(?, ?, ?, ?)");
        $stmt2->bind_param("iiss", $request_id_int, $req_id, $req_status, $date_finished);

        if (!$stmt2->execute()) {
            $this->lastError = $stmt2->error ?: $this->db->error;
            $stmt2->close();
            return false; // ❌ Assignment failed
        }
        $stmt2->close();

        // ✅ Return the new request_id
        return $request_id_int;
    }
}
