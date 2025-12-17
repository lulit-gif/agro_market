<?php $pageClass = 'auth'; require __DIR__ . '/../layout/header.php'; ?>

<div style="display: flex; align-items: center; justify-content: center; min-height: 80vh; background-color: var(--bg-cream); padding: 20px;">
    <div class="card" style="max-width: 400px; width: 100%;">
        <h1 style="text-align: center; margin-top: 0;">Forgot Password?</h1>
        <p style="text-align: center; color: var(--muted); margin-bottom: 24px;">We'll send you a link to reset your password</p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <span>⚠️</span>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>
        
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <span>✓</span>
                <div><?php echo htmlspecialchars($success); ?></div>
            </div>
        <?php endif; ?>
        
        <form method="post" action="/forgot-password">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES); ?>">
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="you@example.com" 
                    required 
                    autofocus
                >
                <div class="form-help">Enter the email associated with your account</div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Send Reset Link</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; color: var(--muted);">
            <a href="/login">← Back to Sign In</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
