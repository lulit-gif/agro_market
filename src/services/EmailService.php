<?php
// Service: EmailService.php
// Minimal email helper. In production replace with a proper mailer (PHPMailer, Symfony Mailer, etc.).

class EmailService
{
	// Send a password reset email. Returns true on success.
	public static function sendPasswordReset(string $toEmail, string $resetLink): bool
	{
		$subject = 'Password reset request';
		$message = "Hello,\n\nWe received a request to reset your password.\n" .
			"Click this link to reset your password:\n\n" . $resetLink . "\n\n" .
			"If you did not request this, ignore this email.\n";
		$headers = 'From: no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n" .
			'Reply-To: no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		// Try PHP mail() first
		if (function_exists('mail')) {
			$ok = @mail($toEmail, $subject, $message, $headers);
			if ($ok) {
				return true;
			}
		}

		// Fallback: write to a local log file for development
		$logLine = date('c') . " | PASSWORD RESET | to={$toEmail} | link={$resetLink}\n";
		file_put_contents(__DIR__ . '/../../logs/email.log', $logLine, FILE_APPEND | LOCK_EX);
		return false;
	}
}

?>
