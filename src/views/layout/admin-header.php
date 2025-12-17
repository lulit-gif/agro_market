<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

$pageClass = $pageClass ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Admin - Agro Market'); ?></title>
    <meta name="description" content="Agro Market Admin">
    <meta name="csrf-token" content="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES); ?>">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="admin">
    <header class="navbar">
        <div class="nav-inner">
            <a href="/admin/dashboard">
                <span>🛠️</span>
                <strong>Agro Admin</strong>
            </a>
            <nav>
                <a href="/admin/dashboard">Dashboard</a>
                <a href="/admin/users">Users</a>
                <a href="/admin/farmers">Farmers</a>
                <a href="/admin/products">Products</a>
                <a href="/admin/orders">Orders</a>
            </nav>
            <div>
                <form method="post" action="/admin/logout" style="display:inline;">
                    <button class="outline" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </header>
    <main class="container">
