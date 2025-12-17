<?php
require __DIR__ . '/../layout/header.php';
?>

<div style="padding: 40px 0;">
    <h1>Checkout</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <span>!</span>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 32px; margin-top: 32px;">
        
        <!-- CHECKOUT FORM -->
        <div>
            <form method="post" action="/checkout/submit">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES); ?>">
                
                <!-- SHIPPING DETAILS -->
                <div class="card">
                    <div class="card-header">
                        <h3 style="margin: 0;">Shipping Address</h3>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Street Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <!-- PAYMENT METHOD -->
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header">
                        <h3 style="margin: 0;">Payment Method</h3>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                            <input type="radio" name="payment_method" value="credit_card" checked>
                            <span style="font-weight: 600;">Credit Card</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                            <input type="radio" name="payment_method" value="paypal">
                            <span style="font-weight: 600;">PayPal</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                            <input type="radio" name="payment_method" value="bank_transfer">
                            <span style="font-weight: 600;">Bank Transfer</span>
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-full" style="margin-top: 20px; padding: 14px;">Complete Purchase</button>
            </form>
        </div>
        
        <!-- ORDER SUMMARY SIDEBAR -->
        <div style="height: fit-content; position: sticky; top: 100px;">
            <div class="card">
                <h3 style="margin-top: 0;">Order Summary</h3>
                
                <?php foreach ($cartItems as $item): ?>
                    <div style="font-size: 13px; margin-bottom: 8px; display: flex; justify-content: space-between;">
                        <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['qty']; ?></span>
                        <span>$<?php echo number_format($item['line_total'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
                
                <div style="border-top: 1px solid var(--line); margin: 12px 0; padding-top: 12px; font-size: 13px; display: flex; justify-content: space-between;">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                
                <div style="font-size: 13px; margin-bottom: 8px; display: flex; justify-content: space-between;">
                    <span>Shipping</span>
                    <span>$<?php echo number_format($shipping, 2); ?></span>
                </div>
                
                <div style="font-size: 13px; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 2px solid var(--line); display: flex; justify-content: space-between;">
                    <span>Tax (10%)</span>
                    <span>$<?php echo number_format($tax, 2); ?></span>
                </div>
                
                <div style="font-size: 16px; font-weight: 700; display: flex; justify-content: space-between;">
                    <span>Total</span>
                    <span style="color: var(--accent);">$<?php echo number_format($total, 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
