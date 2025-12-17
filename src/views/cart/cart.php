<?php
$pageClass = 'products';
$pageTitle = 'Shopping Cart - Agro Market';
require __DIR__ . '/../layout/header.php';
?>

<div style="padding: 40px 0;">
    <h1>Shopping Cart</h1>
    
    <?php if (!empty($items) && count($items) > 0): ?>
        
        <div style="display: grid; grid-template-columns: 1fr 320px; gap: 32px; margin-top: 32px;">
            
            <!-- CART ITEMS LIST -->
            <div>
                <div style="background-color: var(--white); border-radius: 8px; border: 1px solid var(--line); overflow: hidden;">
                    
                    <?php foreach ($items as $idx => $item): ?>
                        <div style="display: grid; grid-template-columns: 100px 1fr auto; gap: 20px; padding: 20px; border-bottom: 1px solid var(--line); align-items: center;">
                            
                            <!-- PRODUCT IMAGE -->
                            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--bg-cream) 0%, #ffe0b2 100%); border-radius: 6px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="/img/products/<?php echo htmlspecialchars($item['image']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            
                            <!-- PRODUCT DETAILS -->
                            <div>
                                <h3 style="margin: 0 0 8px; font-size: 15px;"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="price" style="margin: 0; color: var(--accent-dark); font-weight: 700; font-size: 16px;">$<?php echo htmlspecialchars(number_format($item['price'], 2)); ?> each</p>
                            </div>
                            
                            <!-- QUANTITY & REMOVE -->
                            <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                                <form method="post" action="/cart/update" style="display: flex; gap: 6px; align-items: center;">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
                                    <input type="number" name="qty[<?php echo (int)$item['id']; ?>]" value="<?php echo (int)$item['qty']; ?>" min="1" max="999" class="form-control" style="width: 60px; padding: 8px;">
                                    <button type="submit" class="btn btn-sm" style="padding: 8px 12px;">Update</button>
                                </form>
                                
                                <form method="post" action="/cart/update" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
                                    <input type="hidden" name="qty[<?php echo (int)$item['id']; ?>]" value="0">
                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 8px 12px; font-size: 12px; background-color: #f44336;">Remove</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- ORDER SUMMARY -->
            <div style="height: fit-content; position: sticky; top: 100px;">
                <div class="card">
                    <h3 style="margin-top: 0;">Order Summary</h3>
                    
                    <?php
                    $subtotal = array_sum(array_map(fn($item) => $item['line_total'], $items));
                    $shipping = 5.00;
                    $tax = $subtotal * 0.1;
                    $total = $subtotal + $shipping + $tax;
                    ?>
                    
                    <div style="font-size: 13px; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between;">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    
                    <div style="font-size: 13px; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between;">
                        <span>Shipping</span>
                        <span>$<?php echo number_format($shipping, 2); ?></span>
                    </div>
                    
                    <div style="font-size: 13px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px solid var(--line); display: flex; justify-content: space-between;">
                        <span>Tax (10%)</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    
                    <div style="font-size: 16px; font-weight: 700; margin-bottom: 24px; display: flex; justify-content: space-between;">
                        <span>Total</span>
                        <span style="color: var(--accent);">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <a href="/checkout" class="btn btn-primary btn-full" style="display: block; text-align: center; margin-bottom: 12px;">Proceed to Checkout</a>
                    <a href="/products" class="btn btn-secondary btn-full" style="display: block; text-align: center;">Continue Shopping</a>
                </div>
            </div>
        </div>
        
    <?php else: ?>
        <div style="text-align: center; padding: 80px 20px; background-color: var(--white); border-radius: 8px; border: 1px solid var(--line); margin-top: 32px;">
            <h2 style="color: var(--muted); margin-bottom: 12px;">Your cart is empty</h2>
            <p style="color: var(--muted); margin-bottom: 24px;">Start shopping for fresh local produce</p>
            <a href="/products" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
