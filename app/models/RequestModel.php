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
        $sql = "SELECT 
                    vw.*,
                    r.*,
                    ra.*,
                    COALESCE(
                        GROUP_CONCAT(
                            DISTINCT vwg.full_name SEPARATOR ', '
                        ),
                        'No personnel assigned'
                    ) AS assigned_personnel,
                    COALESCE(
                        GROUP_CONCAT(
                            DISTINCT CONCAT(m.material_desc, ' (Qty: ', rm.quantity_needed, ')')
                            SEPARATOR ', '
                        ),
                        'No materials used'
                    ) AS materials_needed
                FROM vw_requests vw
                INNER JOIN request r 
                    ON vw.request_id = r.request_id
                LEFT JOIN request_assignment ra
                    ON r.request_id = ra.request_id
                LEFT JOIN request_assigned_personnel rap
                    ON r.request_id = rap.request_id
                LEFT JOIN vw_gsu_personnel vwg
                    ON rap.staff_id = vwg.staff_id
                LEFT JOIN request_materials_needed rm
                    ON ra.reqAssignment_id = rm.reqAssignment_id
                LEFT JOIN materials m
                    ON rm.material_code = m.material_code
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
   public function addAssignment(
        $request_id,
        $req_status,
        $staff_ids = [],
        $prio_level = null,
        $materials_to_add = [],    // array of ['material_code'=>int, 'qty'=>int]
        $remove_staff_ids = [],    // array of staff_id ints to remove
        $materials_to_remove = []  // array of material_code ints to remove
    ) {
        try {
            $this->db->begin_transaction();

            // Update Priority Level
            if (!empty($prio_level)) {
                $stmt = $this->db->prepare("CALL spUpdateRequestPriorityStatus(?, ?)");
                $stmt->bind_param("is", $request_id, $prio_level);
                $stmt->execute();
                $stmt->close();
                while ($this->db->more_results() && $this->db->next_result()) {;}
            }

            // Update Request Status
            $stmt = $this->db->prepare("CALL spUpdateRequestStatus(?, ?)");
            $stmt->bind_param("is", $request_id, $req_status);
            $stmt->execute();
            $stmt->close();
            while ($this->db->more_results() && $this->db->next_result()) {;}

            // Remove personnel
            if (!empty($remove_staff_ids)) {
                $delStmt = $this->db->prepare("
                    DELETE FROM request_assigned_personnel 
                    WHERE request_id = ? AND staff_id = ?
                ");
                foreach ($remove_staff_ids as $sid) {
                    $delStmt->bind_param("ii", $request_id, $sid);
                    $delStmt->execute();
                }
                $delStmt->close();
            }

            // Add personnel
            if (!empty($staff_ids)) {
                $stmt = $this->db->prepare("
                    INSERT IGNORE INTO request_assigned_personnel (request_id, staff_id)
                    VALUES (?, ?)
                ");
                foreach ($staff_ids as $sid) {
                    $stmt->bind_param("ii", $request_id, $sid);
                    $stmt->execute();
                }
                $stmt->close();
            }

            // Get reqAssignment_id
            $reqAssignment_id = null;
            $assignStmt = $this->db->prepare("SELECT reqAssignment_id FROM request_assignment WHERE request_id = ? LIMIT 1");
            $assignStmt->bind_param("i", $request_id);
            $assignStmt->execute();
            $assignStmt->bind_result($reqAssignment_id);
            $assignStmt->fetch();
            $assignStmt->close();

            // Remove materials
            if (!empty($materials_to_remove)) {
                $delMatStmt = $this->db->prepare("
                    DELETE FROM request_materials_needed 
                    WHERE reqAssignment_id = ? AND material_code = ?
                ");
                foreach ($materials_to_remove as $code) {
                    $delMatStmt->bind_param("ii", $reqAssignment_id, $code);
                    $delMatStmt->execute();
                }
                $delMatStmt->close();
            }

            if (!empty($materials_to_add)) {
                // Prepare INSERT with ON DUPLICATE KEY UPDATE
                $stmtAdd = $this->db->prepare("
                    INSERT INTO request_materials_needed (reqAssignment_id, material_code, quantity_needed, date_added)
                    VALUES (?, ?, ?, NOW())
                    ON DUPLICATE KEY UPDATE 
                        quantity_needed = quantity_needed + VALUES(quantity_needed)
                ");

                // Prepare stock update
                $updateStock = $this->db->prepare("
                    UPDATE materials SET qty = qty - ? WHERE material_code = ? AND qty >= ?
                ");

                foreach ($materials_to_add as $m) {
                    $code = (int) $m['material_code'];
                    $qty  = (int) $m['qty'];

                    // Bind parameters: reqAssignment_id, material_code, quantity_needed
                    $stmtAdd->bind_param("iii", $reqAssignment_id, $code, $qty);
                    $stmtAdd->execute();

                    // Deduct stock
                    $updateStock->bind_param("iii", $qty, $code, $qty);
                    $updateStock->execute();

                    if ($updateStock->affected_rows === 0) {
                        throw new Exception("Insufficient stock for material code: $code");
                    }
                }

                $stmtAdd->close();
                $updateStock->close();
            }
            $this->db->commit();
            $_SESSION['alert'] = [
                "type" => "success",
                "title" => "Request Updated",
                "message" => "Personnel, materials, and request details successfully updated."
            ];
            return true;

        } catch (Exception $e) {
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

    // Vehicle Requests
   public function getAllVehicleRequests() {
    // First, get the main vehicle requests
    $query = "
        SELECT 
            v.*,
            CONCAT(r.firstName, ' ', r.lastName) AS requester_name,
            r.contact,
            vr.vehicle_id,
            vr.driver_id,
            CONCAT(d.firstName, ' ', d.lastName) AS driver_name,
            vr.req_status,
            vr.approved_by,
            vl.vehicle_name
        FROM vehicle_request v
        INNER JOIN requester r ON v.req_id = r.req_id
        LEFT JOIN vehicle_request_assignment vr ON v.control_no = vr.control_no
        LEFT JOIN vehicle vl ON vl.vehicle_id = vr.vehicle_id
        LEFT JOIN driver d ON d.driver_id = vr.driver_id
        ORDER BY v.date_request DESC;
        ";
        
        $result = $this->db->query($query);
        if (!$result) {
            die('Query Error: ' . $this->db->error);
        }

        $requests = [];
        while ($row = $result->fetch_assoc()) {
            // Fetch passengers for this request
            $control_no = $row['control_no'];
            $passengersQuery = "
                SELECT CONCAT(p.firstName, ' ', p.lastName) AS name
                FROM passengers p
                INNER JOIN vehicle_request_passengers vrp ON p.passenger_id = vrp.passenger_id
                WHERE vrp.control_no = '{$this->db->real_escape_string($control_no)}'
            ";
            $passengersResult = $this->db->query($passengersQuery);
            $passengers = [];
            if ($passengersResult) {
                while ($p = $passengersResult->fetch_assoc()) {
                    $passengers[] = $p['name'];
                }
            }

            $row['passengers'] = $passengers;                     // Array of passenger names
            $row['passenger_count'] = count($passengers);         // Number of passengers

            $requests[] = $row;
        }

        return $requests;
    }

    public function getAssignedPersonnel($request_id) {
        $stmt = $this->db->prepare("SELECT staff_id FROM request_assigned_personnel WHERE request_id = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = [];
        while ($row = $result->fetch_assoc()) {
            $staff[] = $row['staff_id'];
        }
        return $staff;
    }
}