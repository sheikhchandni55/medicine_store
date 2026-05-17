<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Medicine Shop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>">
<nav class="navbar">
    <div class="container">
        <a href="index.php?controller=home&action=index" class="logo">💊 MediShop</a>
        <div class="nav-links">
            <a href="index.php?controller=home&action=index">🏠 Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?controller=profile&action=index">👤 Profile</a>
                <a href="index.php?controller=cart&action=index" class="cart-icon">
                    🛒 Cart <span id="cartCount" class="cart-count">0</span>
                </a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="index.php?controller=admin&action=dashboard">📊 Admin</a>
                    <a href="index.php?controller=medicine&action=index">💊 Medicines</a>
                    <a href="index.php?controller=category&action=index">📂 Categories</a>
                <?php endif; ?>
                <a href="index.php?controller=auth&action=logout">🚪 Logout</a>
            <?php else: ?>
                <a href="index.php?controller=auth&action=login">🔐 Login</a>
                <a href="index.php?controller=auth&action=register">📝 Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container main-container">