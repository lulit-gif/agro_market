<?php
// Layout file: header.php
// Outputs the standard HTML head and navigation used across views
session_start();
// Ensure a CSRF token exists in session
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Agro Market</title>
	<meta name="csrf-token" content="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES); ?>">
	<link rel="icon" href="/agro_market/public/img/hero.jpg" />
	<link rel="stylesheet" href="/agro_market/public/css/style.css">
</head>
<body>
	<header class="navbar">
		<div class="nav-inner">
			<div style="display:flex;align-items:center;gap:12px;">
				<button onclick="location.href='/agro_market/register'">Signup</button>
				<button onclick="location.href='/agro_market/login'">Login</button>
			</div>
			<div style="margin-left:auto;display:flex;align-items:center;gap:10px;">
				<a href="/agro_market/cart" aria-label="Cart">
					Cart <span id="cart-count">0</span>
				</a>
			</div>
		</div>
	</header>

	<main class="container">
		<!-- Page content starts here -->

