<?php
require_once __DIR__ . '/../models/ProfileModel.php';
require_once __DIR__ . '/../config/constants.php';

class ProfileController {
    private $model;

    public function __construct() {
        $this->model = new ProfileModel();
    }

    // Load profile info
        public function getProfile($requester_email) {
        return $this->model->getProfileByEmail($requester_email);
    }

    // Save department/office change
    public function saveOfficeOrDept($requester_email, $officeOrDept) {
        return $this->model->updateOfficeOrDept($requester_email, $officeOrDept);
    }

    // Save new profile picture
    public function saveProfilePicture($requester_email, $filePath) {
        return $this->model->updateProfilePicture($requester_email, $filePath);
    }

    // Save password update
    public function savePassword($requester_email, $oldPassword, $newPassword) {
        $profile = $this->model->getProfileByEmail($requester_email);

        // Verify old password
        if (!password_verify($oldPassword, $profile['password'])) {
            return false; // old password incorrect
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->model->updatePassword($requester_email, $hashedPassword);
    }

    // Delete account
    public function deleteAccount($requester_id) {
        return $this->model->deleteAccount($requester_id);
    }
}
