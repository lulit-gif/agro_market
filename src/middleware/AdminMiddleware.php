<?php
// Middleware: AdminMiddleware.php
// Ensures the current user has role 'admin'
require_once __DIR__ . '/AuthMiddleware.php';

function require_admin()
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $role = $_SESSION['role'] ?? null;
    if ($role !== 'admin') {
        http_response_code(403);
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Admin access required']);
        } else {
            echo 'Admin access required';
        }
        exit;
    }
}
