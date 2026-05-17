<?php
require_once 'Database.php';

class Medicine {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO medicines (name, category_id, vendor_name, price, availability, description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisdiss", $data['name'], $data['category_id'], $data['vendor_name'], $data['price'], $data['availability'], $data['description'], $data['image_path']);
        return $stmt->execute();
    }
    
    public function update($id, $data) {
        if (isset($data['image_path'])) {
            $stmt = $this->db->prepare("UPDATE medicines SET name=?, category_id=?, vendor_name=?, price=?, availability=?, description=?, image_path=? WHERE id=?");
            $stmt->bind_param("sisdissi", $data['name'], $data['category_id'], $data['vendor_name'], $data['price'], $data['availability'], $data['description'], $data['image_path'], $id);
        } else {
            $stmt = $this->db->prepare("UPDATE medicines SET name=?, category_id=?, vendor_name=?, price=?, availability=?, description=? WHERE id=?");
            $stmt->bind_param("sisdisi", $data['name'], $data['category_id'], $data['vendor_name'], $data['price'], $data['availability'], $data['description'], $id);
        }
        return $stmt->execute();
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM medicines WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT m.*, c.name as category_name, c.category_type FROM medicines m JOIN categories c ON m.category_id=c.id WHERE m.id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAll() {
        $result = $this->db->query("SELECT m.*, c.name as category_name, c.category_type FROM medicines m JOIN categories c ON m.category_id=c.id ORDER BY m.created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function search($filters) {
        $sql = "SELECT m.*, c.name as category_name, c.category_type FROM medicines m JOIN categories c ON m.category_id=c.id WHERE 1=1";
        $params = [];
        $types = "";
        $values = [];
        
        if (!empty($filters['q'])) {
            $sql .= " AND m.name LIKE ?";
            $params[] = "%{$filters['q']}%";
            $types .= "s";
        }
        if (!empty($filters['vendor'])) {
            $sql .= " AND m.vendor_name LIKE ?";
            $params[] = "%{$filters['vendor']}%";
            $types .= "s";
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND m.category_id = ?";
            $params[] = $filters['category_id'];
            $types .= "i";
        }
        if (!empty($filters['category_type'])) {
            $sql .= " AND c.category_type = ?";
            $params[] = $filters['category_type'];
            $types .= "s";
        }
        $sql .= " ORDER BY m.name ASC";
        
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function count() {
        $result = $this->db->query("SELECT COUNT(*) as cnt FROM medicines");
        return $result->fetch_assoc()['cnt'];
    }
    
    public function updateStock($id, $quantity) {
        $stmt = $this->db->prepare("UPDATE medicines SET availability = availability - ? WHERE id=? AND availability >= ?");
        $stmt->bind_param("iii", $quantity, $id, $quantity);
        return $stmt->execute();
    }
    
    public function checkStock($id, $quantity) {
        $stmt = $this->db->prepare("SELECT availability FROM medicines WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row && $row['availability'] >= $quantity;
    }
}
?>