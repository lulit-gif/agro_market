<?php require __DIR__ . '/../layout/header.php'; ?>

<div style="padding: 32px 0;">
	<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
		<h1 style="margin: 0;">Your Orders</h1>
		<a href="/products" class="btn btn-secondary">Continue Shopping</a>
	</div>

	<?php if (empty($orders)): ?>
		<div class="alert" style="background: var(--bg2); border: 1px solid var(--line); padding: 16px; border-radius: 8px;">
			You have no orders yet.
		</div>
	<?php else: ?>
		<div class="card">
			<div class="card-header">
				<h3 style="margin: 0;">Recent Orders</h3>
			</div>
			<div class="table" style="width: 100%;">
				<div class="table-header" style="display: grid; grid-template-columns: 120px 1fr 140px 140px 120px; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--line); font-weight: 600;">
					<div>Order #</div>
					<div>Date</div>
					<div>Status</div>
					<div>Total</div>
					<div>Items</div>
				</div>
				<?php foreach ($orders as $o): ?>
					<div class="table-row" style="display: grid; grid-template-columns: 120px 1fr 140px 140px 120px; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--line); align-items: center;">
						<div>#<?php echo (int)$o['id']; ?></div>
						<div><?php echo date('M d, Y H:i', strtotime($o['created_at'])); ?></div>
						<div style="text-transform: capitalize;"><?php echo htmlspecialchars($o['status']); ?></div>
						<div>$<?php echo number_format((float)$o['total'], 2); ?></div>
						<div><?php echo (int)($itemCounts[$o['id']] ?? 0); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
