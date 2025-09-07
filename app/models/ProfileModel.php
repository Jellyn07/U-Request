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
    public function updateOfficeOrDept($requester_email, $officeOrDept) {
        $stmt = $this->db->prepare("
            UPDATE requester 
            SET officeOrDept = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $officeOrDept, $requester_email);
        return $stmt->execute();
    }

    // Update profile picture
    public function updateProfilePicture($requester_email, $filePath) {
        $stmt = $this->db->prepare("
            UPDATE requester 
            SET profile_pic = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $filePath, $requester_email);
        return $stmt->execute();
    }

    // Update password
    public function updatePassword($requester_email, $hashedPassword) {
        $stmt = $this->db->prepare("
            UPDATE requester 
            SET password = ?
            WHERE email = ?
        ");
        $stmt->bind_param("ss", $hashedPassword, $requester_email);
        return $stmt->execute();
    }

    // Delete account
        public function deleteAccount($requester_email) {
        $stmt = $this->db->prepare("DELETE FROM requester WHERE email = ?");
        $stmt->bind_param("s", $requester_email);
        return $stmt->execute();
    }
}
