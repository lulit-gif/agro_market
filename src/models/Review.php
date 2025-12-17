<?php

/**
 * Review model
 *
 * Basic responsibilities:
 * - Represent a review for a product.
 * - Provide methods to fetch and create reviews.
 */

require_once __DIR__ . '/../config/database.php';

class Review
{
    public $id;
    public $buyer_id;
    public $product_id;
    public $rating;
    public $comment;
    public $created_at;

    public static function findByProduct(int $productId): array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC');
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function create(int $buyerId, int $productId, int $rating, string $comment): void
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('INSERT INTO reviews (buyer_id, product_id, rating, comment, created_at)
                               VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$buyerId, $productId, $rating, $comment]);
    }
}

