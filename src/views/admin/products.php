<?php require __DIR__ . '/../layout/admin-header.php'; ?>

<div style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <h1 style="margin: 0;">Manage Products</h1>
        <a href="/admin/dashboard" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
    
    <!-- INFO -->
    <p style="color: var(--muted); margin-bottom: 16px;">Latest products from all farmers</p>
    
    <!-- PRODUCTS TABLE -->
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--line);">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Product Name</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Farmer</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Price</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Stock</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr style="border-bottom: 1px solid var(--line);">
                            <td style="padding: 12px;"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td style="padding: 12px; font-size: 12px; color: var(--muted);"><?php echo htmlspecialchars($product['farmer_email']); ?></td>
                            <td style="padding: 12px; font-weight: 600;">$<?php echo number_format((float)$product['price'], 2); ?></td>
                            <td style="padding: 12px;"><?php echo (int)$product['stock']; ?> units</td>
                            <td style="padding: 12px; color: var(--muted);"><?php echo date('M d', strtotime($product['created_at'])); ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($products)): ?>
            <p style="text-align: center; color: var(--muted); padding: 32px 0; margin: 0;">No products in this status</p>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
