<?php

/**
 * Product model
 *
 * Basic responsibilities:
 * - Represent a product record.
 * - Provide simple methods to list, search, and find products.
 */

require_once __DIR__ . '/../config/database.php';

class Product
{
    public $id;
    public $farmer_id;
    public $category_id;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $created_at;

    public static function all(): array
    {
        $pdo = get_db();
        $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    public static function searchByName(string $keyword): array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT * FROM products WHERE name LIKE ? ORDER BY created_at DESC');
        $stmt->execute(['%' . $keyword . '%']);
        return $stmt->fetchAll();
    }
}
