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
 * -Show home page with a welcome message and a few products.
 * -Show simple static pages: about and contact.
 */

require_once __DIR__ . '/../config/database.php';

class HomeController
{
    // Home page
    public static function index()
    {
        // Load a few latest products to show on home
        $pdo = get_db();
        $stmt = $pdo->query('SELECT id, name, price, image FROM products ORDER BY created_at DESC LIMIT 8');
        $products = $stmt->fetchAll();

        // Render the proper home view with layout
        require __DIR__ . '/../views/home/index.php';
    }

    // About page
    public static function about()
    {
        require __DIR__ . '/../views/home/about.php';
    }

    // Contact page
    public static function contact()
    {
        require __DIR__ . '/../views/home/contact.php';
    }
}
?>