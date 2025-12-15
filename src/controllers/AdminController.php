<?php
/**
 * AdminController
 *
 * Responsibilities:
 * -Handle admin authentication (login, logout, access control).
 * -Manage users (buyers, farmers, admins): list, create, edit, deactivate/activate accounts.
 * -Manage products globally: approve/reject farmer products, edit or remove listings.
 * -View and manage orders: monitor all orders, update status, handle escalated issues.
 * -Access dashboards: show platform statistics (sales, users, products, reviews).
 * -Manage site settings: global configuration, categories, payment and delivery settings.
 */


/**
 * AdminController
 *
 * Basic admin features:
 * -Admin login and logout.
 * -Protect an admin dashboard.
 * -Show simple links to manage users and products.
 */

require_once __DIR__ . '/../config/database.php';

class AdminController
{
    // Show admin login form
    public static function showLoginForm()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = $_SESSION['admin_flash']['error'] ?? null;
        unset($_SESSION['admin_flash']);

        echo '<h1>Admin Login</h1>';

        if (!empty($error)) {
            echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>';
        }

        echo '<form method="post" action="/admin/login">
                <label>Email</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit">Login</button>
              </form>';
    }

    // Handle admin login
    public static function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/login');
            exit;
        }

        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || $password === '') {
            $_SESSION['admin_flash']['error'] = 'Invalid credentials.';
            header('Location: /admin/login');
            exit;
        }

        $pdo = get_db();

        // Example: admins table with email, password hash
        $stmt = $pdo->prepare('SELECT id, password FROM admins WHERE email = ?');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($password, $admin['password'])) {
            $_SESSION['admin_flash']['error'] = 'Invalid credentials.';
            header('Location: /admin/login');
            exit;
        }

        $_SESSION['admin_id'] = $admin['id'];

        header('Location: /admin/dashboard');
        exit;
    }

    // Admin logout
    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['admin_id']);
        header('Location: /admin/login');
        exit;
    }

    // Simple admin dashboard
    public static function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        echo '<h1>Admin Dashboard</h1>';
        echo '<p>Welcome, admin.</p>';

        echo '<ul>
                <li><a href="/admin/users">Manage Users</a></li>
                <li><a href="/admin/products">Manage Products</a></li>
                <li><a href="/admin/orders">View Orders</a></li>
                <li><a href="/admin/logout">Logout</a></li>
              </ul>';
    }

    // List users (very simple)
    public static function users()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $pdo = get_db();

        $stmt = $pdo->query('SELECT id, email FROM users ORDER BY created_at DESC');
        $users = $stmt->fetchAll();

        echo '<h1>Users</h1>';

        if (empty($users)) {
            echo '<p>No users found.</p>';
            return;
        }

        echo '<ul>';
        foreach ($users as $user) {
            echo '<li>' . (int)$user['id'] . ' - ' . htmlspecialchars($user['email']) . '</li>';
        }
        echo '</ul>';
    }

    // List products (very simple)
    public static function products()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $pdo = get_db();

        $stmt = $pdo->query('SELECT id, name, price FROM products ORDER BY created_at DESC');
        $products = $stmt->fetchAll();

        echo '<h1>Products</h1>';

        if (empty($products)) {
            echo '<p>No products found.</p>';
            return;
        }

        echo '<ul>';
        foreach ($products as $product) {
            echo '<li>' . (int)$product['id'] . ' - ' .
                 htmlspecialchars($product['name']) . ' - ' .
                 htmlspecialchars($product['price']) . '</li>';
        }
        echo '</ul>';
    }
}
?>