<?php
/**
 * URL to controller mappings (simple router)
 *
 * What this file should do:
 * - Read the current request path and HTTP method.
 * - Match the path to the correct controller and method.
 * - Call that controller method.
 * - Keep the routing table easy to read and change.
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
// Reviews removed: controller and routes deleted as unused



//


//


// Get current path (without query string)
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


// ========================
// FARMER ROUTES (COMPLETE)
// ========================

} elseif ($uri === '/farmer/dashboard' && $method === 'GET') {
    FarmerController::dashboard();

// Add Product
} elseif ($uri === '/farmer/product/new' && $method === 'GET') {
    FarmerController::addProductForm();
} elseif ($uri === '/farmer/product/add' && $method === 'POST') {
    FarmerController::addProduct();

// Edit Product
} elseif ($uri === '/farmer/product/edit' && $method === 'GET') {
    FarmerController::editProductForm();
} elseif ($uri === '/farmer/product/edit' && $method === 'POST') {
    FarmerController::editProduct();

// Delete Product
} elseif ($uri === '/farmer/product/delete' && $method === 'POST') {
    FarmerController::deleteProduct();

// Farmer Orders
} elseif ($uri === '/farmer/orders' && $method === 'GET') {
    FarmerController::orders();

// View Order Detail
} elseif ($uri === '/farmer/order/view' && $method === 'GET') {
    FarmerController::viewOrder();


// ========================
// ADMIN ROUTES (COMPLETE)
// ========================

// Admin Login
} elseif ($uri === '/admin/login' && $method === 'GET') {
    AdminController::loginForm();
} elseif ($uri === '/admin/login' && $method === 'POST') {
    AdminController::login();

// Admin Logout
} elseif ($uri === '/admin/logout' && $method === 'POST') {
    AdminController::logout();

// Admin Dashboard
} elseif ($uri === '/admin/dashboard' && $method === 'GET') {
    AdminController::dashboard();

// Manage Users
} elseif ($uri === '/admin/users' && $method === 'GET') {
    AdminController::users();

// Manage Products
} elseif ($uri === '/admin/products' && $method === 'GET') {
    AdminController::products();

// Approve Product
} elseif ($uri === '/admin/product/approve' && $method === 'POST') {
    AdminController::approveProduct();

// Reject Product
} elseif ($uri === '/admin/product/reject' && $method === 'POST') {
    AdminController::rejectProduct();

// Manage Orders
} elseif ($uri === '/admin/orders' && $method === 'GET') {
    AdminController::orders();
} elseif ($uri === '/admin/order/view' && $method === 'GET') {
    AdminController::viewOrder();

// Update Order Status
} elseif ($uri === '/admin/order/update-status' && $method === 'POST') {
    AdminController::updateOrderStatus();

// Farmers Management
} elseif ($uri === '/admin/farmers' && $method === 'GET') {
    AdminController::farmers();
} elseif ($uri === '/admin/farmers/new' && $method === 'GET') {
    AdminController::newFarmerForm();
} elseif ($uri === '/admin/farmers/create' && $method === 'POST') {
    AdminController::createFarmer();


// Buyer
} elseif ($uri === '/buyer/profile' && $method === 'GET') {
    BuyerController::profile();
} elseif ($uri === '/buyer/profile/update' && $method === 'POST') {
    BuyerController::updateProfile();
} elseif ($uri === '/buyer/orders' && $method === 'GET') {
    BuyerController::orders();


// 404 fallback
} else {
    http_response_code(404);
    require __DIR__ . '/../views/404.php';
}
?>
