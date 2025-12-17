<?php
$pageClass = 'products';
$pageTitle = 'Products - Agro Market';
require __DIR__ . '/../layout/header.php';
?>

<div style="padding: 40px 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div>
            <h1 style="margin: 0 0 8px;">Our Products</h1>
            <p style="margin: 0; color: var(--muted);">Fresh, locally-sourced agricultural products</p>
        </div>
    </div>
    
    <!-- SEARCH -->
    <form method="get" action="/products" style="margin-bottom: 40px;">
        <div style="display: flex; gap: 12px; max-width: 500px;">
            <input type="text" name="q" placeholder="Search products..." value="<?php echo htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES); ?>" class="form-control" style="flex: 1;">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
    
    <!-- PRODUCTS GRID -->
    <?php if (!empty($products)): ?>
        <div class="grid grid-auto">
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php if (!empty($p['image'])): ?>
                            <img src="/img/products/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-body">
                        <h3 class="product-name">
                            <a href="/product?id=<?php echo (int)$p['id']; ?>" style="color: inherit;">
                                <?php echo htmlspecialchars($p['name']); ?>
                            </a>
                        </h3>
                        <p class="product-price">$<?php echo htmlspecialchars(number_format((float)$p['price'], 2)); ?></p>
                    </div>
                    
                    <div class="product-actions">
                        <a href="/product?id=<?php echo (int)$p['id']; ?>" class="btn btn-secondary" style="flex: 1; text-align: center;">View</a>
                        <form method="post" action="/cart/add" style="flex: 1;">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
                            <input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Add</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 80px 20px; background-color: var(--white); border-radius: 8px; border: 1px solid var(--line);">
            <h2 style="color: var(--muted);">No products found</h2>
            <p style="color: var(--muted); margin-bottom: 24px;">Try a different search or browse all products</p>
            <a href="/products" class="btn btn-primary">View All Products</a>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
