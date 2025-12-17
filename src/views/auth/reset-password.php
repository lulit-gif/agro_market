<?php $pageClass = 'auth'; require __DIR__ . '/../layout/header.php'; ?>

<div style="display: flex; align-items: center; justify-content: center; min-height: 80vh; background-color: var(--bg-cream); padding: 20px;">
    <div class="card" style="max-width: 400px; width: 100%;">
        <h1 style="text-align: center; margin-top: 0;">Reset Password</h1>
        <p style="text-align: center; color: var(--muted); margin-bottom: 24px;">Enter your new password below</p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <span>⚠️</span>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>
        
        <form method="post" action="/reset-password">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES); ?>">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES); ?>">
            
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="••••••••" 
                    required
                >
                <div class="form-help">At least 8 characters</div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirm" 
                    class="form-control" 
                    placeholder="••••••••" 
                    required
                >
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Reset Password</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px; color: var(--muted);">
            <a href="/login">← Back to Sign In</a>
        </p>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
