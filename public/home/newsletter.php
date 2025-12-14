<?php
header('Content-Type: application/json');
// Minimal newsletter endpoint for demo purposes
session_start();

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address']);
    exit;
}

// In production you'd persist the email to a database or mailing list.
// For demo, store in session array
if (!isset($_SESSION['newsletter'])) $_SESSION['newsletter'] = [];
if (!in_array($email, $_SESSION['newsletter'])) {
    $_SESSION['newsletter'][] = $email;
}

echo json_encode(['success' => true, 'message' => 'Thanks! You are subscribed.']);
exit;
?>