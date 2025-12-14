<?php require __DIR__ . '/../layout/header.php'; ?>

<h2>Products</h2>
<div class="form-group">
	<input id="product-search" type="search" placeholder="Search products..." />
</div>
<div class="product-grid" id="product-grid">
	<?php // server renders product cards here ?>
	<div class="product-card">
		<img data-src="/agro_market/public/img/hero.jpg" alt="Product">
		<h3 class="product-title">Product name</h3>
		<p class="price">$9.99</p>
		<p><a href="details.php">View</a></p>
	</div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
