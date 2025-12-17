<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

class CheckoutController {
    
    // Show checkout page
    public static function show() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login?redirect=/checkout');
            exit;
        }

        $cart = $_SESSION['cart'] ?? [];
        $pdo = get_db();

        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }

        // Get products for checkout summary
        $productIds = array_keys($cart);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        
        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
        $stmt->execute($productIds);
        $products = $stmt->fetchAll();

        // Calculate totals
        $cartItems = [];
        $subtotal = 0;
        foreach ($products as $p) {
            $id = (int)$p['id'];
            $qty = (int)($cart[$id] ?? 0);
            if ($qty <= 0) continue;
            $price = (float)$p['price'];
            $line = $qty * $price;
            $subtotal += $line;
            $cartItems[] = [
                'id' => $id,
                'name' => $p['name'],
                'price' => $price,
                'qty' => $qty,
                'line_total' => $line,
            ];
        }

        $shipping = 5.00;
        $tax = $subtotal * 0.1;
        $total = $subtotal + $shipping + $tax;

        $pageClass = 'products';
        $pageTitle = 'Checkout - Agro Market';
        $csrf_token = $_SESSION['csrf_token'] ?? '';
        
        require __DIR__ . '/../views/checkout/index.php';
    }

    // Handle checkout submission
    public static function submit() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /checkout');
            exit;
        }

        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid request token';
            header('Location: /checkout');
            exit;
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }

        // Get form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $postal_code = trim($_POST['postal_code'] ?? '');
        $payment_method = $_POST['payment_method'] ?? 'credit_card';

        // Validate
        if (!$name || !$email || !$phone || !$address || !$city || !$postal_code) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: /checkout');
            exit;
        }

        $pdo = get_db();
        $pdo->beginTransaction();

        try {
            // Create order (align to schema)
            $userId = $_SESSION['user_id'];
            // Calculate totals from cart again for persistence
            $cartIds = array_keys($cart);
            $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
            $pstmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
            $pstmt->execute($cartIds);
            $prods = $pstmt->fetchAll();

            $subtotal = 0.0;
            foreach ($prods as $prod) {
                $pid = (int)$prod['id'];
                $qty = (int)($cart[$pid] ?? 0);
                if ($qty <= 0) continue;
                $subtotal += $qty * (float)$prod['price'];
            }
            $shipping = 5.00;
            $tax = $subtotal * 0.1;
            $total = $subtotal + $shipping + $tax;

            $stmt = $pdo->prepare('
                INSERT INTO orders (user_id, customer_name, customer_address, customer_phone, total, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ');
            $stmt->execute([
                $userId,
                $name,
                $address,
                $phone,
                $total,
                'pending'
            ]);
            $orderId = $pdo->lastInsertId();

            // Get products
            $products = $prods;

            // Create order items
            $itemStmt = $pdo->prepare('
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ');

            foreach ($products as $product) {
                $id = (int)$product['id'];
                $qty = (int)($cart[$id] ?? 0);
                if ($qty <= 0) continue;
                
                $itemStmt->execute([
                    $orderId,
                    $id,
                    $qty,
                    $product['price']
                ]);
            }

            $pdo->commit();

            // Clear cart
            unset($_SESSION['cart']);

            // Redirect to confirmation
            header('Location: /checkout/confirm?id=' . $orderId);
            exit;
        } catch (\Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = 'Error creating order: ' . $e->getMessage();
            header('Location: /checkout');
            exit;
        }
    }

    // Show order confirmation
    public static function confirm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $orderId = (int)($_GET['id'] ?? 0);
        if ($orderId <= 0) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        $pdo = get_db();
        $stmt = $pdo->prepare('
            SELECT id, customer_name, customer_phone,
                   customer_address, total, created_at, status
            FROM orders WHERE id = ?
        ');
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();

        if (!$order) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        // Get order items
        $stmt = $pdo->prepare('
            SELECT oi.quantity, oi.price, p.name
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = ?
        ');
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll();

        $pageClass = 'products';
        $pageTitle = 'Order Confirmation - Agro Market';
        require __DIR__ . '/../views/checkout/confirm.php';
    }
}
?>
