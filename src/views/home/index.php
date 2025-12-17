<?php $pageClass = 'home'; 
require __DIR__ . '/../layout/header.php'; ?>

<!-- HERO -->
<section class="hero">
    <div class="hero-inner">
        <h1>Agro Market</h1>
        <p>Farm‑fresh groceries from local producers, delivered fast.</p>

        <form class="searchbar" method="get" action="/products" role="search">
            <input name="q" type="text" placeholder="Search produce, dairy, grains…" aria-label="Search products">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div class="hero-actions">
            <a href="/products" class="btn btn-primary">Shop Now</a>
            <a href="/register" class="btn btn-outline">Create Account</a>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="section" style="background-color: var(--bg-cream-2);">
    <div class="container">
        <div class="section-header">
            <h2>Latest Arrivals</h2>
            <a href="/products">View all →</a>
        </div>

        <?php if (!empty($products)): ?>
            <div class="grid auto">
                <?php foreach ($products as $p): ?>
                    <div class="card">
                        <?php if (!empty($p['image'])): ?>
                            <div class="card-media">
                                <img src="/img/products/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                            </div>
                        <?php else: ?>
                            <div class="card-media" style="background: var(--bg2);"></div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h3 class="card-title"><a href="/product?id=<?php echo (int)$p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></a></h3>
                            <p class="price">$<?php echo htmlspecialchars(number_format((float)$p['price'], 2)); ?></p>
                        </div>
                        <div class="card-actions">
                            <form method="post" action="/cart/add" style="width:100%; display:flex; gap:8px;">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
                                <input type="hidden" name="product_id" value="<?php echo (int)$p['id']; ?>">
                                <button type="submit" class="btn btn-primary" style="flex:1;">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty">
                <p>No products available yet. Check back soon!</p>
                <a href="/products" class="btn btn-primary" style="margin-top:12px;">Browse Catalog</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- BENEFITS STRIP -->
<section class="section container">
    <div class="card" style="display:grid; grid-template-columns: repeat(3, 1fr); gap:16px;">
        <div>
            <h4 style="margin:0 0 6px;">Quality Produce</h4>
            <p style="margin:0; color: var(--muted);">Carefully sourced from local farms</p>
        </div>
        <div>
            <h4 style="margin:0 0 6px;">Fast Delivery</h4>
            <p style="margin:0; color: var(--muted);">Reliable shipping to your door</p>
        </div>
        <div>
            <h4 style="margin:0 0 6px;">Secure Checkout</h4>
            <p style="margin:0; color: var(--muted);">Safe payments with multiple options</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section container">
    <div class="card" style="display:flex; align-items:center; justify-content:space-between; gap:16px;">
        <div>
            <h3 style="margin:0 0 6px;">Ready to shop?</h3>
            <p style="margin:0; color: var(--muted);">Discover fresh, seasonal produce today.</p>
        </div>
        <a class="btn btn-secondary" href="/products">Start Shopping</a>
    </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
