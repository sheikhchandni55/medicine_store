<?php


class BaseController {
    protected function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../view/header.php';
        require_once __DIR__ . "/../view/{$view}.php";
        require_once __DIR__ . '/../view/footer.php';
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        header("Location: index.php?{$url}");
        exit;
    }
    
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    protected function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
    
    protected function requireLogin() {
        if (!$this->isLoggedIn()) $this->redirect('controller=auth&action=login');
    }
    
    protected function requireAdmin() {
        if (!$this->isAdmin()) $this->redirect('controller=home&action=index');
    }
}
?>