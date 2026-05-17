<?php
require_once 'Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("ssssss", $data['name'], $data['email'], $hashed, $data['role'], $data['address'], $data['phone']);
        return $stmt->execute();
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function updateProfile($id, $name, $address, $phone, $email, $picture = null) {
    if ($picture) {
        $stmt = $this->db->prepare("UPDATE users SET name=?, address=?, phone=?, email=?, profile_picture=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $address, $phone, $email, $picture, $id);
    } else {
        $stmt = $this->db->prepare("UPDATE users SET name=?, address=?, phone=?, email=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $address, $phone, $email, $id);
    }
    return $stmt->execute();
}
    
    public function updatePassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password_hash=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $id);
        return $stmt->execute();
    }
    
    public function updateRememberToken($id, $token) {
        $stmt = $this->db->prepare("UPDATE users SET remember_token=? WHERE id=?");
        $stmt->bind_param("si", $token, $id);
        return $stmt->execute();
    }
    
    public function findByRememberToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE remember_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAllCustomers() {
        $result = $this->db->query("SELECT * FROM users WHERE role='customer' ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function deleteCustomer($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id=? AND role='customer'");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function countCustomers() {
        $result = $this->db->query("SELECT COUNT(*) as cnt FROM users WHERE role='customer'");
        return $result->fetch_assoc()['cnt'];
    }
}
?>