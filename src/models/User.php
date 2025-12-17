<?php

/**
 * User model
 *
 * Basic responsibilities:
 * - Represent a user account.
 * - Provide methods to find users and create new ones.
 */

require_once __DIR__ . '/../config/database.php';

class User
{
    public $id;
    public $email;
    public $password;
    public $name;
    public $created_at;

    public static function findById(int $id): ?array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findByEmail(string $email): ?array
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function create(string $email, string $passwordHash, ?string $name = null): int
    {
        $pdo = get_db();
        $stmt = $pdo->prepare('INSERT INTO users (email, password, name, created_at)
                               VALUES (?, ?, ?, NOW())');
        $stmt->execute([$email, $passwordHash, $name]);
        return (int)$pdo->lastInsertId();
    }
}
