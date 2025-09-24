<?php
require_once __DIR__ . '/../core/BaseModel.php';

class MaterialModel extends BaseModel {
    private $table = "materials";

    //mother division
    public function getFilteredMaterials($search = '', $status = 'all', $order = 'az') {
        $sql = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $params = [];
        $types = "";

        // Search
        if (!empty($search)) {
            $sql .= " AND material_desc LIKE ?";
            $params[] = "%$search%";
            $types .= "s";
        }

        // Status
        if ($status !== 'all') {
            $sql .= " AND material_status = ?";
            $params[] = $status;
            $types .= "s";
        }

        // Sorting
        $direction = ($order === 'za') ? "DESC" : "ASC";
        $sql .= " ORDER BY material_desc $direction";

        $stmt = $this->db->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


    // Fetch all materials
    public function getAll() {
        $sql = "SELECT * FROM " . $this->table;
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Fetch one material
    public function search($keyword) {
    $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " 
                                WHERE material_desc LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $keyword);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Insert material
    public function addmaterial($code, $description, $quantity, $status) {
        $stmt = $this->db->prepare("
            INSERT INTO " . $this->table . " (material_code, material_desc, qty, material_status) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("ssis", $code, $description, $quantity, $status);
        return $stmt->execute();
    }

    //filter status
    public function filterByStatus($status) {
        if ($status === "all") {
            $sql = "SELECT * FROM " . $this->table;
            $result = $this->db->query($sql);
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        } else {
            $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE material_status = ?");
            $stmt->bind_param("s", $status);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
    }

    //sort a-z vice versa
    public function sortByName($order = "az") {
    $direction = ($order === "za") ? "DESC" : "ASC";

    $sql = "SELECT * FROM " . $this->table . " ORDER BY material_desc $direction";
    $result = $this->db->query($sql);

    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Update material
    public function update($code, $desc, $qty, $status) {
        $sql = "UPDATE materials 
                SET material_desc = ?, qty = ?, material_status = ? 
                WHERE material_code = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("siss", $desc, $qty, $status, $code);
        return $stmt->execute();
    }


    // Fetch single material (for details panel if needed)
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM " . $this->table . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Check if material_code or material_desc already exists
    public function exists($code, $description) {
        // Check duplicate material_code
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM " . $this->table . " WHERE material_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result['count'] > 0) {
            return "code"; // duplicate code
        }
    
        // Check duplicate material_desc
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM " . $this->table . " WHERE material_desc = ?");
        $stmt->bind_param("s", $description);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result['count'] > 0) {
            return "description"; // duplicate description
        }
    
        return false; // no duplicate
    }

    public function existsForUpdate($code, $description, $currentId) {
        // Check duplicate code excluding current record
        $stmt = $this->db->prepare("SELECT COUNT(*) as count 
                                    FROM " . $this->table . " 
                                    WHERE material_code = ? AND material_code != ?");
        $stmt->bind_param("ss", $code, $currentId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result['count'] > 0) {
            return "code";
        }
    
        // Check duplicate description excluding current record
        $stmt = $this->db->prepare("SELECT COUNT(*) as count 
                                    FROM " . $this->table . " 
                                    WHERE material_desc = ? AND material_code != ?");
        $stmt->bind_param("ss", $description, $currentId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result['count'] > 0) {
            return "description";
        }
    
        return false;
    }
    
    


}