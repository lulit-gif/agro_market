<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}
$pageClass = 'home'; 
require __DIR__ . '/../layout/header.php'; 
?>

<div style="padding: 40px 0;">
    <div class="container" style="max-width: 1000px;">
        
        <div style="margin-bottom: 40px;">
            <h1 style="margin-top: 0;">My Profile</h1>
            <p style="color: var(--muted);">Welcome back! Manage your account and orders</p>
        </div>
        
        <div style="display: grid; grid-template-columns: 250px 1fr; gap: 24px;">
            
            <!-- SIDEBAR MENU -->
            <div>
                <div class="card" style="padding: 0;">
                    <nav style="display: flex; flex-direction: column;">
                        <a href="/buyer/profile" style="padding: 12px 16px; border-bottom: 1px solid var(--line); color: var(--accent-dark); font-weight: 600;">Account</a>
                        <a href="/buyer/orders" style="padding: 12px 16px; color: var(--accent-dark);">My Orders</a>
                    </nav>
                </div>
                
                <div style="margin-top: 20px;">
                    <form method="post" action="/logout">
                        <button type="submit" class="btn btn-outline btn-full" style="width: 100%;">Logout</button>
                    </form>
                </div>
            </div>
            <!-- MAIN CONTENT -->
            <div>
                <div class="card">
                    <h2 style="margin-top: 0;">Account Information</h2>
                    
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
                    
                    <form method="post" action="/buyer/profile/update">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                required
                            >
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control" 
                                value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                                placeholder="Your full name"
                            >
                        </div>
                        
                        
                        
                        <h3 style="margin-top: 32px; margin-bottom: 16px;">Change Password</h3>
                        
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input 
                                type="password" 
                                name="current_password" 
                                class="form-control" 
                                placeholder="••••••••"
                            >
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input 
                                type="password" 
                                name="new_password" 
                                class="form-control" 
                                placeholder="••••••••"
                            >
                            <div class="form-help">Leave blank to keep current password</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
