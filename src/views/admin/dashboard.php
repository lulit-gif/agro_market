<?php require __DIR__ . '/../layout/admin-header.php'; ?>

<div style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="margin: 0 0 8px;">Admin Dashboard</h1>
            <p style="margin: 0; color: var(--muted);">Platform overview and management</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="/admin/users" class="btn btn-secondary">Manage Users</a>
            <a href="/admin/farmers" class="btn btn-primary">Manage Farmers</a>
        </div>
    </div>
    
    <!-- STAT CARDS -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 40px;">
        <div class="card">
            <h3 style="margin-top: 0; color: var(--muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Total Users</h3>
            <p style="font-size: 28px; font-weight: 700; margin: 8px 0; color: var(--accent);"><?php echo number_format($stats['users']); ?></p>
            <a href="/admin/users?filter=buyers" style="font-size: 12px; color: var(--accent-dark);">View all</a>
        </div>
        <div class="card">
            <h3 style="margin-top: 0; color: var(--muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Farmers</h3>
            <p style="font-size: 28px; font-weight: 700; margin: 8px 0; color: var(--accent);"><?php echo number_format($stats['farmers']); ?></p>
            <a href="/admin/users?filter=farmers" style="font-size: 12px; color: var(--accent-dark);">View all</a>
        </div>
        <div class="card">
            <h3 style="margin-top: 0; color: var(--muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Products</h3>
            <p style="font-size: 28px; font-weight: 700; margin: 8px 0; color: var(--accent);"><?php echo number_format($stats['products']); ?></p>
            <a href="/admin/products" style="font-size: 12px; color: var(--accent-dark);">Manage</a>
        </div>
        <div class="card">
            <h3 style="margin-top: 0; color: var(--muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Orders</h3>
            <p style="font-size: 28px; font-weight: 700; margin: 8px 0; color: var(--accent);"><?php echo number_format($stats['orders']); ?></p>
            <a href="/admin/orders" style="font-size: 12px; color: var(--accent-dark);">View all</a>
        </div>
    </div>
    
    <!-- QUICK ACTIONS -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 40px;">
        <a href="/admin/users" class="card" style="text-decoration: none; color: inherit; cursor: pointer;">
            <h3 style="margin: 0 0 8px; color: var(--accent); font-size: 16px;">Manage Users</h3>
            <p style="margin: 0; color: var(--muted); font-size: 13px;">View and manage all user accounts</p>
        </a>
        <a href="/admin/farmers" class="card" style="text-decoration: none; color: inherit; cursor: pointer;">
            <h3 style="margin: 0 0 8px; color: var(--accent); font-size: 16px;">Manage Farmers</h3>
            <p style="margin: 0; color: var(--muted); font-size: 13px;">Add and manage farmer accounts</p>
        </a>
        <a href="/admin/products" class="card" style="text-decoration: none; color: inherit; cursor: pointer;">
            <h3 style="margin: 0 0 8px; color: var(--accent); font-size: 16px;">Approve Products</h3>
            <p style="margin: 0; color: var(--muted); font-size: 13px;">Review pending product listings</p>
        </a>
        <a href="/admin/orders" class="card" style="text-decoration: none; color: inherit; cursor: pointer;">
            <h3 style="margin: 0 0 8px; color: var(--accent); font-size: 16px;">View Orders</h3>
            <p style="margin: 0; color: var(--muted); font-size: 13px;">Monitor all customer orders</p>
        </a>
    </div>
    
    <!-- RECENT ORDERS -->
    <div class="card" style="margin-bottom: 32px;">
        <div class="card-header">
            <h3 style="margin: 0;">Recent Orders</h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--line);">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Order ID</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Customer</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Items</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr style="border-bottom: 1px solid var(--line);">
                            <td style="padding: 12px;"><a href="/admin/orders" style="color: var(--accent); font-weight: 600;">#<?php echo (int)$order['id']; ?></a></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td style="padding: 12px;"><?php echo (int)$order['item_count']; ?> items</td>
                            <td style="padding: 12px;">
                                <span style="background: var(--bg-cream-2); color: var(--text-dark); padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 11px; text-transform: capitalize;">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </td>
                            <td style="padding: 12px; color: var(--muted);"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (empty($recentOrders)): ?>
            <p style="text-align: center; color: var(--muted); padding: 32px 0; margin: 0;">No orders yet</p>
        <?php endif; ?>
    </div>
    
    <!-- RECENT PRODUCTS -->
    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0;">Recently Added Products</h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--line);">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Product Name</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Farmer</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Price</th>
                        
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentProducts as $product): ?>
                        <tr style="border-bottom: 1px solid var(--line);">
                            <td style="padding: 12px;"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td style="padding: 12px; color: var(--muted); font-size: 12px;"><?php echo htmlspecialchars($product['farmer_email']); ?></td>
                            <td style="padding: 12px; font-weight: 600;">$<?php echo number_format((float)$product['price'], 2); ?></td>
                            
                            <td style="padding: 12px; color: var(--muted);"><?php echo date('M d, Y', strtotime($product['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (empty($recentProducts)): ?>
            <p style="text-align: center; color: var(--muted); padding: 32px 0; margin: 0;">No products yet</p>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
