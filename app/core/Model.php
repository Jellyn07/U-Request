<?php
class BaseModel {
    public $db;

    public function __construct() {
        $this->db = new mysqli("localhost", "root", "", "u_request");
        if ($this->db->connect_errno) {
            die("DB Connection failed: " . $this->db->connect_error);
        }
    }

    public function __destruct() {
        if ($this->db !== null) {
            $this->db->close();
        }
    }
}