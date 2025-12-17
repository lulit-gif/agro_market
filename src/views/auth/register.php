<?php
$pageClass = 'auth';
$pageTitle = 'Create Account - Agro Market';
require __DIR__ . '/../layout/header.php';
?>

<div style="display: flex; align-items: center; justify-content: center; min-height: 70vh; padding: 40px 20px;">
    <div class="card" style="max-width: 400px; width: 100%;">
        <h1 style="text-align: center; margin-top: 0;">Create Account</h1>
        <p style="text-align: center; color: var(--muted); margin-bottom: 28px;">Join us for fresh local produce</p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <span style="font-size: 16px;">!</span>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <form method="post" action="/register">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required autofocus>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="At least 8 characters" required>
                <div class="form-help">Must be at least 8 characters</div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirm" class="form-control" placeholder="Confirm password" required>
            </div>
            
            <div style="margin-bottom: 24px; font-size: 13px;">
                <label style="display: flex; align-items: flex-start; gap: 8px;">
                    <input type="checkbox" name="terms" required>
                    <span>I agree to the <a href="/terms">Terms of Service</a> and <a href="/privacy">Privacy Policy</a></span>
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Create Account</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; font-size: 13px;">
            Already have an account? <a href="/login" style="font-weight: 600;">Sign in</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
