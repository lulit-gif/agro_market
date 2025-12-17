<?php require __DIR__ . '/../layout/admin-header.php'; ?>

<div style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="margin: 0;">Manage Users</h1>
        <a href="/admin/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
    
    <!-- FILTERS & SEARCH -->
    <div class="card" style="margin-bottom: 32px; display: flex; gap: 12px; flex-wrap: wrap;">
        <form method="get" action="/admin/users" style="display: flex; gap: 12px; flex-wrap: wrap; width: 100%;">
            <input type="text" name="search" placeholder="Search by email..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>" class="form-control" style="flex: 1; min-width: 200px;">
            
            <select name="filter" class="form-control" style="min-width: 150px;">
                <option value="all" <?php echo ($_GET['filter'] ?? 'all') === 'all' ? 'selected' : ''; ?>>All Users</option>
                <option value="buyers" <?php echo ($_GET['filter'] ?? 'all') === 'buyers' ? 'selected' : ''; ?>>Buyers Only</option>
                <option value="farmers" <?php echo ($_GET['filter'] ?? 'all') === 'farmers' ? 'selected' : ''; ?>>Farmers Only</option>
            </select>
            
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="/admin/users" class="btn btn-secondary">Clear</a>
        </form>
    </div>
    
    <!-- USERS TABLE -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--line);">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Email</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Role</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Joined</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr style="border-bottom: 1px solid var(--line);">
                            <td style="padding: 12px;"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td style="padding: 12px;">
                                <span style="background: var(--bg-cream-2); color: var(--text-dark); padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 11px; text-transform: capitalize;">
                                    <?php echo htmlspecialchars($user['role']); ?>
                                </span>
                            </td>
                            <td style="padding: 12px; color: var(--muted);"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td style="padding: 12px;">
                                <a href="#" style="color: var(--accent); font-weight: 600; font-size: 12px;">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($users)): ?>
            <p style="text-align: center; color: var(--muted); padding: 32px 0; margin: 0;">No users found</p>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
