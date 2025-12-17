<?php
$pageClass = 'products';
$pageTitle = ($product['name'] ?? 'Product') . ' - Agro Market';
require __DIR__ . '/../layout/header.php';
?>

<div style="padding: 40px 0;">
    <a href="/products" style="color: var(--accent-dark); font-weight: 600; margin-bottom: 24px; display: inline-block;">← Back to Products</a>
    
    <?php if (!empty($product)): ?>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: start;">
            
            <!-- PRODUCT IMAGE page -->
            <div style="background: linear-gradient(135deg, var(--bg-cream) 0%, #ffe0b2 100%); border-radius: 8px; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <?php if (!empty($product['image'])): ?>
                    <img src="/img/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php endif; ?>
            </div>
            
            <!-- PRODUCT INFO -->
            <div>
                <h1 style="margin-top: 0;"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <p class="product-price" style="font-size: 28px; margin: 16px 0;">$<?php echo htmlspecialchars(number_format((float)$product['price'], 2)); ?></p>
                
                <?php if (!empty($product['description'])): ?>
                    <h3 style="margin-top: 24px;">Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <?php endif; ?>
                
                <!-- ADD TO CART FORM -->
                <form method="post" action="/cart/add" style="margin: 32px 0;">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                    
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" max="999" class="form-control" style="max-width: 120px;">
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="padding: 14px 32px; font-size: 15px;">Add to Cart</button>
                </form>
                
                <!-- PRODUCT META -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 24px 0; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); margin: 32px 0;">
                    <div>
                        <h4 style="color: var(--text-brown); margin-bottom: 8px; font-size: 13px; text-transform: uppercase; font-weight: 600;">Fresh & Local</h4>
                        <p style="margin: 0; color: var(--muted); font-size: 13px;">From nearby farms</p>
                    </div>
                    <div>
                        <h4 style="color: var(--text-brown); margin-bottom: 8px; font-size: 13px; text-transform: uppercase; font-weight: 600;">Fast Delivery</h4>
                        <p style="margin: 0; color: var(--muted); font-size: 13px;">24-48 hours shipping</p>
                    </div>
                    <div>
                        <h4 style="color: var(--text-brown); margin-bottom: 8px; font-size: 13px; text-transform: uppercase; font-weight: 600;">Quality Assured</h4>
                        <p style="margin: 0; color: var(--muted); font-size: 13px;">Certified fresh produce</p>
                    </div>
                    <div>
                        <h4 style="color: var(--text-brown); margin-bottom: 8px; font-size: 13px; text-transform: uppercase; font-weight: 600;">Best Price</h4>
                        <p style="margin: 0; color: var(--muted); font-size: 13px;">Direct from farmers</p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 80px 20px;">
            <h2 style="color: var(--muted);">Product not found</h2>
            <a href="/products" class="btn btn-primary" style="margin-top: 24px;">Back to Products</a>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
