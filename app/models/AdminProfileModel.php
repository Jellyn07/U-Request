<?php
require_once __DIR__ . '/../core/BaseModel.php';

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


    // Update password with encryption
    public function updatePassword($requester_email, $newPassword)
    {
        require_once __DIR__ . '/../config/encryption.php';
        $encryptedPassword = encrypt($newPassword);

        $stmt = $this->db->prepare("
            UPDATE administrator 
            SET password = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $encryptedPassword, $requester_email);
        return $stmt->execute();
    }

    // Verify old password
    public function verifyPassword($requester_email, $oldPassword)
    {
        require_once __DIR__ . '/../config/encryption.php';
        $stmt = $this->db->prepare("SELECT password FROM administrator WHERE email = ?");
        $stmt->bind_param("s", $requester_email);
        $stmt->execute();
        $stmt->bind_result($encryptedPassword);
        $stmt->fetch();
        $stmt->close();

        return decrypt($encryptedPassword) === $oldPassword;
    }


    // Delete account
    public function deleteAccount($requester_email)
    {
        $stmt = $this->db->prepare("DELETE FROM administrator WHERE email = ?");
        $stmt->bind_param("s", $requester_email);
        return $stmt->execute();
    }
}
