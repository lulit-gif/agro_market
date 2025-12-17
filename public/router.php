<?php
// Simple router for PHP built-in server to serve static files
// and fall back to the front controller.

if (PHP_SAPI === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . $path;
    if (is_file($file)) {
        return false; // let built-in server serve the static file
    }
}

require __DIR__ . '/index.php';
