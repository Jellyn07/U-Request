<?php
// filepath: app/models/UserAdminModel.php
require_once __DIR__ . '/../core/BaseModel.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class UserAdminModel extends BaseModel
{
    /**
     * Get users with search, status, and sort filters
     */
    public function getUsers($search = '', $status = 'all', $sort = 'az')
    {
        $sql = "
        SELECT 
            r.req_id,
            r.requester_id,
            r.firstName,
            r.lastName,
            CONCAT(r.firstName, ' ', r.lastName) AS full_name,
            r.email,
            r.officeOrDept,
            r.profile_pic,
            CASE 
                WHEN EXISTS (
                    SELECT 1
                    FROM vw_rqtrack v
                    WHERE v.req_id = r.req_id
                    AND v.req_status <> 'Completed'
                ) THEN 'Active'
                ELSE 'Inactive'
            END AS account_status
        FROM requester r
        WHERE 1=1
    ";

        // 🔍 Optional search
        if (!empty($search)) {
            $escaped = $this->db->real_escape_string($search);
            $sql .= " AND (r.firstName LIKE '%$escaped%' OR r.lastName LIKE '%$escaped%' OR r.email LIKE '%$escaped%')";
        }

        // ⚙️ Filter by account status
        if ($status === 'have_pending') {
            $sql .= " HAVING account_status = 'Active'";
        } elseif ($status === 'no_pending') {
            $sql .= " HAVING account_status = 'Inactive'";
        }

        // 🔤 Sorting options
        if ($sort === 'az') {
            $sql .= " ORDER BY full_name ASC";
        } elseif ($sort === 'za') {
            $sql .= " ORDER BY full_name DESC";
        }

        $result = $this->db->query($sql);

        if (!$result) {
            die('Database query failed: ' . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }



    // Get profile data by email
    public function getProfileByEmail($admin_email)
    {
        $stmt = $this->db->prepare("
            SELECT profile_pic
            FROM vw_requesters
            WHERE email = ?
        ");
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns single row
    }

    public function getUserDetails($requester_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                requester_id,
                firstName,
                lastName,
                CONCAT(firstName, ' ', lastName) AS full_name,
                email,
                officeOrDept,
                profile_pic
            FROM vw_requesters
            WHERE requester_id = ?
        ");
        $stmt->bind_param("s", $requester_id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc(); // return one user record
    }

    // Get GSU Personnel Work History
  public function getRequestHistory($requester_id) {
        $records = [];
        $requester_id = intval($requester_id);

        $query = "
            SELECT 
                v.tracking_id,
                v.request_Type,
                v.request_desc,
                v.location,
                v.req_status,
                v.date_finished
            FROM vw_rqtrack v
            WHERE v.req_id IN (
                SELECT req_id FROM requester WHERE requester_id = ?
            )
            ORDER BY v.date_finished DESC
        ";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("i", $requester_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $records[] = $row;
            }

            $stmt->close();
        }

        return $records;
    }



//     public function getWorkHistory($req_id) {
//     try {
//         $sql = "
//             SELECT 
//                 rt.request_id,
//                 rt.req_id,
//                 rt.request_title,
//                 rt.req_status,
//                 rt.date_requested,
//                 rt.date_completed
//             FROM requester_status rt
//             WHERE rt.req_id = ?
//             ORDER BY rt.date_requested DESC
//         ";

//         $stmt = $this->db->prepare($sql);
//         $stmt->execute([$req_id]);
//         return $stmt->fetchAll(PDO::FETCH_ASSOC);
//     } catch (PDOException $e) {
//         error_log('getWorkHistory Error: ' . $e->getMessage());
//         return [];
//     }
// }
}
