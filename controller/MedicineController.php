<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Medicine.php';
// No Category model required now

class MedicineController extends BaseController {
    private $medicineModel;
    
    public function __construct() {
        $this->requireAdmin();
        $this->medicineModel = new Medicine();
    }
    
    public function index() {
        $medicines = $this->medicineModel->getAll();
        $this->view('medicine_list', ['medicines' => $medicines]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateMedicine($_POST, $_FILES);
            
            if (empty($errors)) {
                $imagePath = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $imagePath = $this->uploadImage($_FILES['image']);
                }
                
                $data = [
                    'name' => $_POST['name'],
                    'category_id' => $_POST['category_id'],
                    'vendor_name' => $_POST['vendor_name'],
                    'price' => $_POST['price'],
                    'availability' => $_POST['availability'],
                    'description' => $_POST['description'],
                    'image_path' => $imagePath
                ];
                
                if ($this->medicineModel->create($data)) {
                    $this->redirect('controller=medicine&action=index');
                    return;
                } else {
                    $errors[] = "Failed to create medicine";
                }
            }
            
            $this->view('medicine_create', ['errors' => $errors, 'old' => $_POST]);
        } else {
            $this->view('medicine_create');
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $medicine = $this->medicineModel->findById($id);
        if (!$medicine) {
            $this->redirect('controller=medicine&action=index');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->validateMedicine($_POST, $_FILES, true);
            
            $data = [
                'name' => $_POST['name'],
                'category_id' => $_POST['category_id'],
                'vendor_name' => $_POST['vendor_name'],
                'price' => $_POST['price'],
                'availability' => $_POST['availability'],
                'description' => $_POST['description']
            ];
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $data['image_path'] = $this->uploadImage($_FILES['image']);
            }
            
            if (empty($errors) && $this->medicineModel->update($id, $data)) {
                $this->redirect('controller=medicine&action=index');
                return;
            }
            
            $this->view('medicine_edit', ['medicine' => $medicine, 'errors' => $errors, 'old' => $_POST]);
        } else {
            $this->view('medicine_edit', ['medicine' => $medicine]);
        }
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        $this->medicineModel->delete($id);
        $this->redirect('controller=medicine&action=index');
    }
    
    private function validateMedicine($data, $files, $isEdit = false) {
        $errors = [];
        if (empty($data['name'])) $errors[] = "Medicine name is required";
        if (empty($data['category_id']) || !is_numeric($data['category_id'])) $errors[] = "Valid category ID is required";
        if (empty($data['vendor_name'])) $errors[] = "Vendor name is required";
        if (empty($data['price']) || $data['price'] <= 0) $errors[] = "Valid price is required";
        if (!isset($data['availability']) || $data['availability'] < 0) $errors[] = "Valid stock quantity is required";
        
        if (!$isEdit && (!isset($files['image']) || $files['image']['error'] !== UPLOAD_ERR_OK)) {
            $errors[] = "Medicine image is required";
        }
        
        if (isset($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($files['image']['type'], $allowed)) {
                $errors[] = "Only JPG, JPEG, PNG images are allowed";
            }
            if ($files['image']['size'] > 2 * 1024 * 1024) {
                $errors[] = "Image size must be less than 2MB";
            }
        }
        
        return $errors;
    }
    
    private function uploadImage($file) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'med_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $destination = __DIR__ . '/../uploads/medicines/' . $filename;
        move_uploaded_file($file['tmp_name'], $destination);
        return $filename;
    }
}
?>