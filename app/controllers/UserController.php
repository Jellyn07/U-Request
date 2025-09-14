<?php
require_once __DIR__ . '/../config/constants.php';

class UserController {
    private $conn;

    public function __construct() {
        // Create MySQL connection
        $this->conn = new mysqli('localhost', 'root', '', 'urequest_db'); // replace with your DB credentials
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Fetch all users
    public function getAllUsers() {
        $sql = "SELECT id, name, email, role FROM users ORDER BY id DESC";
        $result = $this->conn->query($sql);
        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    // Add new user
    public function addUser($name, $email, $role) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $role);
        $stmt->execute();
        $stmt->close();
    }

    // Edit existing user
    public function editUser($id, $name, $email, $role) {
        $stmt = $this->conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Delete user
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    // Optional: fetch single user by ID
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }
}
