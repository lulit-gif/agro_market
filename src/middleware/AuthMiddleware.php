<?php
// Middleware: AuthMiddleware.php
// Simple helper to require a logged-in user via $_SESSION['user_id']

function require_auth() {
	if (session_status() !== PHP_SESSION_ACTIVE) session_start();
	if (empty($_SESSION['user_id'])) {
		// If this looks like an AJAX/API call, return JSON 401
		$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false);
		if ($isAjax) {
			http_response_code(401);
			header('Content-Type: application/json');
			echo json_encode(['success' => false, 'message' => 'Authentication required']);
			exit;
		}
		// Otherwise redirect to login page
		header('Location: /agro_market/login.php');
		exit;
	}
}

function current_user_id() {
	if (session_status() !== PHP_SESSION_ACTIVE) session_start();
	return $_SESSION['user_id'] ?? null;
}

?>
