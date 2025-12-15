<?php

/**
 * URL to controller mappings or (simple router)
 *
 * What this file should do:
 * - Read the current request path and HTTP method.
 * - Match the path to the correct controller and method.
 * - Call that controller method.
 * - Keep the routing table easy to read and also change.
 */

// Include all controllers you need
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../controllers/CheckoutController.php';
require_once __DIR__ . '/../controllers/FarmerController.php';
require_once __DIR__ . '/../controllers/BuyerController.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../controllers/ReviewController.php';

// Get current path ( it's without query string)
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Simple routing using if/elseif (basic and clear)

// Home pages
if ($uri === '/' && $method === 'GET') {
    HomeController::index();
} elseif ($uri === '/about' && $method === 'GET') {
    HomeController::about();
} elseif ($uri === '/contact' && $method === 'GET') {
    HomeController::contact();

    // Auth
} elseif ($uri === '/login' && $method === 'GET') {
    AuthController::showLoginForm();
} elseif ($uri === '/login' && $method === 'POST') {
    AuthController::handleLogin();
} elseif ($uri === '/register' && $method === 'GET') {
    AuthController::showRegisterForm();
} elseif ($uri === '/register' && $method === 'POST') {
    AuthController::handleRegister();
} elseif ($uri === '/logout' && $method === 'GET') {
    AuthController::logout();

    // Forgot / reset password
} elseif ($uri === '/forgot-password' && $method === 'GET') {
    AuthController::showForgotForm();
} elseif ($uri === '/forgot-password' && $method === 'POST') {
    AuthController::handleForgot();
} elseif ($uri === '/reset-password' && $method === 'GET') {
    AuthController::showResetForm();
} elseif ($uri === '/reset-password' && $method === 'POST') {
    AuthController::handleReset();

    // Products
} elseif ($uri === '/products' && $method === 'GET') {
    ProductController::index();
} elseif ($uri === '/product' && $method === 'GET') {
    ProductController::show();

    // Cart
} elseif ($uri === '/cart' && $method === 'GET') {
    CartController::show();
} elseif ($uri === '/cart/add' && $method === 'POST') {
    CartController::add();
} elseif ($uri === '/cart/update' && $method === 'POST') {
    CartController::update();

    // Checkout
} elseif ($uri === '/checkout' && $method === 'GET') {
    CheckoutController::show();
} elseif ($uri === '/checkout/submit' && $method === 'POST') {
    CheckoutController::submit();
} elseif ($uri === '/checkout/confirm' && $method === 'GET') {
    CheckoutController::confirm();

    // Farmer
} elseif ($uri === '/farmer/dashboard' && $method === 'GET') {
    FarmerController::dashboard();
} elseif ($uri === '/farmer/product/new' && $method === 'GET') {
    FarmerController::showCreateForm();
} elseif ($uri === '/farmer/product/create' && $method === 'POST') {
    FarmerController::create();

    // Buyer
} elseif ($uri === '/buyer/profile' && $method === 'GET') {
    BuyerController::profile();
} elseif ($uri === '/buyer/profile/update' && $method === 'POST') {
    BuyerController::updateProfile();
} elseif ($uri === '/buyer/orders' && $method === 'GET') {
    BuyerController::orders();

    // Admin
} elseif ($uri === '/admin/login' && $method === 'GET') {
    AdminController::showLoginForm();
} elseif ($uri === '/admin/login' && $method === 'POST') {
    AdminController::login();
} elseif ($uri === '/admin/logout' && $method === 'GET') {
    AdminController::logout();
} elseif ($uri === '/admin/dashboard' && $method === 'GET') {
    AdminController::dashboard();
} elseif ($uri === '/admin/users' && $method === 'GET') {
    AdminController::users();
} elseif ($uri === '/admin/products' && $method === 'GET') {
    AdminController::products();

    // Reviews
} elseif ($uri === '/review/add' && $method === 'POST') {
    ReviewController::add();
} elseif ($uri === '/review/delete' && $method === 'POST') {
    ReviewController::delete();

    // 404 fallback
} else {
    http_response_code(404);
    echo '404 Not Found';
}
