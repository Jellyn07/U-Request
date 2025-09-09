<?php
require_once __DIR__ . '/../core/BaseModel.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/encryption.php';
require_once __DIR__ . '/../controllers/ProfileController.php';

class ProfileModel extends BaseModel {

    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "utrms_db"); // change DB name
        if ($this->db->connect_error) {
            die("DB Connection failed: " . $this->db->connect_error);
        }
    }

    // Get profile data by requester_id
    public function getProfileByEmail($requester_email) {
    $stmt = $this->db->prepare("
        SELECT requester_id, firstName, lastName, middleInitial, email, officeOrDept, profile_pic
        FROM requester
        WHERE email = ?
    ");
    $stmt->bind_param("s", $requester_email);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }

    // Update department/office
    public function updateOfficeOrDept($officeOrDept, $requester_email) {
    $stmt = $this->db->prepare("UPDATE requester SET officeOrDept = ? WHERE email = ?");
    $stmt->bind_param("ss", $officeOrDept, $requester_email);
    return $stmt->execute();
    }

    // Update profile picture
    public function updateProfilePicture($requester_email, $filePath) {
        $stmt = $this->db->prepare("
            UPDATE requester SET 
            profile_pic = ? 
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $filePath, $requester_email);
        return $stmt->execute();
    }

    //Update Password
    public function savePassword($email, $oldPassword, $newPassword) {
        // 1. Fetch current password hash
        $stmt = $this->db->prepare("SELECT password FROM requester WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($currentHash);

        if (!$stmt->fetch()) {
            $stmt->close();
            return false; // no user found
        }
        $stmt->close();

        // 2. Verify old password
        if (!password_verify($oldPassword, $currentHash)) {
            return false;
        }

        // 3. Prevent reusing same password
        if (password_verify($newPassword, $currentHash)) {
            return false; // new password = old password
        }

        // 4. Hash new password
        $hashedNew = password_hash($newPassword, PASSWORD_DEFAULT);

        // 5. Update password
        return $this->updatePassword($email, $hashedNew);
    }

    public function updatePassword($email, $encryptedPassword) {
        $stmt = $this->db->prepare("UPDATE requester SET password = ? WHERE email = ?");
        if (!$stmt) {
            error_log("DB prepare failed: " . $this->db->error);
            return false;
        }

        $stmt->bind_param("ss", $encryptedPassword, $email);

        if (!$stmt->execute()) {
            error_log("DB Error: " . $stmt->error);
            $stmt->close();
            return false;
        }

        $rows = $stmt->affected_rows;
        $stmt->close();

        return $rows > 0;
    }
}