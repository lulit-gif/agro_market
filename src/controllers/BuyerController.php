<?php
/**
 * BuyerController
 *
 * Responsibilities:
 * -Handle buyer account pages: profile view/edit, address and contact details.
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

        echo '<h1>Your Profile</h1>';
        echo '<p>Email: ' . htmlspecialchars($user['email']) . '</p>';
        echo '<p>Name: ' . htmlspecialchars($user['name'] ?? '') . '</p>';

        echo '<h2>Edit Profile</h2>';
        echo '<form method="post" action="/buyer/profile/update">
                <label>Name</label>
                <input type="text" name="name" value="' . htmlspecialchars($user['name'] ?? '') . '">

                <button type="submit">Save</button>
              </form>';

        echo '<h2>Your Orders</h2>';
        echo '<a href="/buyer/orders">View Orders</a>';
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

        $pdo = get_db();

        $stmt = $pdo->prepare('UPDATE users SET name = ? WHERE id = ?');
        $stmt->execute([$name, $userId]);

        header('Location: /buyer/profile');
        exit;
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

        // Assuming orders table has user_id field
        $stmt = $pdo->prepare('SELECT id, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();

        echo '<h1>Your Orders</h1>';

        if (empty($orders)) {
            echo '<p>You have no orders.</p>';
            return;
        }

        echo '<ul>';
        foreach ($orders as $order) {
            echo '<li>Order #' . (int)$order['id'] .
                 ' - ' . htmlspecialchars($order['created_at']) .
                 ' - <a href="/buyer/orders/view?id=' . (int)$order['id'] . '">View</a></li>';
        }
        echo '</ul>';
    }
}
?>