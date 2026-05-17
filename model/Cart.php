<?php
require_once 'Database.php';

class Cart {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function addItem($userId, $medicineId, $quantity) {
        // check if exists
        $check = $this->db->prepare("SELECT id, quantity FROM cart WHERE user_id=? AND medicine_id=?");
        $check->bind_param("ii", $userId, $medicineId);
        $check->execute();
        $existing = $check->get_result()->fetch_assoc();
        
        if ($existing) {
            $newQty = $existing['quantity'] + $quantity;
            return $this->updateQuantity($existing['id'], $newQty);
        } else {
            $stmt = $this->db->prepare("INSERT INTO cart (user_id, medicine_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $userId, $medicineId, $quantity);
            return $stmt->execute();
        }
    }
    
    public function updateQuantity($cartId, $quantity) {
        if ($quantity <= 0) return $this->removeItem($cartId);
        $stmt = $this->db->prepare("UPDATE cart SET quantity=? WHERE id=?");
        $stmt->bind_param("ii", $quantity, $cartId);
        return $stmt->execute();
    }
    
    public function removeItem($cartId) {
        $stmt = $this->db->prepare("DELETE FROM cart WHERE id=?");
        $stmt->bind_param("i", $cartId);
        return $stmt->execute();
    }
    
    public function getUserCart($userId) {
        $stmt = $this->db->prepare("SELECT c.id as cart_id, c.quantity, m.*, cat.category_type FROM cart c JOIN medicines m ON c.medicine_id=m.id JOIN categories cat ON m.category_id=cat.id WHERE c.user_id=?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getCartCount($userId) {
        $stmt = $this->db->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id=?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['total'] ?? 0;
    }
    
    public function clearCart($userId) {
        $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id=?");
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
?>