<?php
require_once 'Database.php';

class Payment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($orderId, $amount, $method, $transactionId) {
        $stmt = $this->db->prepare("INSERT INTO payments (order_id, amount, payment_method, transaction_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $orderId, $amount, $method, $transactionId);
        return $stmt->execute();
    }
}
?>