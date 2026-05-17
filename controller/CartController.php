<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Cart.php';
require_once __DIR__ . '/../model/Medicine.php';

class CartController extends BaseController {
    private $cartModel;
    private $medicineModel;
    
    public function __construct() {
        $this->requireLogin();  // ensure user is logged in
        $this->cartModel = new Cart();
        $this->medicineModel = new Medicine();
    }
     public function index() {
        $cartItems = $this->cartModel->getUserCart($_SESSION['user_id']);
        $total = array_sum(array_map(fn($i)=>$i['price']*$i['quantity'], $cartItems));
        $this->view('cart', ['cartItems' => $cartItems, 'total' => $total]);
    }
    
    // ✅ ADD THIS METHOD HERE
    public function addAjax() {
        $medicineId = $_POST['medicine_id'] ?? 0;
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if (!$this->medicineModel->checkStock($medicineId, $quantity)) {
            $this->json(['success' => false, 'error' => 'Insufficient stock']);
            return;
        }
        
        $result = $this->cartModel->addItem($_SESSION['user_id'], $medicineId, $quantity);
        $count = $this->cartModel->getCartCount($_SESSION['user_id']);
        $this->json(['success' => $result, 'cart_count' => $count]);
    }
    
    public function updateAjax() {
        $cartId = $_POST['cart_id'];
        $quantity = (int)$_POST['quantity'];
        $result = $this->cartModel->updateQuantity($cartId, $quantity);
        $cartItems = $this->cartModel->getUserCart($_SESSION['user_id']);
        $total = array_sum(array_map(fn($i)=>$i['price']*$i['quantity'], $cartItems));
        $this->json(['success' => $result, 'total' => $total]);
    }
    
    public function removeAjax() {
        $cartId = $_POST['cart_id'];
        $result = $this->cartModel->removeItem($cartId);
        $count = $this->cartModel->getCartCount($_SESSION['user_id']);
        $this->json(['success' => $result, 'cart_count' => $count]);
    }
    
    public function getCountAjax() {
        $count = $this->cartModel->getCartCount($_SESSION['user_id']);
        $this->json(['cart_count' => $count]);
    }
}
?>