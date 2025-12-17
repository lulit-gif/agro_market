<?php
/**
 * BuyerController
 *
 * Responsibilities:
 * - Handle buyer account pages: profile view/edit, address and contact details.
 * -Manage buyer-specific views of products: browsing history, saved items/wishlist.
 * -Show buyer orders: list, detail view, order status tracking.
 * -Support account actions: update password, notification preferences, delete/deactivate account.
 * -Coordinate with CartController and CheckoutController for a smooth buying flow.
 */

/**
 * BuyerController
 *
 * Basic buyer features:
 * -Show buyer profile.
 * -Edit basic profile data.
 * -List buyer orders.
 */

require_once __DIR__ . '/../config/database.php';

class BuyerController
{
    // Show buyer profile (very simple)
    public static function profile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $pdo    = get_db();

        $stmt = $pdo->prepare('SELECT id, email, name FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            echo 'User not found.';
            return;
        }

        require __DIR__ . '/../views/buyer/profile.php';
    }

    // Update basic profile
    public static function updateProfile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $name   = trim($_POST['name'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';

        $pdo = get_db();
        $success = '';
        $error = '';

        // Update basic fields
        $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
        $stmt->execute([$name, $email, $userId]);
        $success = 'Profile updated.';

        // Handle password change if requested
        if (!empty($new)) {
            // Fetch current hash
            $s = $pdo->prepare('SELECT password FROM users WHERE id = ?');
            $s->execute([$userId]);
            $row = $s->fetch();

            if (!$row || !password_verify($current, $row['password'])) {
                $error = 'Current password is incorrect.';
            } else {
                $hash = password_hash($new, PASSWORD_BCRYPT);
                $u = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
                $u->execute([$hash, $userId]);
                $success = 'Password updated.';
            }
        }

        // Reload profile data and render view with messages
        $stmt = $pdo->prepare('SELECT id, email, name FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        require __DIR__ . '/../views/buyer/profile.php';
    }

    // List buyer orders (simple)
    public static function orders()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $pdo    = get_db();

        // Fetch orders for this buyer with summary info
        $stmt = $pdo->prepare('SELECT id, created_at, status, total FROM orders WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();

        // Count items per order
        $itemCounts = [];
        if (!empty($orders)) {
            $orderIds = array_map(fn($o) => (int)$o['id'], $orders);
            $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
            $isStmt = $pdo->prepare("SELECT order_id, SUM(quantity) as qty FROM order_items WHERE order_id IN ($placeholders) GROUP BY order_id");
            $isStmt->execute($orderIds);
            foreach ($isStmt->fetchAll() as $row) {
                $itemCounts[(int)$row['order_id']] = (int)$row['qty'];
            }
        }

        require __DIR__ . '/../views/buyer/orders.php';
    }
}
?>