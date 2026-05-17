<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../model/Category.php';

class CategoryController extends BaseController {
    private $categoryModel;
    
    public function __construct() {
        $this->requireAdmin();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $categories = $this->categoryModel->getAll();
        $this->view('category_list', ['categories' => $categories]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->categoryModel->create($_POST['name'], $_POST['category_type']);
            $this->redirect('controller=category&action=index');
        } else {
            $this->view('category_create');
        }
    }
    
    public function edit() {
        $id = $_GET['id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->categoryModel->update($id, $_POST['name'], $_POST['category_type']);
            $this->redirect('controller=category&action=index');
        } else {
            $category = $this->categoryModel->findById($id);
            $this->view('category_create', ['category' => $category]);
        }
    }
    
    public function delete() {
        $id = $_GET['id'];
        if (!$this->categoryModel->delete($id)) {
            $_SESSION['error'] = 'Cannot delete category with medicines';
        }
        $this->redirect('controller=category&action=index');
    }
}
?>