<?php
// Middleware: FarmerMiddleware.php
// Ensures the current user has role 'producer' or 'farmer'
require_once __DIR__ . '/AuthMiddleware.php';

function require_farmer()
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $role = $_SESSION['role'] ?? null;
    if (!in_array($role, ['producer', 'farmer', 'admin'])) {
        http_response_code(403);
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Farmer access required']);
        } else {
            echo 'Farmer access required';
        }
        exit;
    }
}
