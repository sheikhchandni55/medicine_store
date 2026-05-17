<?php
require_once 'Database.php';

class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($name, $type) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, category_type) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $type);
        return $stmt->execute();
    }
    
    public function update($id, $name, $type) {
        $stmt = $this->db->prepare("UPDATE categories SET name=?, category_type=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $type, $id);
        return $stmt->execute();
    }
    
    public function delete($id) {
        // check if medicines exist
        $check = $this->db->prepare("SELECT COUNT(*) FROM medicines WHERE category_id=?");
        $check->bind_param("i", $id);
        $check->execute();
        if ($check->get_result()->fetch_row()[0] > 0) return false;
        
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAll() {
        $result = $this->db->query("SELECT * FROM categories ORDER BY name");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function count() {
        $result = $this->db->query("SELECT COUNT(*) as cnt FROM categories");
        return $result->fetch_assoc()['cnt'];
    }
}
?>