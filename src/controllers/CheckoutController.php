<?php
/**
 * CheckoutController
 *
 * Responsibilities:
 * -Handle the checkout process from cart to order creation.
 * -Collect and validate shipping details, billing details, and contact info.
 * -Integrate with payment gateway: create payment intent, handle callbacks/responses.
 * -Create and store orders in the database when payment succeeds.
 * -Show order confirmation page and handle checkout failures or cancellations.
 */


/**
 * CheckoutController
 *
 * Basic checkout features:
 * - Show checkout form with cart summary.
 * - Create a simple order from the cart.
 * - Show a basic order confirmation page.
 */

require_once __DIR__ . '/../config/database.php';

class CheckoutController
{
    // Show checkout page
    public static function show()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cart = $_SESSION['cart'] ?? []; // ['product_id' => quantity]
        $pdo  = get_db();

        echo '<h1>Checkout</h1>';

        if (empty($cart)) {
            echo '<p>Your cart is empty.</p>';
            return;
        }

        // Load products for summary
        $productIds = array_keys($cart);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
        $stmt->execute($productIds);
        $products = $stmt->fetchAll();

        $total = 0;

        echo '<h2>Order Summary</h2>';
        echo '<ul>';
        foreach ($products as $product) {
            $qty   = (int)$cart[$product['id']];
            $line  = $product['price'] * $qty;
            $total += $line;

            echo '<li>' . htmlspecialchars($product['name']) .
                 ' x ' . $qty .
                 ' = ' . htmlspecialchars($line) . '</li>';
        }
        echo '</ul>';
        echo '<p>Total: ' . htmlspecialchars($total) . '</p>';

        // Simple shipping form
        echo '<h2>Shipping Details</h2>';
        echo '<form method="post" action="/checkout/submit">
                <label>Full Name</label>
                <input type="text" name="name" required>

                <label>Address</label>
                <input type="text" name="address" required>

                <label>Phone</label>
                <input type="text" name="phone" required>

                <button type="submit">Place Order</button>
              </form>';
    }

    // Handle checkout submission and create order
    public static function submit()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /checkout');
            exit;
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: /cart');
            exit;
        }

        $name    = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');

        if ($name === '' || $address === '' || $phone === '') {
            header('Location: /checkout');
            exit;
        }

        $pdo = get_db();
        $pdo->beginTransaction();

        try {
            // Create order
            $stmt = $pdo->prepare('INSERT INTO orders (customer_name, customer_address, customer_phone, created_at)
                                   VALUES (?, ?, ?, NOW())');
            $stmt->execute([$name, $address, $phone]);
            $orderId = $pdo->lastInsertId();

            // Load products
            $productIds   = array_keys($cart);
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));

            $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
            $stmt->execute($productIds);
            $products = $stmt->fetchAll();

            // Create order items
            $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price)
                                       VALUES (?, ?, ?, ?)');

            foreach ($products as $product) {
                $qty = (int)$cart[$product['id']];
                $itemStmt->execute([
                    $orderId,
                    $product['id'],
                    $qty,
                    $product['price']
                ]);
            }

            $pdo->commit();

            // Clear cart
            unset($_SESSION['cart']);

            header('Location: /checkout/confirm?id=' . $orderId);
            exit;
        } catch (\Exception $e) {
            $pdo->rollBack();
            echo 'There was a problem placing your order.';
        }
    }

    // Simple order confirmation page
    public static function confirm()
    {
        $orderId = (int)($_GET['id'] ?? 0);
        if ($orderId <= 0) {
            echo 'Invalid order.';
            return;
        }

        $pdo = get_db();

        $stmt = $pdo->prepare('SELECT id, customer_name, customer_address, customer_phone, created_at
                               FROM orders WHERE id = ?');
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();

        if (!$order) {
            echo 'Order not found.';
            return;
        }

        echo '<h1>Order Confirmation</h1>';
        echo '<p>Thank you, ' . htmlspecialchars($order['customer_name']) . '.</p>';
        echo '<p>Your order number is ' . (int)$order['id'] . '.</p>';
    }
}
?>