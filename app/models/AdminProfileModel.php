<?php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/encryption.php';

class AdminProfileModel extends BaseModel
{
    // Get profile data by email
    public function getProfileByEmail($admin_email)
    {
        $stmt = $this->db->prepare("
            SELECT staff_id, email, first_name, last_name, profile_picture
            FROM administrator
            WHERE email = ?
        ");
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns single row
    }

    // Update profile picture
    public function updateProfilePicture($email, $filePath)
    {
        $sql = "UPDATE administrator SET profile_picture = ? WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }
        $stmt->bind_param("ss", $filePath, $email);
        return $stmt->execute();
    }

    // Update password (plain text)
    public function updatePassword($requester_email, $newPassword)
    {
        $encryptedNewPass = encrypt($newPassword);
        $stmt = $this->db->prepare("
            UPDATE administrator 
            SET password = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $encryptedNewPass, $requester_email);
        return $stmt->execute();
    }

    // Verify old password (plain text)
    public function verifyPassword($requester_email, $oldPassword)
    {
        $stmt = $this->db->prepare("SELECT password FROM administrator WHERE email = ?");
        $stmt->bind_param("s", $requester_email);
        $stmt->execute();
        $stmt->bind_result($dbPassword);
        $stmt->fetch();
        $stmt->close();

        return decrypt($dbPassword) === $oldPassword;
    }
}