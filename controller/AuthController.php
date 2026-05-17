<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../model/User.php';

class AuthController extends BaseController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if ($this->isLoggedIn()) return $this->redirect('controller=home&action=index');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userModel->findByEmail($_POST['email']);
            if ($user && password_verify($_POST['password'], $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                  $this->redirect('controller=home&action=index'); 
                if (isset($_POST['remember'])) {
                    $token = bin2hex(random_bytes(32));
                    $this->userModel->updateRememberToken($user['id'], $token);
                    setcookie('remember_token', $token, time() + 86400 * 30, '/');
                }
                $this->redirect('controller=home&action=index');
            } else {
                $this->view('login', ['error' => 'Invalid credentials']);
            }
        } else {
            $this->view('login');
        }
    }
    
    public function register() {
        if ($this->isLoggedIn()) $this->redirect('controller=home&action=index');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            if (strlen($_POST['password']) < 8) $errors[] = 'Password must be 8+ chars';
            if ($_POST['password'] !== $_POST['confirm_password']) $errors[] = 'Passwords do not match';
            if ($this->userModel->findByEmail($_POST['email'])) $errors[] = 'Email already exists';
            if (empty($errors)) {
                $this->userModel->create($_POST);
                $this->redirect('controller=auth&action=login');
            } else {
                $this->view('register', ['errors' => $errors, 'old' => $_POST]);
            }
        } else {
            $this->view('register');
        }
    }
    
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->userModel->updateRememberToken($_SESSION['user_id'], null);
        }
        setcookie('remember_token', '', time() - 3600, '/');
        session_destroy();
        $this->redirect('controller=auth&action=login');
    }
    
    public function checkRememberMe() {
        if (!$this->isLoggedIn() && isset($_COOKIE['remember_token'])) {
            $user = $this->userModel->findByRememberToken($_COOKIE['remember_token']);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
            }
        }
    }
}
?>