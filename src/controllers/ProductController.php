<?php
/**
 * ProductController
 *
 * Responsibilities:
 * -Display product lists with filtering, sorting, and pagination.
 * -Show product detail pages with descriptions, images, price, and availability.
 * -Handle product search by keyword, category, location, or other filters.
 * -Coordinate with FarmerController and AdminController for product management actions.
 * -Expose product data to CartController for adding items to cart.
 */


/**
 * ProductController
 *
 * Basic product features:
 * - List all products.
 * - Show a single product detail page.
 * - Simple search by keyword.
 */

require_once __DIR__ . '/../config/database.php';

class ProductController
{
    // List products (optionally with a simple search)
    public static function index()
    {
        $pdo = get_db();

        $search = trim($_GET['q'] ?? '');

        if ($search !== '') {
            $stmt = $pdo->prepare('SELECT id, name, price FROM products WHERE name LIKE ? ORDER BY created_at DESC');
            $stmt->execute(['%' . $search . '%']);
        } else {
            $stmt = $pdo->query('SELECT id, name, price FROM products ORDER BY created_at DESC');
        }

        $products = $stmt->fetchAll();

        // Very simple output (replace with a proper view if you want)
        echo '<h1>Products</h1>';

        echo '<form method="get" action="/products">
                <input type="text" name="q" placeholder="Search products" value="' . htmlspecialchars($search) . '">
                <button type="submit">Search</button>
              </form>';

        if (empty($products)) {
            echo '<p>No products found.</p>';
            return;
        }

        echo '<ul>';
        foreach ($products as $product) {
            echo '<li>';
            echo '<a href="/product?id=' . (int)$product['id'] . '">';
            echo htmlspecialchars($product['name']) . ' - ' . htmlspecialchars($product['price']);
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
    }

    // Show one product by id
    public static function show()
    {
        $pdo = get_db();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo 'Invalid product.';
            return;
        }

        $stmt = $pdo->prepare('SELECT id, name, description, price, stock FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            echo 'Product not found.';
            return;
        }

        echo '<h1>' . htmlspecialchars($product['name']) . '</h1>';
        echo '<p>Price: ' . htmlspecialchars($product['price']) . '</p>';
        echo '<p>In stock: ' . (int)$product['stock'] . '</p>';
        echo '<p>' . nl2br(htmlspecialchars($product['description'])) . '</p>';

        // Simple "add to cart" form (CartController would handle the POST)
        echo '<form method="post" action="/cart/add">
                <input type="hidden" name="product_id" value="' . (int)$product['id'] . '">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit">Add to Cart</button>
              </form>';
    }
}
