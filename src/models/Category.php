<?php

/**
 * Category model
 *
 * Basic responsibilities:
 * - Represent a single category record.
 * - Provide simple methods to fetch categories.
 */

require_once __DIR__ . '/../config/database.php';

class Category
{
    public $id;
    public $name;

    public static function all(): array
    {
        $pdo = get_db();
        $stmt = $pdo->query('SELECT id, name FROM categories ORDER BY name');
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT id, name FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        $category = $stmt->fetch();
        return $category ?: null;
    }
}


