<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../model/Medicine.php';
require_once __DIR__ . '/../model/Category.php';

class HomeController extends BaseController {
    private $medicineModel;
    private $categoryModel;
    
    public function __construct() {
        $this->medicineModel = new Medicine();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $categories = $this->categoryModel->getAll();
        $medicines = $this->medicineModel->getAll();
        $this->view('home', ['categories' => $categories, 'medicines' => $medicines]);
    }
    
   public function searchAjax() {
    $filters = [
        'q' => $_GET['q'] ?? '',
        'vendor' => $_GET['vendor'] ?? '',
        'category_id' => $_GET['category_id'] ?? '',
        'category_type' => $_GET['category_type'] ?? ''
    ];
    $medicines = $this->medicineModel->search($filters);
    $this->json([
        'success' => true,
        'medicines' => $medicines,
        'isLoggedIn' => isset($_SESSION['user_id'])   // ← THIS LINE IS CRUCIAL
    ]);
}
}
?>