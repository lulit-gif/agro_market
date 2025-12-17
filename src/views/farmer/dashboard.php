<?php require __DIR__ . '/../layout/farmer-header.php'; ?>

<div style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="margin: 0 0 8px;">Farmer Dashboard</h1>
            <p style="margin: 0; color: var(--muted);">Manage your products and orders</p>
        </div>
        <a href="/farmer/product/new" class="btn btn-primary">Add Product</a>
    </div>
    
    <!-- STATS -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 40px;">
        <div class="card">
            <h3 style="margin-top: 0; color: var(--muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">My Products</h3>
            <p style="font-size: 28px; font-weight: 700; margin: 8px 0; color: var(--accent);"><?php echo number_format($stats['products']); ?></p>
            <p style="margin: 0; font-size: 12px; color: var(--muted);">Active listings</p>
        </div>
        <div class="card">
            <h3 style="margin-top: 0; color: var(--muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Total Stock</h3>
            <p style="font-size: 28px; font-weight: 700; margin: 8px 0; color: var(--accent);"><?php echo number_format($stats['stock']); ?></p>
            <p style="margin: 0; font-size: 12px; color: var(--muted);">Units available</p>
        </div>
        <div class="card">
            <h3 style="margin-top: 0; color: var(--muted); font-size: 12px; text-transform: uppercase; font-weight: 700;">Items Sold</h3>
            <p style="font-size: 28px; font-weight: 700; margin: 8px 0; color: var(--accent);"><?php echo number_format($stats['orders']); ?></p>
            <p style="margin: 0; font-size: 12px; color: var(--muted);">Total orders</p>
        </div>
    </div>
    
    <!-- MY PRODUCTS -->
    <div class="card" style="margin-bottom: 32px;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;">My Products (<?php echo count($products); ?>)</h3>
            <a href="/farmer/product/new" class="btn btn-sm">Add</a>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--line);">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Product Name</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Price</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Stock</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr style="border-bottom: 1px solid var(--line);">
                            <td style="padding: 12px;"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td style="padding: 12px; font-weight: 600;">$<?php echo number_format((float)$product['price'], 2); ?></td>
                            <td style="padding: 12px;"><?php echo (int)$product['stock']; ?> units</td>
                            
                            <td style="padding: 12px;">
                                <a href="/farmer/product/edit?id=<?php echo (int)$product['id']; ?>" style="color: var(--accent); font-weight: 600; font-size: 12px;">Edit</a> |
                                <form method="post" action="/farmer/product/delete" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
                                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                                    <button type="submit" style="background: none; border: none; color: #c00; font-size: 12px; font-weight: 600; cursor: pointer; padding: 0;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($products)): ?>
            <p style="text-align: center; color: var(--muted); padding: 32px 0; margin: 0;">
                No products yet. <a href="/farmer/product/new" style="font-weight: 600;">Add your first product</a>
            </p>
        <?php endif; ?>
    </div>
    
    <!-- RECENT ORDERS -->
    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0;">Recent Orders</h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--line);">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Product</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Qty</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Customer</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Status</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr style="border-bottom: 1px solid var(--line);">
                            <td style="padding: 12px;"><?php echo htmlspecialchars($order['name']); ?></td>
                            <td style="padding: 12px;"><?php echo (int)$order['quantity']; ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($order['customer_name']); ?></td>
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
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
