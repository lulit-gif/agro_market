<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

class AdminController {
    
    /**
     * Admin Dashboard - Show statistics and recent orders
     */
    public static function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if admin is logged in
        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $pdo = get_db();

        // Get statistics
        $stats = [];
        
        // Total users
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM users WHERE role = "consumer"');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['users'] = (int)$result['count'];

        // Total farmers (producers)
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM users WHERE role = "producer"');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['farmers'] = (int)$result['count'];

        // Total products
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM products');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['products'] = (int)$result['count'];

        // Total orders
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM orders');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['orders'] = (int)$result['count'];

        // Recent orders
        $stmt = $pdo->query('
            SELECT o.id, o.customer_name, o.status, o.created_at, 
                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
            FROM orders o
            ORDER BY o.created_at DESC
            LIMIT 10
        ');
        $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Recent products
        $stmt = $pdo->query('
            SELECT p.id, p.name, p.price, u.email as farmer_email, p.created_at
            FROM products p
            LEFT JOIN users u ON u.id = p.farmer_id
            ORDER BY p.created_at DESC
            LIMIT 5
        ');
        $recentProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageClass = 'admin';
        $pageTitle = 'Admin Dashboard - Agro Market';
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    /**
     * Manage Users - List all users with filters
     */
    public static function users() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $pdo = get_db();
        $filter = $_GET['filter'] ?? 'all';
        $search = trim($_GET['search'] ?? '');

        // Build query
        $query = 'SELECT id, email, role, created_at FROM users WHERE 1=1';
        $params = [];

        if ($filter === 'buyers') {
            $query .= ' AND role = "consumer"';
        } elseif ($filter === 'farmers') {
            $query .= ' AND role = "producer"';
        }

        if (!empty($search)) {
            $query .= ' AND email LIKE ?';
            $params[] = '%' . $search . '%';
        }

        $query .= ' ORDER BY created_at DESC LIMIT 100';

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageClass = 'admin';
        $pageTitle = 'Manage Users - Admin';
        require __DIR__ . '/../views/admin/users.php';
    }

    /**
     * Manage Products - Approve/reject products
     */
    public static function products() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $pdo = get_db();
        $stmt = $pdo->query('
            SELECT p.id, p.name, p.price, p.stock, u.email as farmer_email, p.created_at
            FROM products p
            LEFT JOIN users u ON u.id = p.farmer_id
            ORDER BY p.created_at DESC
            LIMIT 100
        ');
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageClass = 'admin';
        $pageTitle = 'Manage Products - Admin';
        require __DIR__ . '/../views/admin/products.php';
    }

    /**
     * Approve a product
     */
    public static function approveProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            exit;
        }

        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid request token';
            header('Location: /admin/products');
            exit;
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId <= 0) {
            $_SESSION['error'] = 'Invalid product';
            header('Location: /admin/products');
            exit;
        }

        $pdo = get_db();
        $stmt = $pdo->prepare('UPDATE products SET status = "approved" WHERE id = ?');
        $stmt->execute([$productId]);

        $_SESSION['success'] = 'Product approved successfully';
        header('Location: /admin/products?status=pending');
        exit;
    }

    /**
     * Reject a product
     */
    public static function rejectProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            exit;
        }

        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid request token';
            header('Location: /admin/products');
            exit;
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');

        if ($productId <= 0) {
            $_SESSION['error'] = 'Invalid product';
            header('Location: /admin/products');
            exit;
        }

        $pdo = get_db();
        $stmt = $pdo->prepare('UPDATE products SET status = "rejected", rejection_reason = ? WHERE id = ?');
        $stmt->execute([$reason, $productId]);

        $_SESSION['success'] = 'Product rejected';
        header('Location: /admin/products?status=pending');
        exit;
    }

    /**
     * Manage Orders - View all orders
     */
    public static function orders() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $pdo = get_db();
        $status = $_GET['status'] ?? 'all';

        $query = 'SELECT id, customer_name, customer_phone, total, status, created_at FROM orders';
        $params = [];

        if ($status !== 'all') {
            $query .= ' WHERE status = ?';
            $params[] = $status;
        }

        $query .= ' ORDER BY created_at DESC LIMIT 100';

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageClass = 'admin';
        $pageTitle = 'Manage Orders - Admin';
        require __DIR__ . '/../views/admin/orders.php';
    }

    /**
     * View a single order with items
     */
    public static function viewOrder() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $orderId = (int)($_GET['id'] ?? 0);
        if ($orderId <= 0) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        $pdo = get_db();
        // Get order
        $stmt = $pdo->prepare('SELECT id, user_id, customer_name, customer_address, customer_phone, total, status, created_at FROM orders WHERE id = ?');
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        // Get order items with product info and farmer email
        $stmt = $pdo->prepare('
            SELECT oi.product_id, oi.quantity, oi.price,
                   p.name AS product_name, u.email AS farmer_email
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            LEFT JOIN users u ON u.id = p.farmer_id
            WHERE oi.order_id = ?
        ');
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageClass = 'admin';
        $pageTitle = 'Order #' . (int)$order['id'] . ' - Admin';
        require __DIR__ . '/../views/admin/order-detail.php';
    }

    /**
     * Update order status
     */
    public static function updateOrderStatus() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            exit;
        }

        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid request token';
            header('Location: /admin/orders');
            exit;
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($orderId <= 0 || !in_array($status, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])) {
            $_SESSION['error'] = 'Invalid order or status';
            header('Location: /admin/orders');
            exit;
        }

        $pdo = get_db();
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$status, $orderId]);

        $_SESSION['success'] = 'Order status updated';
        header('Location: /admin/orders');
        exit;
    }

    /**
     * Admin login page
     */
    public static function loginForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Already logged in
        if (!empty($_SESSION['admin_id'])) {
            header('Location: /admin/dashboard');
            exit;
        }

        $pageClass = 'auth';
        $pageTitle = 'Admin Login - Agro Market';
        require __DIR__ . '/../views/admin/login.php';
    }

    /**
     * Admin login - process
     */
    public static function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['error'] = 'Email and password required';
            header('Location: /admin/login');
            exit;
        }

        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ? AND role = "admin" LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: /admin/login');
            exit;
        }

        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = 'admin';
        header('Location: /admin/dashboard');
        exit;
    }

    /**
     * Admin logout
     */
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['admin_id']);
        session_destroy();
        header('Location: /');
        exit;
    }

    /**
     * List and manage farmers (producers)
     */
    public static function farmers() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $pdo = get_db();
        $stmt = $pdo->query('SELECT id, email, name, created_at FROM users WHERE role = "producer" ORDER BY created_at DESC');
        $farmers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageClass = 'admin';
        $pageTitle = 'Manage Farmers - Admin';
        require __DIR__ . '/../views/admin/farmers.php';
    }

    /**
     * Show add farmer form
     */
    public static function newFarmerForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        $pageClass = 'admin';
        $pageTitle = 'Add Farmer - Admin';
        $error = $_SESSION['error'] ?? '';
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['error'], $_SESSION['success']);
        require __DIR__ . '/../views/admin/farmer-form.php';
    }

    /**
     * Create a new farmer account
     */
    public static function createFarmer() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            exit;
        }

        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }

        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid request token';
            header('Location: /admin/farmers/new');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['error'] = 'Email and password are required';
            header('Location: /admin/farmers/new');
            exit;
        }

        $pdo = get_db();

        // Check if email exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = 'Email already in use';
            header('Location: /admin/farmers/new');
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (email, password, role, name, created_at) VALUES (?, ?, "producer", ?, NOW())');
        $stmt->execute([$email, $hash, $name]);

        $_SESSION['success'] = 'Farmer created successfully';
        header('Location: /admin/farmers');
        exit;
    }
}
?>
