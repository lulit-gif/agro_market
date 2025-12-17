<?php
/**
 * CartController
 *
 * Responsibilities:
 * -Manage the shopping cart for the current user or session.
 * -Add products to cart, update quantities, and remove items.
 * -Calculate cart totals: item subtotals, discounts, taxes, and grand total.
 * -Validate product availability and stock before checkout.
 * -Provide cart summary views for use in headers, sidebars, and checkout pages.
 */

/**
 * CartController
 *
 * Basic cart features:
 * - Add products to cart.
 * - Update quantities.
 * - Remove items.
 * - Show a simple cart page.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

class CartController
{
    // Show cart
    public static function show()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cart = $_SESSION['cart'] ?? []; // ['product_id' => quantity]

        $pdo = get_db();

        $items = [];
        $total = 0.0;

        if (!empty($cart)) {
            $productIds   = array_keys($cart);
            $placeholders = implode(',', array_fill(0, count($productIds), '?'));
            $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id IN ($placeholders)");
            $stmt->execute($productIds);
            $products = $stmt->fetchAll();

            foreach ($products as $p) {
                $id  = (int)$p['id'];
                $qty = (int)($cart[$id] ?? 0);
                if ($qty <= 0) continue;
                $price = (float)$p['price'];
                $line  = $qty * $price;
                $total += $line;
                $items[] = [
                    'id' => $id,
                    'name' => $p['name'],
                    'price' => $price,
                    'qty' => $qty,
                    'image' => $p['image'] ?? null,
                    'line_total' => $line,
                ];
            }
        }

        $pageClass = 'products'; // reuse themed styles
        require __DIR__ . '/../views/cart/cart.php';
    }

    // Add item to cart
    public static function add()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products');
            exit;
        }
        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            header('Location: /products');
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 1);

        if ($productId <= 0 || $quantity <= 0) {
            header('Location: /products');
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }

        $_SESSION['cart'][$productId] += $quantity;

        header('Location: /cart');
        exit;
    }

    // Update quantities / remove items
    public static function update()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            exit;
        }
        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            header('Location: /cart');
            exit;
        }

        $quantities = $_POST['qty'] ?? [];

        if (!is_array($quantities)) {
            header('Location: /cart');
            exit;
        }

        $_SESSION['cart'] = $_SESSION['cart'] ?? [];

        foreach ($quantities as $productId => $qty) {
            $productId = (int)$productId;
            $qty       = (int)$qty;

            if ($qty <= 0) {
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId] = $qty;
            }
        }

        header('Location: /cart');
        exit;
    }
}
?>