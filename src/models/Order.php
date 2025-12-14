<?php

/**
 * Order model
 *
 * Basic responsibilities:
 * - Represent an order record.
 * - Provide methods to create and fetch orders.
 */

require_once __DIR__ . '/../config/database.php';

class Order
{
    public $id;
    public $user_id;
    public $customer_name;
    public $customer_address;
    public $customer_phone;
    public $created_at;

    public static function findById(int $id): ?array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public static function findByUser(int $userId): array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('INSERT INTO orders (user_id, customer_name, customer_address, customer_phone, created_at)
                               VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['user_id'] ?? null,
            $data['customer_name'],
            $data['customer_address'],
            $data['customer_phone'],
        ]);
        return (int)$pdo->lastInsertId();
    }
}
