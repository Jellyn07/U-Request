<?php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/encryption.php';
require_once __DIR__ . '/../config/db_helpers.php';

class AdminProfileModel extends BaseModel{
    // Get profile data by email
    public function getProfileByEmail($admin_email){
        // Query using the encrypted value (DB stores encrypted email)
        $encrypted_email = encrypt($admin_email);
        $stmt = $this->db->prepare("
            SELECT staff_id, email, first_name, last_name, profile_picture
            FROM administrator
            WHERE email = ?
        ");
        $stmt->bind_param("s", $encrypted_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row && isset($row['email'])) {
            $row['email'] = decrypt($row['email']);
        }
        return $row;
    }

    // Update profile picture
    public function updateProfilePicture($email, $filePath){
        if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
        $encrypted_email = encrypt($email);
        $sql = "UPDATE administrator SET profile_picture = ? WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("ss", $filePath, $encrypted_email);
        return $stmt->execute();
    }

    // Update password (plain text)
    public function updatePassword($requester_email, $newPassword){
         if (isset($_SESSION['staff_id'])) {
            setCurrentStaff($this->db); // Use model's connection
        }
        $encryptedNewPass = encrypt($newPassword);
        $encrypted_email = encrypt($requester_email);
        $stmt = $this->db->prepare("
            UPDATE administrator 
            SET password = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $encryptedNewPass, $encrypted_email);
        return $stmt->execute();
    }

    // Verify old password (plain text)
    public function verifyPassword($requester_email, $oldPassword){
        $encrypted_email = encrypt($requester_email);
        $stmt = $this->db->prepare("SELECT password FROM administrator WHERE email = ?");
        $stmt->bind_param("s", $encrypted_email);
        $stmt->execute();
        $dbPassword = null;
        $stmt->bind_result($dbPassword);
        $stmt->fetch();
        $stmt->close();

        return decrypt($dbPassword) === $oldPassword;
    }
}