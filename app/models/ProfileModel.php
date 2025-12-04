<?php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/encryption.php';
require_once __DIR__ . '/../config/db_helpers.php';
class ProfileModel extends BaseModel
{

    // Get profile data by requester_id
    public function getProfileByEmail($requester_email)
    {
        $requester_email = encrypt($requester_email);
        $stmt = $this->db->prepare("
            SELECT requester_id, firstName, lastName, contact, email, officeOrDept, profile_pic
            FROM vw_requesters
            WHERE email = ?
        ");

        $stmt->bind_param("s", $requester_email);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc(); // single row

        if ($row && isset($row['email'])) {
            $row['email'] = decrypt($row['email']); // decrypt email before returning
        }

        return $row;
    }

    // Update department/office
    public function updateOfficeOrDept($email, $officeOrDept)
    {
        if (isset($_SESSION['req_id'])) {
            setCurrentRequester($this->db); // Use model's connection
        }
        $email = encrypt($email);
        $stmt = $this->db->prepare("
            UPDATE requester
            SET officeOrDept = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $officeOrDept, $email);
        return $stmt->execute();
    }

    public function updateContact($req_id, $contact)
    {
        if (isset($_SESSION['req_id'])) {
            setCurrentRequester($this->db); // Use model's connection
        }
        $stmt = $this->db->prepare("UPDATE requester SET contact = ? WHERE req_id = ?");
        $stmt->bind_param("si", $contact, $req_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // Check if contact exists in gsu_personnel, driver, administrator
    public function contactExistsElsewhere($contact)
    {
        $tables = [
            'gsu_personnel' => 'contact',
            'driver'        => 'contact',
            'administrator' => 'contact_no',
            'requester'     => 'contact'
        ];

        foreach ($tables as $table => $column) {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM {$table} WHERE {$column} = ?");
            if (!$stmt) {
                die("Prepare failed: " . $this->db->error);
            }
            $stmt->bind_param("s", $contact);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($result['count'] > 0) {
                return true;
            }
        }

        return false;
    }

    // Update profile picture
    public function updateProfilePicture($email, $fileName)
    {
        $sql = "UPDATE requester SET profile_pic = ? WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("ss", $fileName, $email);
        return $stmt->execute();
    }


    // Update password with encryption
    public function updatePassword($requester_email, $newPassword)
    {
        if (isset($_SESSION['req_id'])) {
            setCurrentRequester($this->db); // Use model's connection
        }
        $encryptedPassword = encrypt($newPassword);
        $encrypted_email = encrypt($requester_email);
        $stmt = $this->db->prepare("
            UPDATE requester 
            SET pass = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $encryptedPassword,  $encrypted_email);
        return $stmt->execute();
    }

    // Verify old password
    public function verifyPassword($requester_email, $oldPassword)
    {
        require_once __DIR__ . '/../config/encryption.php';
        $encrypted_email = encrypt($requester_email);
        $stmt = $this->db->prepare("SELECT pass FROM requester WHERE email = ?");
        $stmt->bind_param("s", $encrypted_email);
        $stmt->execute();
        $stmt->bind_result($encryptedPassword);
        $stmt->fetch();
        $stmt->close();

        return decrypt($encryptedPassword) === $oldPassword;
    }

    // // Delete account
    //     public function deleteAccount($requester_email) {
    //     $stmt = $this->db->prepare("DELETE FROM requester WHERE email = ?");
    //     $stmt->bind_param("s", $requester_email);
    //     return $stmt->execute();
    // }   
}
