<?php
/**
 * HomeController
 *
 * Responsibilities:
 * -Render public-facing pages: home page, landing sections, and general information.
 * -Show featured products, categories, and promotional content.
 * -Provide search entry points and basic filtering for products.
 * -Handle static pages: about, contact, FAQ, terms, and privacy policy.
 * -Optionally provide basic SEO-related data for main pages.
 */


/**
 * HomeController
 *
 * Basic home features:
 * - Show home page with a welcome message and a few products.
 * - Show simple static pages: about and contact.
 */

require_once __DIR__ . '/../config/database.php';

class HomeController
{
    // Home page
    public static function index()
    {
        $pdo = get_db();

        // Get a few latest products (optional, keep it simple)
        $stmt = $pdo->query('SELECT id, name, price FROM products ORDER BY created_at DESC LIMIT 4');
        $products = $stmt->fetchAll();

        echo '<h1>Welcome to Our Store</h1>';
        echo '<p>Browse our latest products below.</p>';

        if (!empty($products)) {
            echo '<ul>';
            foreach ($products as $product) {
                echo '<li>';
                echo '<a href="/product?id=' . (int)$product['id'] . '">';
                echo htmlspecialchars($product['name']) . ' - ' . htmlspecialchars($product['price']);
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No products available yet.</p>';
        }
    }

    // About page
    public static function about()
    {
        echo '<h1>About Us</h1>';
        echo '<p>This is a simple marketplace where buyers can find products from farmers.</p>';
    }

    // Contact page
    public static function contact()
    {
        echo '<h1>Contact Us</h1>';
        echo '<p>You can contact us at example@example.com.</p>';
    }
}
?>