<?php
// Minimal PDO DB helper for the project.
// Adjust the constants below to match your environment.
define('DB_HOST', 'localhost');
define('DB_NAME', 'agro_market');
define('DB_USER', 'root');
define('DB_PASS', '');

function get_db()
{
	static $pdo = null;
	if ($pdo instanceof PDO) {
		return $pdo;
	}

	$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
	try {
		$pdo = new PDO($dsn, DB_USER, DB_PASS, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES =>false,
		]);
		return $pdo;
	} catch (PDOException $e) {
		// In production you might log and show a generic error.
		die('Database connection failed: ' . $e->getMessage());
	}
}

?>
