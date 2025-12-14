<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../services/TokenService.php';
require_once __DIR__ . '/../services/EmailService.php';

class AuthController
{
	// Show forgot password form (view handles token removal from URL if needed)
	public static function showForgotForm()
	{
		/*
		TODO: Render the "forgot password" view.
		*/
	    session_start();
        //Generate CSRF token if missing

		if(empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex (random_bytes(32));
		}
		//Get flash messages(set by handleFogot)
		$error = $_SESSION['flash']['error'] ?? null;
		$success = $_SESSION['flash']['success'] ?? null;
		unset($_SESSION['flash']);//clear flash messages after reading

		extract([
			'csrf_token' => $_SESSION['csrf_token'],
			'error' => $error, 
			'success' => $success

		]);
		require __DIR__ . '/../views/auth/forgot-password.php';
		
	}


	// Handle forgot password POST: generate token, store hash, send email
	public static function handleForgot()
	{
		/*
		TODO: Implement forgot-password POST handler.

		Requirements and implementation steps:
		*/
		if($_SERVER['REQUEST_METHOD'] !== 'POST'){
			header('Location: /agro_market/forgot-password', true, 302);
			exit;
		}	
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		if(!$email){
			$_SESSION['flash']['error'] = "Please provide a valid email address.";
			header('Location: /agro_market/forgot-password', true, 302);
			exit;
		}
        //Rate limiting (simple IP-based example)
		$ip = $_SERVER['REMOTE_ADDR'];
        $rateLimitKey = 'forgot_attempts_'.md5($ip);
		$attempts = $_SESSION[$rateLimitKey]['count'] ?? 0;
		if($attempts >= 5){
			$_SESSION['flash']['error'] = "Too many requests. Please try again later.";
			header('Location: /agro_market/forgot-password', true, 302);
			exit;
		}
		$_SESSION[$rateLimitKey]['count'] = $attempts + 1;

		$pdo = get_db();

		//check if user exists,but do not reveal this info
		$stmt = $pdo -> prepare('SELECT id,email FROM users WHERE email = ?');
		$stmt -> execute([$email]);

		$user = $stmt -> fetch();
		if(!$user){
			//generate secure token
			$tokenData = TokenService::generateToken();
			$rawToken = $tokenData['token'];
			$tokenHash = $tokenData['hash'];

			//1 hour expiry
			$expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour')); 
			
			//store reset token
			$stmt = $pdo -> prepare(
			'INSERT INTO password_resets (user_id, token_hash, expirs_at, used, created_at)
			VALUES (?, ?, ?, 0, NOW())
			ON DUPLICATE KEY UPDATE 
			token_hash = VALUES(token_hash),
			expires_at = VALUES(expires_at),
			used = 0,
			created_at = VALUES(created_at)
			');

			//Build reset url
			$resetUrl = 'https://'.$_SERVER['HTTP_HOST'] . '/reset-password?token=' . $rawToken;

			 // Send email
            EmailService::sendPasswordReset($user['email'], $resetUrl);
        }

        $_SESSION['flash']['success'] = 'If an account exists, check your email for reset instructions.';
        header('Location: /forgot-password');
        exit;
    }


		
	

	// Show reset form (the view will pick the token from GET and show the form)
	public static function showResetForm()
	{
      if(session_status() === PHP_SESSION_NONE){
		session_start();
	  }
	  //Ensure CSRF token
	  if(empty($_SESSION['csrf_token'])){
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

		$token = $_GET['token'] ?? '';
		$error = $_SESSION['flash']['error'] ?? null;
		unset($_SESSION['flash']);//clear flash after reading

         $csrf_token = $_SESSION['csrf_token'];

    // Make variables available in the view
    // $csrf_token, $token, $error
    require __DIR__ . '/../views/auth/reset-password.php';
	}
}

	// Handle reset POST: validate token, set new password, mark token used
	public static function handleReset()
	{
		 if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /reset-password');
            exit;
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($token) || empty($password) || $password !== $passwordConfirm) {
            $_SESSION['flash']['error'] = 'Please fill all fields and ensure passwords match.';
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['flash']['error'] = 'Password must be at least 8 characters long.';
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        $pdo = get_db();
        $tokenHash = TokenService::hashToken($token);

        // Validate token
        $stmt = $pdo->prepare('
            SELECT pr.user_id, pr.expires_at 
            FROM password_resets pr 
            WHERE pr.token_hash = ? AND pr.used = 0 AND pr.expires_at > NOW()
        ');
        $stmt->execute([$tokenHash]);
        $reset = $stmt->fetch();

        if (!$reset) {
            $_SESSION['flash']['error'] = 'Invalid or expired reset link. Please request a new one.';
            header('Location: /forgot-password');
            exit;
        }

        // Update password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$passwordHash, $reset['user_id']]);

        // Mark token as used
        $stmt = $pdo->prepare('UPDATE password_resets SET used = 1, used_at = NOW() WHERE token_hash = ?');
        $stmt->execute([$tokenHash]);

        $_SESSION['flash']['success'] = 'Password reset successfully. Please log in.';
        header('Location: /login');
        exit;
    }
	// ... your existing forgot/reset methods ...

    public static function showLoginForm()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error   = $_SESSION['flash']['error']   ?? null;
        $success = $_SESSION['flash']['success'] ?? null;
        unset($_SESSION['flash']);

        require __DIR__ . '/../views/auth/login.php';
    }

    public static function handleLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || $password === '') {
            $_SESSION['flash']['error'] = 'Invalid credentials.';
            header('Location: /login');
            exit;
        }

        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['flash']['error'] = 'Invalid credentials.';
            header('Location: /login');
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        header('Location: /');
        exit;
    }

    public static function showRegisterForm()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error   = $_SESSION['flash']['error']   ?? null;
        $success = $_SESSION['flash']['success'] ?? null;
        unset($_SESSION['flash']);

        require __DIR__ . '/../views/auth/register.php';
    }

    public static function handleRegister()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        $errors = [];

        if (!$email) {
            $errors[] = 'Invalid email address.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            $_SESSION['flash']['error'] = implode('<br>', $errors);
            header('Location: /register');
            exit;
        }

        $pdo = get_db();
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['flash']['error'] = 'Account already exists.';
            header('Location: /register');
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (email, password, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([$email, $hash]);

        $_SESSION['flash']['success'] = 'Account created. Please log in.';
        header('Location: /login');
        exit;
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header('Location: /login');
        exit;
    }

		
}


?>
