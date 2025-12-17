<?php
require __DIR__ . '/../layout/header.php';
$error = $_SESSION['error'] ?? $error ?? null; // surface any session error
unset($_SESSION['error']);
?>

<div style="display: flex; align-items: center; justify-content: center; min-height: 70vh; padding: 40px 20px;">
    <div class="card" style="max-width: 400px; width: 100%;">
        <h1 style="text-align: center; margin-top: 0;">Admin Login</h1>
        <p style="text-align: center; color: var(--muted); margin-bottom: 28px;">Access admin dashboard</p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <span>!</span>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <form method="post" action="/admin/login">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="admin@example.com" required autofocus>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Sign In</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
