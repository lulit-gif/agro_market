<?php

/**
 * OrderItem model
 *
 * Basic responsibilities:
 * - Represent an item in an order.
 * - Provide methods to fetch items by order.
 */

require_once __DIR__ . '/../config/database.php';

class OrderItem
{
    public $id;
    public $order_id;
    public $product_id;
    public $quantity;
    public $price;

    public static function findByOrder(int $orderId): array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public static function create(int $orderId, int $productId, int $quantity, float $price): void
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price)
                               VALUES (?, ?, ?, ?)');
        $stmt->execute([$orderId, $productId, $quantity, $price]);
    }
}
