<?php
require_once __DIR__ . '/../core/BaseModel.php';

class ProfileModel extends BaseModel {

    // Get profile data by requester_id
    public function getProfileByEmail($requester_email) {
        $stmt = $this->db->prepare("
            SELECT requester_id, firstName, lastName, middleInitial, email, officeOrDept, profile_pic
            FROM vw_requesters
            WHERE email = ?
        ");
        $stmt->bind_param("s", $requester_email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns single row
    }

    // Update department/office
    public function updateOfficeOrDept($email, $officeOrDept) {
        $stmt = $this->db->prepare("
            UPDATE requester
            SET officeOrDept = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $officeOrDept, $email);
        return $stmt->execute();
    }

    // Update profile picture
    public function updateProfilePicture($fileName, $filePath) {
    $sql = "
        UPDATE requester 
        SET profile_pic = ? 
        WHERE email = ?";
    $stmt = $this->db->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $this->db->error);
    }
    $stmt->bind_param("ss", $filePath, $fileName);
    return $stmt->execute();
    }

    // Update password with encryption
    public function updatePassword($requester_email, $newPassword) {
        require_once __DIR__ . '/../config/encryption.php';
        $encryptedPassword = encrypt($newPassword);

        $stmt = $this->db->prepare("
            UPDATE requester 
            SET pass = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $encryptedPassword, $requester_email);
        return $stmt->execute();
    }

    // Verify old password
    public function verifyPassword($requester_email, $oldPassword) {
        require_once __DIR__ . '/../config/encryption.php';
        $stmt = $this->db->prepare("SELECT pass FROM requester WHERE email = ?");
        $stmt->bind_param("s", $requester_email);
        $stmt->execute();
        $stmt->bind_result($encryptedPassword);
        $stmt->fetch();
        $stmt->close();

        return decrypt($encryptedPassword) === $oldPassword;
    }


    // Delete account
        public function deleteAccount($requester_email) {
        $stmt = $this->db->prepare("DELETE FROM requester WHERE email = ?");
        $stmt->bind_param("s", $requester_email);
        return $stmt->execute();
    }   
}