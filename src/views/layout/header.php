<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

$pageClass = $pageClass ?? '';
$cartCount = 0;
if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cartCount += (int)$qty;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agro Market - Fresh Local Produce</title>
    <meta name="description" content="Fresh produce directly from local farmers">
    <meta name="csrf-token" content="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES); ?>">
    <link rel="stylesheet" href="/css/style.css">
    <?php if (in_array($pageClass, ['home', 'products'])): ?>
        <link rel="stylesheet" href="/css/home.css">
    <?php endif; ?>
</head>
<body class="<?php echo htmlspecialchars($pageClass, ENT_QUOTES); ?>">
    <header class="navbar">
        <div class="nav-inner">
            <a href="/">
                <span>🌱</span>
                <strong>Agro Market</strong>
            </a>
            
            <nav>
                <a href="/">Home</a>
                <a href="/products">Products</a>
                <a href="/about">About</a>
                <a href="/contact">Contact</a>
            </nav>
            
            <div>
                <?php if (empty($_SESSION['user_id'])): ?>
                    <button onclick="location.href='/register'">Signup</button>
                    <button class="outline" onclick="location.href='/login'">Login</button>
                <?php else: ?>
                    <button onclick="location.href='/buyer/profile'">Profile</button>
                    <button class="outline" onclick="location.href='/logout'">Logout</button>
                <?php endif; ?>
                
                <a href="/cart" style="color: white; font-weight: 700;">
                    🛒 <span id="cart-count"><?php echo $cartCount; ?></span>
                </a>
            </div>
        </div>
    </header>
    
    <main class="container">
