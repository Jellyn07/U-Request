<?php

require_once __DIR__ . '/../core/BaseModel.php'; 

class RequestModel extends BaseModel {
    public $lastError = null;

    public function createRequest($tracking_id, $nature, $req_id, $description, $unit, $location, $dateNoticed, $filePath) {
        // Step 1: Insert into REQUEST
        $stmt = $this->db->prepare("
            CALL spAddRequest(?, ?, ?, ?, ?, ?, ?, ?)");
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

    public function checkDuplicateRequest($unit, $location, $nature) {
        $sql = "
            SELECT request_id 
            FROM request 
            WHERE unit = ? AND location = ? AND request_Type = ?
            LIMIT 1
        "; // make a function to the db (secured)
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $unit, $location, $nature);
        $stmt->execute();
    
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns row if found, null if not
    }

    // In RequestModel.php
    public function getAllRequesters() {
        $sql = "SELECT * FROM vw_requesters"; 
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRequesterById($id) {
        $stmt = $this->db->prepare("SELECT * FROM vw_requesters WHERE requester_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

      // Get all requests from vw_requests
      public function getAllRequests() {
        $sql = "
          SELECT 
                vw.*,
                r.*,
                ra.*,
                rap.staff_id,
                vwg.full_name
            FROM vw_requests vw
            INNER JOIN request r 
                ON vw.request_id = r.request_id
            LEFT JOIN request_assignment ra
                ON r.request_id = ra.request_id
            LEFT JOIN request_assigned_personnel rap
                ON r.request_id = rap.request_id
            LEFT JOIN vw_gsu_personnel vwg
                ON rap.staff_id = vwg.staff_id
            WHERE rap.staff_id IS NULL OR rap.staff_id = (
                SELECT MIN(staff_id) 
                FROM request_assigned_personnel 
                WHERE request_id = r.request_id
            )
            ORDER BY vw.request_date DESC;
            ";
        $result = $this->db->query($sql);

        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Get single request by ID
    public function getRequestById($id) {
        $id = (int) $id; // prevent SQL injection
        $sql = "SELECT request_id, Name, request_Type, location, request_date, req_status 
                FROM vw_requests
                WHERE request_id = $id
                LIMIT 1";

        $result = $this->db->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

   // In RequestModel.php
   public function addAssignment($request_id, $req_id, $req_status, $staff_id, $prio_level, $date_finished = null) {
    try {
        // ✅ 0. Check if request assignment already exists
        $checkStmt = $this->db->prepare("SELECT COUNT(*) AS count FROM REQUEST_ASSIGNMENT WHERE request_id = ?");
        $checkStmt->bind_param("i", $request_id);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            $_SESSION['alert'] = [
                "type" => "warning",
                "title" => "Already Assigned",
                "message" => "This request already has an assignment. No duplicate created."
            ];
            return false;
        }

        // 1️⃣ Add Request Assignment
        $stmt = $this->db->prepare("CALL spAddRequestAssignment(?, ?, ?, ?)");
        if (!$stmt) {
            $_SESSION['alert'] = [
                "type" => "error",
                "title" => "Database Error",
                "message" => "Add Assignment failed: " . $this->db->error
            ];
            return false;
        }
        $stmt->bind_param("iiss", $request_id, $req_id, $req_status, $date_finished);
        $stmt->execute();
        $stmt->close();

        // 2️⃣ Update Priority Status
        $stmt2 = $this->db->prepare("CALL spUpdateRequestPriorityStatus(?, ?)");
        if (!$stmt2) {
            $_SESSION['alert'] = [
                "type" => "error",
                "title" => "Database Error",
                "message" => "Update Priority failed: " . $this->db->error
            ];
            return false;
        }
        $stmt2->bind_param("is", $request_id, $prio_level);
        $stmt2->execute();
        $stmt2->close();

        // 3️⃣ Assign Personnel
        $stmt3 = $this->db->prepare("CALL spAssignPersonnel(?, ?)");
        if (!$stmt3) {
            $_SESSION['alert'] = [
                "type" => "error",
                "title" => "Database Error",
                "message" => "Assign Personnel failed: " . $this->db->error
            ];
            return false;
        }
        $stmt3->bind_param("ii", $request_id, $staff_id);
        $stmt3->execute();
        $stmt3->close();

        // ✅ Success
        $_SESSION['alert'] = [
            "type" => "success",
            "title" => "Success",
            "message" => "Assignment, priority, and personnel saved successfully."
        ];

        return true;

    } catch (Exception $e) {
        $_SESSION['alert'] = [
            "type" => "error",
            "title" => "Error",
            "message" => $e->getMessage()
        ];
        return false;
    }
}

public function updateRequestStatus($request_id, $req_status) {
    try {
        $stmt = $this->db->prepare("CALL spUpdateRequestStatus(?, ?)");
        $stmt->bind_param("is", $request_id, $req_status);
        $stmt->execute();
        $stmt->close();

        return ["success" => true, "message" => "Status updated successfully."];
    } catch (Exception $e) {
        return ["success" => false, "message" => $e->getMessage()];
    }
}



    
    public function getAvailableStaff() {
        $sql = "
            SELECT gp.staff_id, gp.full_name
            FROM vw_gsu_personnel gp
            WHERE gp.staff_id NOT IN (
                SELECT rap.staff_id
                FROM request_assigned_personnel rap
                INNER JOIN request_assignment ra 
                    ON rap.request_id = ra.request_id
                WHERE ra.date_finished IS NULL
            )
            ORDER BY gp.full_name ASC
        ";
    
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    

    // Get profile data by email
    public function getProfileByEmail($admin_email)
    {
        $stmt = $this->db->prepare("
            SELECT profile_picture
            FROM administrator
            WHERE email = ?
        ");
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns single row
    }
}
