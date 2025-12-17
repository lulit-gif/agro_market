<?php
// public/index.php
// Single entrypoint: delegate request handling to the router.

// Ensure errors are visible in development (optional)
// ini_set('display_errors', '1');
// error_reporting(E_ALL);

require __DIR__ . '/../src/routes/web.php';
