<?php require __DIR__ . '/../layout/admin-header.php'; ?>

<div style="padding: 40px 0;">
    <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h1 style="margin:0;">Manage Orders</h1>
        <a href="/admin/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
    
    <!-- STATUS FILTER -->
    <div style="display: flex; gap: 12px; margin-bottom: 32px; border-bottom: 2px solid var(--line); padding-bottom: 12px;">
        <a href="/admin/orders?status=all" style="padding: 8px 16px; border-bottom: 3px solid <?php echo ($_GET['status'] ?? 'all') === 'all' ? 'var(--accent)' : 'transparent'; ?>; color: inherit; text-decoration: none; font-weight: 600; font-size: 14px;">All</a>
        <a href="/admin/orders?status=pending" style="padding: 8px 16px; border-bottom: 3px solid <?php echo ($_GET['status'] ?? 'all') === 'pending' ? 'var(--accent)' : 'transparent'; ?>; color: inherit; text-decoration: none; font-weight: 600; font-size: 14px;">Pending</a>
        <a href="/admin/orders?status=processing" style="padding: 8px 16px; border-bottom: 3px solid <?php echo ($_GET['status'] ?? 'all') === 'processing' ? 'var(--accent)' : 'transparent'; ?>; color: inherit; text-decoration: none; font-weight: 600; font-size: 14px;">Processing</a>
        <a href="/admin/orders?status=shipped" style="padding: 8px 16px; border-bottom: 3px solid <?php echo ($_GET['status'] ?? 'all') === 'shipped' ? 'var(--accent)' : 'transparent'; ?>; color: inherit; text-decoration: none; font-weight: 600; font-size: 14px;">Shipped</a>
        <a href="/admin/orders?status=delivered" style="padding: 8px 16px; border-bottom: 3px solid <?php echo ($_GET['status'] ?? 'all') === 'delivered' ? 'var(--accent)' : 'transparent'; ?>; color: inherit; text-decoration: none; font-weight: 600; font-size: 14px;">Delivered</a>
    </div>
    
    <!-- ORDERS TABLE -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--line);">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Order ID</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Customer</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Phone</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Total</th>
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
                            <td style="padding: 12px; font-size: 12px; color: var(--muted);"><?php echo htmlspecialchars($order['customer_phone']); ?></td>
                            <td style="padding: 12px; font-weight: 600;">$<?php echo number_format((float)$order['total'], 2); ?></td>
                            <td style="padding: 12px;">
                                <form method="post" action="/admin/order/update-status" style="display: inline-flex; gap: 6px; align-items: center;">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
                                    <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
                                    <select name="status" class="form-control" style="padding: 6px; font-size: 12px;">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm" style="padding: 6px 10px; font-size: 11px;">Update</button>
                                </form>
                            </td>
                            <td style="padding: 12px; color: var(--muted);"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td style="padding: 12px;">
                                <a href="/admin/order/view?id=<?php echo (int)$order['id']; ?>" style="color: var(--accent); font-weight: 600; font-size: 12px;">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($orders)): ?>
            <p style="text-align: center; color: var(--muted); padding: 32px 0; margin: 0;">No orders found</p>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
