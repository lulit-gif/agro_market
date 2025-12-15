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

class CartController
{
    // Show cart
    public static function show()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cart = $_SESSION['cart'] ?? []; // ['product_id' => quantity]

        if (empty($cart)) {
            echo '<h1>Your Cart</h1>';
            echo '<p>Your cart is empty.</p>';
            return;
        }

        $pdo = get_db();
        $productIds   = array_keys($cart);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
        $stmt->execute($productIds);
        $products = $stmt->fetchAll();

        echo '<h1>Your Cart</h1>';
        echo '<form method="post" action="/cart/update">';
        echo '<ul>';

        $total = 0;
        foreach ($products as $product) {
            $id   = (int)$product['id'];
            $qty  = (int)$cart[$id];
            $line = $qty * $product['price'];
            $total += $line;

            echo '<li>';
            echo htmlspecialchars($product['name']) . ' - ' . htmlspecialchars($product['price']);
            echo ' x <input type="number" name="qty[' . $id . ']" value="' . $qty . '" min="0">';
            echo ' = ' . htmlspecialchars($line);
            echo '</li>';
        }

        echo '</ul>';
        echo '<p>Total: ' . htmlspecialchars($total) . '</p>';
        echo '<button type="submit">Update Cart</button>';
        echo '</form>';

        echo '<form method="post" action="/checkout">
                <button type="submit">Go to Checkout</button>
              </form>';
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