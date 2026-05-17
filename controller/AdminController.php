<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Order.php';
require_once __DIR__ . '/../model/Medicine.php';
require_once __DIR__ . '/../model/Category.php';

class AdminController extends BaseController {
    private $userModel, $orderModel, $medicineModel, $categoryModel;
    
    public function __construct() {
        $this->requireAdmin();
        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->medicineModel = new Medicine();
        $this->categoryModel = new Category();
    }
    
    public function dashboard() {
        $stats = [
            'medicines' => $this->medicineModel->count(),
            'categories' => $this->categoryModel->count(),
            'customers' => $this->userModel->countCustomers(),
            'pending_orders' => $this->orderModel->countPending()
        ];
        $this->view('dashboard', ['stats' => $stats]);
    }
    
    public function customers() {
        $customers = $this->userModel->getAllCustomers();
        $this->view('customers', ['customers' => $customers]);
    }
    
    public function deleteCustomer() {
        $id = $_GET['id'];
        $this->userModel->deleteCustomer($id);
        $this->redirect('controller=admin&action=customers');
    }
    
    public function purchaseRequests() {
        $orders = $this->orderModel->getAllOrders();
        $this->view('purchase_requests', ['orders' => $orders]);
    }
    
    public function updateOrderStatusAjax() {
        $orderId = $_POST['order_id'];
        $status = $_POST['status'];
        $result = $this->orderModel->updateStatus($orderId, $status);
        $this->json(['success' => $result]);
    }
    
    public function history() {
        $orders = $this->orderModel->getCompletedOrders();
        $this->view('history', ['orders' => $orders]);
    }
}
?>