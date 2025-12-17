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
 * -List all products.
 * -Show a single product detail page.
 * -Simple search by keyword.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

class ProductController
{
    // List products (optionally with a simple search)
    public static function index()
    {
        $pdo = get_db();

        $search = trim($_GET['q'] ?? '');

        if ($search !== '') {
            $stmt = $pdo->prepare('SELECT id, name, price, image FROM products WHERE name LIKE ? ORDER BY created_at DESC');
            $stmt->execute(['%' . $search . '%']);
        } else {
            $stmt = $pdo->query('SELECT id, name, price, image FROM products ORDER BY created_at DESC');
        }

        $products = $stmt->fetchAll();

        // Render view
        $pageClass = 'products';
        require __DIR__ . '/../views/products/list.php';
    }

    // Show one product by id
    public static function show()
    {
        $pdo = get_db();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        $stmt = $pdo->prepare('SELECT id, name, description, price, stock, image FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        $pageClass = 'products';
        require __DIR__ . '/../views/products/details.php';
    }
}
