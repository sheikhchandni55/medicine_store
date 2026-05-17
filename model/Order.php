<?php
require_once 'Database.php';

class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($userId, $total, $address, $paymentMethod, $cartItems) {
        $this->db->begin_transaction();
        try {
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmt->bind_param("idss", $userId, $total, $address, $paymentMethod);
            $stmt->execute();
            $orderId = $this->db->insert_id;
            
            $itemStmt = $this->db->prepare("INSERT INTO order_items (order_id, medicine_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                $itemStmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
                $itemStmt->execute();
            }
            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    public function updateStatus($orderId, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $orderId);
        return $stmt->execute();
    }
    
    public function getUserOrders($userId) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY order_date DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAllOrders() {
        $result = $this->db->query("SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.order_date DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getOrderWithItems($orderId) {
        $stmt = $this->db->prepare("SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id=u.id WHERE o.id=?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        if ($order) {
            $stmt2 = $this->db->prepare("SELECT oi.*, m.name as medicine_name FROM order_items oi JOIN medicines m ON oi.medicine_id=m.id WHERE oi.order_id=?");
            $stmt2->bind_param("i", $orderId);
            $stmt2->execute();
            $order['items'] = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        return $order;
    }
    
    public function getCompletedOrders() {
        $result = $this->db->query("SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id=u.id WHERE o.status='accepted' ORDER BY o.order_date DESC");
        $orders = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($orders as &$order) {
            $stmt = $this->db->prepare("SELECT oi.*, m.name as medicine_name FROM order_items oi JOIN medicines m ON oi.medicine_id=m.id WHERE oi.order_id=?");
            $stmt->bind_param("i", $order['id']);
            $stmt->execute();
            $order['items'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        return $orders;
    }
    
    public function countPending() {
        $result = $this->db->query("SELECT COUNT(*) as cnt FROM orders WHERE status='pending'");
        return $result->fetch_assoc()['cnt'];
    }
}
?>