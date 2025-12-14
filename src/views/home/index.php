<?php require __DIR__ . '/../layout/header.php'; ?>

	<section class="hero">
		<h1>Welcome to Agro Market</h1>
		<p>Fresh produce from local farmers — discover, buy and support local.</p>
		<p><a class="btn" href="/agro_market/public/index.php?page=products">Shop Now</a></p>
	</section>

	<section class="featured">
		<h2>Featured Products</h2>
		<div class="product-grid">
			<div class="product-card">
				<img data-src="/agro_market/public/img/hero.jpg" alt="Sample product 1">
				<h3>Sample Product A</h3>
				<p class="price">$4.99</p>
				<p><button class="btn" data-action="add-to-cart" data-url="/agro_market/public/cart/add.php">Add to cart</button></p>
			</div>
			<div class="product-card">
				<img data-src="/agro_market/public/img/hero.jpg" alt="Sample product 2">
				<h3>Sample Product B</h3>
				<p class="price">$6.49</p>
				<p><button class="btn" data-action="add-to-cart" data-url="/agro_market/public/cart/add.php">Add to cart</button></p>
			</div>
			<div class="product-card">
				<img data-src="/agro_market/public/img/hero.jpg" alt="Sample product 3">
				<h3>Sample Product C</h3>
				<p class="price">$3.20</p>
				<p><button class="btn" data-action="add-to-cart" data-url="/agro_market/public/cart/add.php">Add to cart</button></p>
			</div>
		</div>
	</section>

	<section class="newsletter">
		<h2>Join our newsletter</h2>
		<p class="muted">Get seasonal offers and farmer stories — no spam.</p>
		<form method="POST" action="/agro_market/public/newsletter.php" data-ajax id="newsletterForm">
			<div class="form-group form-inline">
				<input type="email" name="email" placeholder="Your email" required>
				<button class="btn" type="submit">Subscribe</button>
			</div>
		</form>
	</section>

	<section class="testimonials">
		<h2>Why people shop with us</h2>
		<div class="product-grid">
			<div class="product-card">
				<p><strong>Local & fresh</strong></p>
				<p class="muted">Direct from small farms to your table.</p>
			</div>
			<div class="product-card">
				<p><strong>Support farmers</strong></p>
				<p class="muted">Fair prices and sustainable practices.</p>
			</div>
			<div class="product-card">
				<p><strong>Easy checkout</strong></p>
				<p class="muted">Fast and secure payments.</p>
			</div>
		</div>
	</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
