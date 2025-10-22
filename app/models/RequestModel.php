<?php

require_once __DIR__ . '/../core/BaseModel.php'; 

class RequestModel extends BaseModel {
    public $lastError = null;

    public function createRequest($tracking_id, $nature, $req_id, $description, $unit, $location, $dateNoticed, $filePath) {
        // Step 1: Insert into REQUEST using stored procedure
        $stmt = $this->db->prepare("CALL spAddRequest(?, ?, ?, ?, ?, ?, ?, ?)");
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

        // ✅ Fetch the newly inserted request_id from the procedure's SELECT result
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $request_id_int = $row['request_id'] ?? 0;
        $stmt->close();

        if (!$request_id_int) {
            $this->lastError = 'Failed to retrieve new request ID.';
            return false; // ❌ Could not get request ID
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
                GROUP BY r.request_id
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
   public function addAssignment($request_id, $req_status, $staff_ids, $prio_level, $materials = []) {
    try {
        $this->db->begin_transaction();

        // ✅ 1️⃣ Check if personnel already assigned for this request
        $countStmt = $this->db->prepare("
            SELECT COUNT(*) AS personnel_count 
            FROM request_assigned_personnel 
            WHERE request_id = ?
        ");
        if (!$countStmt) {
            throw new Exception("Prepare failed (count personnel): " . $this->db->error);
        }
        $countStmt->bind_param("i", $request_id);
        $countStmt->execute();
        $countStmt->bind_result($personnelCount);
        $countStmt->fetch();
        $countStmt->close();

        $isNewAssignment = false; // track if new personnel were added

        // ✅ 2️⃣ If no personnel yet, insert new ones
        if ($personnelCount == 0 && !empty($staff_ids) && is_array($staff_ids)) {
            $stmt3 = $this->db->prepare("CALL spAssignPersonnel(?, ?)");
            if (!$stmt3) {
                throw new Exception("Prepare failed (insert personnel): " . $this->db->error);
            }

            foreach ($staff_ids as $staff_id) {
                if (!empty($staff_id)) {
                    $stmt3->bind_param("ii", $request_id, $staff_id);
                    $stmt3->execute();
                }
            }
            $stmt3->close();
            $isNewAssignment = true;
        }

        // ✅ 3️⃣ Retrieve reqAssignment_id from request_assignment using request_id
        $reqAssignment_id = null;
        $getAssignStmt = $this->db->prepare("
            SELECT reqAssignment_id 
            FROM request_assignment 
            WHERE request_id = ? 
            LIMIT 1
        ");
        if (!$getAssignStmt) {
            throw new Exception("Prepare failed (retrieve reqAssignment_id): " . $this->db->error);
        }
        $getAssignStmt->bind_param("i", $request_id);
        $getAssignStmt->execute();
        $getAssignStmt->bind_result($reqAssignment_id);
        $getAssignStmt->fetch();
        $getAssignStmt->close();

        // ✅ 4️⃣ Always update Priority Level (even if personnel already exists)
        if (!empty($prio_level)) {
            $stmt2 = $this->db->prepare("CALL spUpdateRequestPriorityStatus(?, ?)");
            if (!$stmt2) {
                throw new Exception("Prepare failed (priority update): " . $this->db->error);
            }
            $stmt2->bind_param("is", $request_id, $prio_level);
            $stmt2->execute();
            $stmt2->close();
            while ($this->db->more_results() && $this->db->next_result()) {;}
        }

        // ✅ 5️⃣ Always update Request Status
        $stmtStatus = $this->db->prepare("CALL spUpdateRequestStatus(?, ?)");
        if (!$stmtStatus) {
            throw new Exception("Prepare failed (status update): " . $this->db->error);
        }
        $stmtStatus->bind_param("is", $request_id, $req_status);
        $stmtStatus->execute();
        $stmtStatus->close();
        while ($this->db->more_results() && $this->db->next_result()) {;}

        // ✅ 6️⃣ Add Materials Needed (if provided)
        if (!empty($materials) && is_array($materials) && !empty($reqAssignment_id)) {
            // Insert materials needed
            $stmt4 = $this->db->prepare("
                INSERT INTO request_materials_needed (reqAssignment_id, material_code, quantity_needed, date_added)
                VALUES (?, ?, ?, NOW())
            ");
            if (!$stmt4) {
                throw new Exception("Prepare failed (materials insert): " . $this->db->error);
            }

            // Prepare the update statement to deduct stock
            $updateStockStmt = $this->db->prepare("
                UPDATE materials
                SET qty = qty - ?
                WHERE material_code = ? AND qty >= ?
            ");
            if (!$updateStockStmt) {
                throw new Exception("Prepare failed (stock update): " . $this->db->error);
            }

            foreach ($materials as $item) {
                if (!empty($item['material_code'])) {
                    $material_code = (int) $item['material_code'];
                    $quantity_needed = (int) ($item['qty'] ?? 1);

                    // ✅ Insert into request_materials_needed
                    $stmt4->bind_param("iii", $reqAssignment_id, $material_code, $quantity_needed);
                    $stmt4->execute();

                    // ✅ Deduct from materials table (only if enough stock)
                    $updateStockStmt->bind_param("iii", $quantity_needed, $material_code, $quantity_needed);
                    $updateStockStmt->execute();

                    // Optional: Check if deduction was successful
                    if ($updateStockStmt->affected_rows === 0) {
                        throw new Exception("Insufficient stock for material code: $material_code");
                    }
                }
            }
            $stmt4->close();
            $updateStockStmt->close();
        }
        // ✅ 7️⃣ Commit all changes
        $this->db->commit();

        // ✅ 8️⃣ Different alerts based on action type
        if ($isNewAssignment) {
            $_SESSION['alert'] = [
                "type" => "success",
                "title" => "Assignment Added",
                "message" => "New personnel were successfully assigned and request details were updated."
            ];
        } else {
            $_SESSION['alert'] = [
                "type" => "info",
                "title" => "Request Updated",
                "message" => "This request already has assigned personnel. Status, priority, and materials were updated."
            ];
        }

            return true;

        } catch (Exception $e) {
            // ❌ Rollback on any error
            $this->db->rollback();
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

    public function getAllMaterials() {
        $sql = "SELECT material_code, material_desc 
                FROM materials 
                WHERE qty > ?";

        // Prepare the statement
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->db->error);
        }

        // Bind parameters (qty > 0)
        $minQty = 0;
        $stmt->bind_param("i", $minQty);

        // Execute
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();
        $materials = $result->fetch_all(MYSQLI_ASSOC);

        // Close
        $stmt->close();

        return $materials;
    }

    public function getLocationsByUnit($unit) {
        $stmt = $this->db->prepare("
            SELECT building, exact_location 
            FROM campus_locations 
            WHERE unit = ?
            ORDER BY building, exact_location
        ");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("s", $unit);
        $stmt->execute();
        $result = $stmt->get_result();

        $locations = [];
        while ($row = $result->fetch_assoc()) {
            $locations[] = $row;
        }

        return ["success" => true, "locations" => $locations];
    }


}
