<?php require __DIR__ . '/../layout/header.php'; ?>

<h2>Checkout</h2>
<section class="checkout-summary">
	<p>Order summary and payment form will appear here.</p>
	<form method="POST" action="checkout.php">
		<label>Name on card:
			<input type="text" name="card_name" required>
		</label>
		<label>Card number:
			<input type="text" name="card_number" required>
		</label>
		<button type="submit">Place order</button>
	</form>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
