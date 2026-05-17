<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'controller/AuthController.php';
require_once 'controller/HomeController.php';
require_once 'controller/ProfileController.php';
require_once 'controller/MedicineController.php';
require_once 'controller/CategoryController.php';
require_once 'controller/CartController.php';
require_once 'controller/OrderController.php';
require_once 'controller/AdminController.php';

$auth = new AuthController();
$auth->checkRememberMe();

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

$map = [
    'auth' => 'AuthController',
    'home' => 'HomeController',
    'profile' => 'ProfileController',
    'medicine' => 'MedicineController',
    'category' => 'CategoryController',
    'cart' => 'CartController',
    'order' => 'OrderController',
    'admin' => 'AdminController'
];

if (isset($map[$controller])) {
    $obj = new $map[$controller]();
    if (method_exists($obj, $action)) {
        $obj->$action();
    } else {
        echo "Action not found";
    }
} else {
    echo "Controller not found";
}
?>