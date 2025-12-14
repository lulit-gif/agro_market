<?php require __DIR__ . '/../layout/header.php'; ?>

<h2>Reset Password</h2>
<form method="POST" action="reset-password.php" class="auth-form">
    <label>New password:
        <input type="password" name="password" required />
    </label>
    <button type="submit">Reset password</button>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<?php
// Auth view: reset-password.php
$reset_token = isset($_GET['token']) ? htmlspecialchars($_GET['token'] ,ENT_QUOTES, 'UTF-8') : '';
?>
