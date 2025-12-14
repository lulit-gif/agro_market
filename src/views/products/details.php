<?php require __DIR__ . '/../layout/header.php'; ?>

<article class="product-card">
	<img src="/agro_market/public/img/placeholder.png" alt="Product Image">
	<h2>Product Title</h2>
	<p class="price">$12.00</p>
	<p>Short description of the product.</p>
	<p><button data-action="add-to-cart" data-url="/agro_market/public/cart/add.php">Add to cart</button></p>
	<?php // Reviews and details go here ?>
</article>

<?php require __DIR__ . '/../layout/footer.php'; ?>
