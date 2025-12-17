<?php
$pageClass = 'auth';
$pageTitle = 'Sign In - Agro Market';
require __DIR__ . '/../layout/header.php';
?>

<div style="display: flex; align-items: center; justify-content: center; min-height: 70vh; padding: 40px 20px;">
    <div class="card" style="max-width: 400px; width: 100%;">
        <h1 style="text-align: center; margin-top: 0;">Welcome Back</h1>
        <p style="text-align: center; color: var(--muted); margin-bottom: 28px;">Sign in to your account</p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <span style="font-size: 16px;">!</span>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <form method="post" action="/login">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required autofocus>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <label style="font-size: 13px; display: flex; align-items: center; gap: 6px;">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="/forgot-password" style="font-size: 13px;">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Sign In</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; font-size: 13px;">
            Don't have an account? <a href="/register" style="font-weight: 600;">Create one</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
