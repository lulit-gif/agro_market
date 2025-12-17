<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

class FarmerController {
    
    /**
     * Farmer Dashboard - Show products and orders
     */
    public static function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if farmer is logged in
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'producer') {
            header('Location: /login');
            exit;
        }

        $pdo = get_db();
        $farmerId = $_SESSION['user_id'];

        // Get farmer's products
        $stmt = $pdo->prepare('
            SELECT id, name, price, stock, created_at
            FROM products
            WHERE farmer_id = ?
            ORDER BY created_at DESC
        ');
        $stmt->execute([$farmerId]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get statistics
        $stats = [];
        
        // Total products
        $stats['products'] = count($products);

        // Total stock
        $stmt = $pdo->prepare('SELECT SUM(stock) as total FROM products WHERE farmer_id = ?');
        $stmt->execute([$farmerId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['stock'] = (int)($result['total'] ?? 0);

        // Total orders (items sold)
        $stmt = $pdo->prepare('
            SELECT COUNT(*) as count FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE p.farmer_id = ?
        ');
        $stmt->execute([$farmerId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['orders'] = (int)$result['count'];

        // Recent orders for farmer's products
        $stmt = $pdo->prepare('
            SELECT oi.id, p.name, oi.quantity, oi.price, o.customer_name, o.status, o.created_at
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            JOIN orders o ON o.id = oi.order_id
            WHERE p.farmer_id = ?
            ORDER BY o.created_at DESC
            LIMIT 10
        ');
        $stmt->execute([$farmerId]);
        $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageClass = 'farmer';
        $pageTitle = 'Farmer Dashboard - Agro Market';
        require __DIR__ . '/../views/farmer/dashboard.php';
    }

    /**
     * Add Product Form
     */
    public static function addProductForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'producer') {
            header('Location: /login');
            exit;
        }

        $pageClass = 'farmer';
        $pageTitle = 'Add Product - Agro Market';
        require __DIR__ . '/../views/farmer/product-form.php';
    }

    /**
     * Add Product - Process
     */
    public static function addProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /farmer/products');
            exit;
        }

        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid request token';
            header('Location: /farmer/product/new');
            exit;
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'producer') {
            header('Location: /login');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $imageName = null;

        // Handle image upload if provided
        if (!empty($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
            $mime = mime_content_type($_FILES['image']['tmp_name']);
            $ext = $allowed[$mime] ?? null;
            if ($ext) {
                $imageName = bin2hex(random_bytes(8)) . '.' . $ext;
                $targetDir = __DIR__ . '/../../public/img/products';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0775, true);
                }
                $target = $targetDir . '/' . $imageName;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $imageName = null; // fallback, don't block creation
                }
            }
        }

        if (!$name || $price <= 0 || $stock < 0) {
            $_SESSION['error'] = 'All fields are required with valid values';
            header('Location: /farmer/product/new');
            exit;
        }

        $pdo = get_db();
        $farmerId = $_SESSION['user_id'];

        try {
            $stmt = $pdo->prepare('
                INSERT INTO products (farmer_id, name, price, stock, description, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ');
            $stmt->execute([
                $farmerId,
                $name,
                $price,
                $stock,
                $description,
                $imageName
            ]);

            $_SESSION['success'] = 'Product added successfully! Awaiting admin approval.';
            header('Location: /farmer/dashboard');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error adding product: ' . $e->getMessage();
            header('Location: /farmer/product/new');
            exit;
        }
    }

    /**
     * Edit Product Form
     */
    public static function editProductForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'producer') {
            header('Location: /login');
            exit;
        }

        $productId = (int)($_GET['id'] ?? 0);
        if ($productId <= 0) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        $pdo = get_db();
        $farmerId = $_SESSION['user_id'];

        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ? AND farmer_id = ?');
        $stmt->execute([$productId, $farmerId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        $pageClass = 'farmer';
        $pageTitle = 'Edit Product - Agro Market';
        require __DIR__ . '/../views/farmer/product-form.php';
    }

    /**
     * Edit Product - Process
     */
    public static function editProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /farmer/dashboard');
            exit;
        }

        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid request token';
            header('Location: /farmer/dashboard');
            exit;
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'producer') {
            header('Location: /login');
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $stock = (int)($_POST['stock'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $category = null; // deprecated

        if ($productId <= 0 || !$name || $price <= 0 || $stock < 0) {
            $_SESSION['error'] = 'Invalid product data';
            header('Location: /farmer/dashboard');
            exit;
        }

        $pdo = get_db();
        $farmerId = $_SESSION['user_id'];

        // Verify product belongs to farmer
        $stmt = $pdo->prepare('SELECT id FROM products WHERE id = ? AND farmer_id = ?');
        $stmt->execute([$productId, $farmerId]);
        if (!$stmt->fetch()) {
            $_SESSION['error'] = 'Product not found';
            header('Location: /farmer/dashboard');
            exit;
        }

        // Optional image replacement
        $imageName = null;
        if (!empty($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
            $mime = mime_content_type($_FILES['image']['tmp_name']);
            $ext = $allowed[$mime] ?? null;
            if ($ext) {
                $imageName = bin2hex(random_bytes(8)) . '.' . $ext;
                $targetDir = __DIR__ . '/../../public/img/products';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0775, true);
                }
                $target = $targetDir . '/' . $imageName;
                move_uploaded_file($_FILES['image']['tmp_name'], $target);
            }
        }

        try {
            if ($imageName) {
                $stmt = $pdo->prepare('
                    UPDATE products
                    SET name = ?, price = ?, stock = ?, description = ?, image = ?
                    WHERE id = ? AND farmer_id = ?
                ');
                $stmt->execute([
                    $name,
                    $price,
                    $stock,
                    $description,
                    $imageName,
                    $productId,
                    $farmerId
                ]);
            } else {
                $stmt = $pdo->prepare('
                    UPDATE products
                    SET name = ?, price = ?, stock = ?, description = ?
                    WHERE id = ? AND farmer_id = ?
                ');
                $stmt->execute([
                    $name,
                    $price,
                    $stock,
                    $description,
                    $productId,
                    $farmerId
                ]);
            }

            $_SESSION['success'] = 'Product updated successfully';
            header('Location: /farmer/dashboard');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error updating product: ' . $e->getMessage();
            header('Location: /farmer/dashboard');
            exit;
        }
    }

    /**
     * Delete Product
     */
    public static function deleteProduct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            exit;
        }

        if (!csrf_verify($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid request token';
            header('Location: /farmer/dashboard');
            exit;
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'producer') {
            header('Location: /login');
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId <= 0) {
            $_SESSION['error'] = 'Invalid product';
            header('Location: /farmer/dashboard');
            exit;
        }

        $pdo = get_db();
        $farmerId = $_SESSION['user_id'];

        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ? AND farmer_id = ?');
        $stmt->execute([$productId, $farmerId]);

        $_SESSION['success'] = 'Product deleted';
        header('Location: /farmer/dashboard');
        exit;
    }

    /**
     * My Orders - View orders for farmer's products
     */
    public static function orders() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'producer') {
            header('Location: /login');
            exit;
        }

        $pdo = get_db();
        $farmerId = $_SESSION['user_id'];
        $status = $_GET['status'] ?? 'all';

        $query = '
            SELECT DISTINCT o.id, o.customer_name, o.status, o.created_at,
                   (SELECT COUNT(*) FROM order_items oi 
                    JOIN products p ON p.id = oi.product_id 
                    WHERE oi.order_id = o.id AND p.farmer_id = ?) as item_count
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            JOIN products p ON p.id = oi.product_id
            WHERE p.farmer_id = ?
        ';
        $params = [$farmerId, $farmerId];

        if ($status !== 'all') {
            $query .= ' AND o.status = ?';
            $params[] = $status;
        }

        $query .= ' ORDER BY o.created_at DESC LIMIT 100';

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageClass = 'farmer';
        $pageTitle = 'My Orders - Agro Market';
        require __DIR__ . '/../views/farmer/orders.php';
    }

    /**
     * View order details
     */
    public static function viewOrder() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'producer') {
            header('Location: /login');
            exit;
        }

        $orderId = (int)($_GET['id'] ?? 0);
        if ($orderId <= 0) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        $pdo = get_db();
        $farmerId = $_SESSION['user_id'];

        // Get order
        $stmt = $pdo->prepare('
            SELECT * FROM orders WHERE id = ? AND EXISTS (
                SELECT 1 FROM order_items oi
                JOIN products p ON p.id = oi.product_id
                WHERE oi.order_id = ? AND p.farmer_id = ?
            )
        ');
        $stmt->execute([$orderId, $orderId, $farmerId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            http_response_code(404);
            require __DIR__ . '/../views/404.php';
            return;
        }

        // Get order items from this farmer
        $stmt = $pdo->prepare('
            SELECT oi.id, oi.quantity, oi.price, p.name
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = ? AND p.farmer_id = ?
        ');
        $stmt->execute([$orderId, $farmerId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pageClass = 'farmer';
        $pageTitle = 'Order Details - Agro Market';
        require __DIR__ . '/../views/farmer/order-detail.php';
    }
}
?>
