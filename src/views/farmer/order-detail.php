<?php require __DIR__ . '/../layout/farmer-header.php'; ?>

<div style="padding: 40px 0;">
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 16px;">
        <a href="/farmer/orders" style="color: var(--accent-dark); font-weight: 600;">← Back to Orders</a>
        <a href="/farmer/dashboard" class="btn btn-secondary">Back to Dashboard</a>
    </div>
    
    <div style="max-width: 700px;">
        <h1 style="margin-bottom: 32px;">Order #<?php echo (int)$order['id']; ?></h1>
        
        <!-- ORDER STATUS & INFO -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3 style="margin: 0;">Order Information</h3>
                <div>
                    <p style="color: var(--muted); margin-bottom: 4px;">Phone</p>
                    <p style="font-weight: 600; margin: 0;">&nbsp;<?php echo htmlspecialchars($order['customer_phone']); ?></p>
                </div>
                    <p style="color: var(--muted); margin-bottom: 4px;">Customer Name</p>
                    <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                </div>
                <div>
                    <p style="color: var(--muted); margin-bottom: 4px;">Email</p>
                    <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($order['customer_email']); ?></p>
                </div>
                <div>
                    <p style="color: var(--muted); margin-bottom: 4px;">Status</p>
                    <p style="margin: 0;">
                        <span style="background: var(--bg-cream-2); color: var(--text-dark); padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 11px; text-transform: capitalize;">
                            <?php echo htmlspecialchars($order['status']); ?>
                        </span>
                    </p>
                </div>
                <div>
                    <p style="color: var(--muted); margin-bottom: 4px;">Order Date</p>
                    <p style="font-weight: 600; margin: 0;"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                </div>
            </div>
        </div>
        
        <!-- ORDER ITEMS -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3 style="margin: 0;">Your Items in This Order</h3>
            </div>
            
            <?php foreach ($items as $item): ?>
                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--line); font-size: 13px;">
                    <div>
                        <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($item['name']); ?></p>
                        <p style="color: var(--muted); margin: 0;">Qty: <?php echo (int)$item['quantity']; ?></p>
                    </div>
                    <p style="font-weight: 600; margin: 0;">$<?php echo number_format((float)$item['quantity'] * $item['price'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- SHIPPING ADDRESS -->
        <div class="card">
            <div class="card-header">
                <h3 style="margin: 0;">Shipping Address</h3>
            </div>
            
            <p style="font-size: 13px; margin: 0; line-height: 1.8;">
                <?php echo htmlspecialchars($order['customer_name']); ?><br>
                <?php echo htmlspecialchars($order['customer_address']); ?><br>
                <?php echo htmlspecialchars($order['customer_phone']); ?>
            </p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
