<?php
/**
 * ReviewController
 *
 * Responsibilities:
 * - Handle creation of reviews and ratings for products (and optionally farmers).
 * - Validate that only eligible buyers (who purchased) can review.
 * - Display product reviews and average ratings on product detail pages.
 * - Allow buyers to edit or delete their own reviews when permitted.
 * - Provide moderation tools (for admins) to hide or remove inappropriate reviews.
 */

require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Buyer.php';
require_once __DIR__ . '/../config/database.php';

class ReviewController
{
    // Show reviews for a product
    public static function listByProduct($productId)
    {
        $pdo = get_db();
        $stmt = $pdo->prepare(
            'SELECT r.id, r.rating, r.comment, r.created_at, b.first_name, b.last_name 
             FROM reviews r 
             JOIN buyers b ON r.buyer_id = b.id 
             WHERE r.product_id = :product_id 
             ORDER BY r.created_at DESC'
        );
        $stmt->execute(['product_id' => $productId]);
        $reviews = $stmt->fetchAll();

        // Very simple output (you can replace this with a proper view)
        foreach ($reviews as $review) {
            $name = trim(($review['first_name'] ?? '') . ' ' . ($review['last_name'] ?? ''));
            echo '<p><strong>' . htmlspecialchars($name) . '</strong><br>';
            echo 'Rated: ' . htmlspecialchars($review['rating']) . '/5<br>';
            echo 'Comment: ' . htmlspecialchars($review['comment']) . '<br>';
            echo 'On: ' . htmlspecialchars($review['created_at']) . '</p>';
        }
    }

    // Route target: /review/add (POST)
    public static function add()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products');
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo 'Login required to add review';
            exit;
        }

        $userId    = (int)$_SESSION['user_id'];
        $productId = (int)($_POST['product_id'] ?? 0);
        $rating    = (int)($_POST['rating'] ?? 0);
        $comment   = trim($_POST['comment'] ?? '');

        // Basic validation
        if ($productId <= 0 || $rating < 1 || $rating > 5) {
            http_response_code(400);
            echo 'Invalid input';
            exit;
        }

        $pdo = get_db();
        $stmt = $pdo->prepare(
            'INSERT INTO reviews (product_id, buyer_id, rating, comment, created_at) 
             VALUES (:product_id, :buyer_id, :rating, :comment, NOW())'
        );
        $stmt->execute([
            'buyer_id'   => $userId,
            'product_id' => $productId,
            'rating'     => $rating,
            'comment'    => $comment
        ]);

        header('Location: /product?id=' . $productId);
        exit;
    }

    // Route target: /review/delete (POST)
    public static function delete()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products');
            exit;
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId    = (int)$_SESSION['user_id'];
        $reviewId  = (int)($_POST['review_id'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);

        if ($reviewId <= 0 || $productId <= 0) {
            header('Location: /product?id=' . $productId);
            exit;
        }

        $pdo = get_db();

        // Ensure the review belongs to this user
        $stmt = $pdo->prepare('DELETE FROM reviews WHERE id = ? AND buyer_id = ?');
        $stmt->execute([$reviewId, $userId]);

        header('Location: /product?id=' . $productId);
        exit;
    }
}
