<?php require __DIR__ . '/../layout/header.php'; ?>

<h2>Your Cart</h2>
<div class="cart-list">
	<p>Your cart items will appear here.</p>
	<table>
		<thead>
			<tr><th>Product</th><th>Qty</th><th>Price</th><th></th></tr>
		</thead>
		<tbody>
			<!-- Server renders cart rows -->
		</tbody>
	</table>
	<p><a href="/agro_market/public/checkout.php">Proceed to Checkout</a></p>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
