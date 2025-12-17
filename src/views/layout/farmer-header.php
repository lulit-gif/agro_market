<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

$pageClass = $pageClass ?? 'farmer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Farmer - Agro Market'); ?></title>
    <meta name="description" content="Agro Market Farmer Area">
    <meta name="csrf-token" content="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES); ?>">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="farmer">
    <header class="navbar">
        <div class="nav-inner">
            <a href="/farmer/dashboard">
                <span>👨‍🌾</span>
                <strong>Farmer Panel</strong>
            </a>
            <nav>
                <a href="/farmer/dashboard">Dashboard</a>
                <a href="/farmer/product/new">Add Product</a>
                <a href="/farmer/orders">Orders</a>
            </nav>
            <div>
                <a href="/" class="btn btn-secondary" style="text-decoration:none;">Store</a>
                <a href="/logout" class="btn btn-outline" style="text-decoration:none;">Logout</a>
            </div>
        </div>
    </header>
    <main class="container">
