<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../model/Cart.php';
require_once __DIR__ . '/../model/Order.php';
require_once __DIR__ . '/../model/Payment.php';
require_once __DIR__ . '/../model/Medicine.php';
require_once __DIR__ . '/../model/User.php';

class OrderController extends BaseController {
    private $cartModel, $orderModel, $paymentModel, $medicineModel, $userModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->cartModel = new Cart();
        $this->orderModel = new Order();
        $this->paymentModel = new Payment();
        $this->medicineModel = new Medicine();
        $this->userModel = new User();
    }
    
    public function checkout() {
        $cartItems = $this->cartModel->getUserCart($_SESSION['user_id']);
        if (empty($cartItems)) $this->redirect('controller=cart&action=index');
        $user = $this->userModel->findById($_SESSION['user_id']);
        $total = array_sum(array_map(fn($i)=>$i['price']*$i['quantity'], $cartItems));
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['step'] === 'address') {
                $_SESSION['checkout_address'] = $_POST['shipping_address'];
                $this->view('invoice', ['cartItems' => $cartItems, 'total' => $total, 'address' => $_SESSION['checkout_address']]);
            } elseif ($_POST['step'] === 'payment') {
                foreach ($cartItems as $item) {
                    if (!$this->medicineModel->checkStock($item['id'], $item['quantity'])) {
                        $error = "Insufficient stock for {$item['name']}";
                        $this->view('checkout', ['cartItems' => $cartItems, 'total' => $total, 'user' => $user, 'error' => $error]);
                        return;
                    }
                }
                $orderId = $this->orderModel->create($_SESSION['user_id'], $total, $_SESSION['checkout_address'], $_POST['payment_method'], $cartItems);
                foreach ($cartItems as $item) $this->medicineModel->updateStock($item['id'], $item['quantity']);
                $this->paymentModel->create($orderId, $total, $_POST['payment_method'], 'TXN_'.time().'_'.$orderId);
                $this->cartModel->clearCart($_SESSION['user_id']);
                unset($_SESSION['checkout_address']);
                $order = $this->orderModel->getOrderWithItems($orderId);
                $this->view('success', ['order' => $order]);
            }
        } else {
            $this->view('checkout', ['cartItems' => $cartItems, 'total' => $total, 'user' => $user]);
        }
    }
}
?>