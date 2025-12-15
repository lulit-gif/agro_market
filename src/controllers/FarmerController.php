<?php
/**
 * FarmerController
 *
 * Responsibilities:
 * -Handle farmer account pages: profile, farm details, verification status.
 * -Manage farmer products: create, edit, publish/unpublish, and delete listings.
 * -Show sales dashboard: orders for the farmer’s products, revenue, stock status.
 * - Manage inventory: update stock, pricing, and product availability.
 * - Respond to buyer inquiries related to the farmer’s products (if messaging exists).
 */


/**
 * FarmerController
 *
 * Basic farmer features:
 * - Show farmer dashboard.
 * - Show and add products for the logged-in farmer.
 */

require_once __DIR__ . '/../config/database.php';

class FarmerController
{
    // Farmer dashboard (simple)
    public static function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $farmerId = $_SESSION['user_id'];
        $pdo = get_db();

        // Fetch farmer products
        $stmt = $pdo->prepare('SELECT id, name, price, stock FROM products WHERE farmer_id = ? ORDER BY created_at DESC');
        $stmt->execute([$farmerId]);
        $products = $stmt->fetchAll();

        echo '<h1>Farmer Dashboard</h1>';
        echo '<p>Welcome, farmer.</p>';

        echo '<h2>Your Products</h2>';
        echo '<a href="/farmer/product/new">Add New Product</a>';

        if (empty($products)) {
            echo '<p>You have no products yet.</p>';
        } else {
            echo '<ul>';
            foreach ($products as $product) {
                echo '<li>';
                echo htmlspecialchars($product['name']) . ' - ' . htmlspecialchars($product['price']);
                echo ' (Stock: ' . (int)$product['stock'] . ')';
                echo ' <a href="/farmer/product/edit?id=' . (int)$product['id'] . '">Edit</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
    }

    // Show simple form to create a product
    public static function showCreateForm()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        echo '<h1>Add Product</h1>';
        echo '<form method="post" action="/farmer/product/create">
                <label>Name</label>
                <input type="text" name="name" required>

                <label>Price</label>
                <input type="number" name="price" step="0.01" required>

                <label>Stock</label>
                <input type="number" name="stock" min="0" required>

                <button type="submit">Save</button>
              </form>';
    }

    // Handle product creation
    public static function create()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $farmerId = $_SESSION['user_id'];
        $name     = trim($_POST['name'] ?? '');
        $price    = (float)($_POST['price'] ?? 0);
        $stock    = (int)($_POST['stock'] ?? 0);

        if ($name === '' || $price <= 0 || $stock < 0) {
            header('Location: /farmer/product/new');
            exit;
        }

        $pdo = get_db();

        $stmt = $pdo->prepare('INSERT INTO products (farmer_id, name, price, stock, created_at)
                               VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$farmerId, $name, $price, $stock]);

        header('Location: /farmer/dashboard');
        exit;
    }
}
?>

