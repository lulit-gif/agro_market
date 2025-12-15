<?php
// Middleware: CsrfMiddleware.php
// Basic CSRF verification helper that checks against session token or header

function verify_csrf()
{
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $token = $_SESSION['csrf_token'] ?? '';
    // token may come in header X-CSRF-Token, or in POST field 'csrf_token'
    $hdr = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_SERVER['HTTP_X_CSRFHEADER'] ?? null);
    $post = $_POST['csrf_token'] ?? null;
    if ($hdr) $given = $hdr;
    else if ($post) $given = $post;
    else $given = null;
    if (!$given || !$token || !hash_equals($token, $given)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
        exit;
    }
}
