<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../model/User.php';

class ProfileController extends BaseController {
    private $userModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->userModel = new User();
    }
    
    public function index() {
        $user = $this->userModel->findById($_SESSION['user_id']);
        $this->view('profile', ['user' => $user]);
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $picture = null;
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg', 'image/png'];
                if (!in_array($_FILES['profile_picture']['type'], $allowed)) $errors[] = 'Only JPG/PNG';
                if ($_FILES['profile_picture']['size'] > 2*1024*1024) $errors[] = 'Max 2MB';
                if (empty($errors)) {
                    $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                    $picture = 'profile_'.$_SESSION['user_id'].'_'.time().".$ext";
                   $destination = __DIR__ . '/../uploads/profiles/' . $picture;
move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination);
                }
            }
            if (empty($errors)) {
                $this->userModel->updateProfile($_SESSION['user_id'], $_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email'], $picture);
                $_SESSION['name'] = $_POST['name'];
                if (!empty($_POST['new_password'])) {
                    if (strlen($_POST['new_password'])>=8 && $_POST['new_password']===$_POST['confirm_password']) {
                        $this->userModel->updatePassword($_SESSION['user_id'], $_POST['new_password']);
                    }
                }
                $this->redirect('controller=profile&action=index');
            } else {
                $user = $this->userModel->findById($_SESSION['user_id']);
                $this->view('profile', ['user' => $user, 'errors' => $errors]);
            }
        }
    }
}
?>