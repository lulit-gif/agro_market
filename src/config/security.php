<?php
// Security settings (CSRF, input sanitization)


function security_start_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Generate or return existing CSRF token for the current session.
 *
 * @return string
 */
function csrf_token()
{
    security_start_session();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Verify a submitted CSRF token from a form.
 *
 * @param string|null $token Token received from POST/GET.
 * @return bool
 */
function csrf_verify($token)
{
    security_start_session();

    if (empty($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Sanitize a string from user input (basic).
 *
 * @param string|null $value
 * @return string
 */
function sanitize_string($value)
{
    $value = (string)$value;
    $value = trim($value);
    // FILTER_SANITIZE_STRING is deprecated; perform a conservative cleanup
    $value = strip_tags($value);
    return $value;
}

/**
 * Escape output for safe HTML display.
 *
 * @param string|null $value
 * @return string
 */
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

?>
