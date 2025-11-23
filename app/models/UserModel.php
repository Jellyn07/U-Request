<?php

require_once __DIR__ . '/../core/BaseModel.php'; 
require_once __DIR__ . '/../config/encryption.php';

class UserModel extends BaseModel  {

    // SIGNUP - Create User
    public function createUser($ssid, $email, $fn, $ln, $pass) {
        $encrypted_pass = encrypt($pass);
         // $encrypted_email = encrypt($email);

        $stmt = $this->db->prepare("CALL spAddRequester(?, ?, ?, ?, ?)");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return false;
        }

        $stmt->bind_param("sssss", $ssid, $fn, $ln, $encrypted_pass, $email);
        $result = $stmt->execute();

        if (!$result) {
            $_SESSION['db_error'] = "Execute failed: " . $stmt->error;
            $stmt->close();
            return false;
        }

        $stmt->close();
        return $result;
    }

    // LOGIN - Get User by Email
    public function getUserByEmail($email) {
         // $encrypted_email = encrypt($email);
        $stmt = $this->db->prepare("SELECT pass FROM REQUESTER WHERE email = ?");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return null;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function fnGetRequesterContact($req_id) {
        $sql = "SELECT fnGetRequesterContact(?) AS contact";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("fnGetRequesterContact prepare failed: " . $this->db->error);
            return null;
        }

        $stmt->bind_param("i", $req_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        return $row['contact'] ?? null;
    }

    // LOGIN - Get Requester ID
    public function getRequesterId($email) {
         // $encrypted_email = encrypt($email);
        $stmt = $this->db->prepare("SELECT fnGetRequesterIdByEmail(?) AS req_id");
        if (!$stmt) {
            $_SESSION['db_error'] = "Prepare failed: " . $this->db->error;
            return null;
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    // LOGIN - Verify Password
    public function verifyPassword($input_pass, $stored_pass) {
        return $input_pass === decrypt($stored_pass);
    }

    // CHECK if Email Exists
    public function emailExists($email) {
         // $encrypted_email = encrypt($email);
        $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM vw_requesters WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['cnt'] > 0;
    }

    // CHECK if Student ID Exists
    public function studentIdExists($ssid) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM vw_requesters WHERE requester_id = ?");
        $stmt->bind_param("s", $ssid);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['cnt'] > 0;
    }

    // GET ADMIN User
    public function getAdminUserByEmail($email) {
        $stmt = $this->db->prepare("CALL spGetAdminByEmail(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    //  GET Encrypted Email ADMIN User
    // public function getAdminUserByEmail($email) {
    //     // Encrypt the email to match the database value
    //     $encryptedEmail = encrypt($email);

    //     $stmt = $this->db->prepare("CALL spGetAdminByEmail(?)");
    //     if (!$stmt) {
    //         error_log("Prepare failed: " . $this->db->error);
    //         return null;
    //     }

    //     $stmt->bind_param("s", $encryptedEmail);
    //     $stmt->execute();

    //     // Get the result
    //     $result = $stmt->get_result()->fetch_assoc();

    //     // Decrypt email in the returned row, if exists
    //     if ($result && isset($result['email'])) {
    //         $result['email'] = decrypt($result['email']);
    //     }

    //     $stmt->close();
    //     return $result;
    // }

    public function getRequestHistory($requester_id) {
    $records = [];
    $requester_id = strval($requester_id);

    // --- Query 1: Vehicle Requests (VR) ---
    $queryVR = "
        SELECT
            vr.tracking_id AS tracking_id,
            'Vehicle Request' AS request_Type,
            vr.trip_purpose AS request_desc,
            vr.travel_destination AS location,
            vra.req_status AS req_status,
            '--' AS date_finished,
            vr.travel_date AS sort_date  -- temporary for sorting
        FROM vehicle_request vr
        INNER JOIN vehicle_request_assignment vra 
            ON vr.control_no = vra.control_no
        INNER JOIN requester r 
            ON vr.req_id = r.req_id
        WHERE r.requester_id = ?
    ";

    if ($stmt = $this->db->prepare($queryVR)) {
        $stmt->bind_param("s", $requester_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        $stmt->close();
    }

    // --- Query 2: Non-VR Requests ---
    $queryNonVR = "
        SELECT
            v.tracking_id AS tracking_id,
            'Repair Request' AS request_Type,
            v.request_Type AS request_desc,
            v.req_status AS req_status,
            v.location AS location,
            v.date_finished AS date_finished,
            v.date_finished AS sort_date  -- temporary for sorting
        FROM vw_rqtrack v
        INNER JOIN requester r 
            ON v.req_id = r.req_id
        WHERE r.requester_id = ?
    ";

    if ($stmt = $this->db->prepare($queryNonVR)) {
        $stmt->bind_param("s", $requester_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        $stmt->close();
    }

    // --- Sort all records by date_finished / travel_date descending ---
    usort($records, function($a, $b) {
        return strtotime($b['sort_date']) - strtotime($a['sort_date']);
    });

    // Remove temporary sort_date field
    foreach ($records as &$row) {
        unset($row['sort_date']);
    }

    return $records;
}


    public function getVehicleRequestHistory($requester_id) {
        $stmt = $this->db->prepare("
            SELECT 
                vr.tracking_id,
                vr.trip_purpose,
                vr.travel_destination,
                vr.travel_date,
                vr.return_date,
                vra.req_status
            FROM vehicle_request vr
            inner join vehicle_request_assignment vra on vr.control_no = vra.control_no
            inner join requester r on vr.req_id = r.req_id
            WHERE requester_id = ?
            ORDER BY travel_date DESC
        ");
        $stmt->bind_param("s", $requester_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Destructor
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}




// public function getRequestHistory($requester_id) {
//     $records = [];
//     $requester_id = strval($requester_id);

//     // --- Query 1: Vehicle Requests (VR) ---
//     $queryVR = "
//         SELECT
//             vr.tracking_id AS tracking_id,
//             vr.trip_purpose AS type,
//             vr.travel_destination AS location,
//             vra.req_status AS status,
//             CONCAT(vr.travel_date, ' - ', vr.return_date) AS date_finished,
//             vr.travel_date AS sort_date  -- temporary for sorting
//         FROM vehicle_request vr
//         INNER JOIN vehicle_request_assignment vra 
//             ON vr.control_no = vra.control_no
//         INNER JOIN requester r 
//             ON vr.req_id = r.req_id
//         WHERE r.requester_id = ?
//     ";

//     if ($stmt = $this->db->prepare($queryVR)) {
//         $stmt->bind_param("s", $requester_id);
//         $stmt->execute();
//         $result = $stmt->get_result();
//         while ($row = $result->fetch_assoc()) {
//             $records[] = $row;
//         }
//         $stmt->close();
//     }

//     // --- Query 2: Non-VR Requests ---
//     $queryNonVR = "
//         SELECT
//             v.tracking_id AS tracking_id,
//             v.request_type AS type,
//             v.request_desc AS location,
//             v.req_status AS status,
//             v.date_finished AS date_finished,
//             v.date_finished AS sort_date  -- temporary for sorting
//         FROM vw_rqtrack v
//         INNER JOIN requester r 
//             ON v.req_id = r.req_id
//         WHERE r.requester_id = ?
//     ";

//     if ($stmt = $this->db->prepare($queryNonVR)) {
//         $stmt->bind_param("s", $requester_id);
//         $stmt->execute();
//         $result = $stmt->get_result();
//         while ($row = $result->fetch_assoc()) {
//             $records[] = $row;
//         }
//         $stmt->close();
//     }

//     // --- Sort all records by date_finished / travel_date descending ---
//     usort($records, function($a, $b) {
//         return strtotime($b['sort_date']) - strtotime($a['sort_date']);
//     });

//     // Remove temporary sort_date field
//     foreach ($records as &$row) {
//         unset($row['sort_date']);
//     }

//     return $records;
// }

