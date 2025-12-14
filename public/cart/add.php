<?php
header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// For demo, product_id can be passed as form field or query
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : (isset($_GET['product_id']) ? intval($_GET['product_id']) : 0);
$qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid product id']);
    exit;
}

// Initialize simple cart in session
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// increment quantity
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $qty;
} else {
    $_SESSION['cart'][$product_id] = $qty;
}

// compute total count
$totalCount = array_sum($_SESSION['cart']);

// Respond with JSON
echo json_encode(['success' => true, 'message' => 'Added to cart', 'cart_count' => $totalCount]);
exit;
?>