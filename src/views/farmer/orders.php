<?php require __DIR__ . '/../layout/farmer-header.php'; ?>

<div style="padding: 40px 0;">
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1 style="margin:0;">My Orders</h1>
        <a href="/farmer/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
    
    <!-- STATUS FILTER -->
    <div style="display: flex; gap: 12px; margin-bottom: 32px; border-bottom: 2px solid var(--line); padding-bottom: 12px;">
        <a href="/farmer/orders?status=all" style="padding: 8px 16px; border-bottom: 3px solid <?php echo ($_GET['status'] ?? 'all') === 'all' ? 'var(--accent)' : 'transparent'; ?>; color: inherit; text-decoration: none; font-weight: 600; font-size: 14px;">All</a>
        <a href="/farmer/orders?status=pending" style="padding: 8px 16px; border-bottom: 3px solid <?php echo ($_GET['status'] ?? 'all') === 'pending' ? 'var(--accent)' : 'transparent'; ?>; color: inherit; text-decoration: none; font-weight: 600; font-size: 14px;">Pending</a>
        <a href="/farmer/orders?status=processing" style="padding: 8px 16px; border-bottom: 3px solid <?php echo ($_GET['status'] ?? 'all') === 'processing' ? 'var(--accent)' : 'transparent'; ?>; color: inherit; text-decoration: none; font-weight: 600; font-size: 14px;">Processing</a>
        <a href="/farmer/orders?status=delivered" style="padding: 8px 16px; border-bottom: 3px solid <?php echo ($_GET['status'] ?? 'all') === 'delivered' ? 'var(--accent)' : 'transparent'; ?>; color: inherit; text-decoration: none; font-weight: 600; font-size: 14px;">Delivered</a>
    </div>
    
    <!-- ORDERS TABLE -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--line);">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Order ID</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Customer</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Items</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr style="border-bottom: 1px solid var(--line);">
                            <td style="padding: 12px; font-weight: 600;">#<?php echo (int)$order['id']; ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td style="padding: 12px;"><?php echo (int)$order['item_count']; ?> items</td>
                            <td style="padding: 12px;">
                                <span style="background: var(--bg-cream-2); color: var(--text-dark); padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 11px; text-transform: capitalize;">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </td>
                            <td style="padding: 12px; color: var(--muted);"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td style="padding: 12px;">
                                <a href="/farmer/order/view?id=<?php echo (int)$order['id']; ?>" style="color: var(--accent); font-weight: 600; font-size: 12px;">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($orders)): ?>
            <p style="text-align: center; color: var(--muted); padding: 32px 0; margin: 0;">No orders yet</p>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
