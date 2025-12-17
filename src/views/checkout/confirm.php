<?php
require __DIR__ . '/../layout/header.php';
?>

<div style="padding: 40px 0;">
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="width: 60px; height: 60px; background-color: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 32px; font-weight: bold;">✓</div>
            <h1 style="margin: 0 0 12px;">Order Confirmed</h1>
            <p style="color: var(--muted);">Thank you for your purchase!</p>
        </div>
        
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3 style="margin: 0;">Order Details</h3>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 13px;">
                <div>
                    <p style="color: var(--muted); margin-bottom: 4px;">Order Number</p>
                    <p style="font-weight: 600; margin: 0;"><?php echo (int)$order['id']; ?></p>
                </div>
                <div>
                    <p style="color: var(--muted); margin-bottom: 4px;">Order Date</p>
                    <p style="font-weight: 600; margin: 0;"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                </div>
                <div>
                    <p style="color: var(--muted); margin-bottom: 4px;">Name</p>
                    <p style="font-weight: 600; margin: 0;"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                </div>
                <div>
                    <p style="color: var(--muted); margin-bottom: 4px;">Status</p>
                    <p style="font-weight: 600; margin: 0; text-transform: capitalize;"><?php echo htmlspecialchars($order['status']); ?></p>
                </div>
            </div>
        </div>
        
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3 style="margin: 0;">Items Ordered</h3>
            </div>
            
            <?php foreach ($items as $item): ?>
                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--line); font-size: 13px;">
                    <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo (int)$item['quantity']; ?></span>
                    <span>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3 style="margin: 0;">Shipping Address</h3>
            </div>
            
            <p style="font-size: 13px; margin: 0; line-height: 1.8;">
                <?php echo htmlspecialchars($order['customer_name']); ?><br>
                <?php echo htmlspecialchars($order['customer_address']); ?><br>
                <?php echo htmlspecialchars($order['customer_phone']); ?><br>
                <strong>Total:</strong> $<?php echo number_format((float)$order['total'], 2); ?>
            </p>
        </div>
        
        <div style="display: flex; gap: 12px; margin-top: 32px;">
            <a href="/buyer/orders" class="btn btn-primary" style="flex: 1; text-align: center;">View My Orders</a>
            <a href="/products" class="btn btn-secondary" style="flex: 1; text-align: center;">Continue Shopping</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
