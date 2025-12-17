<?php require __DIR__ . '/../layout/admin-header.php'; ?>

<div style="padding: 40px 0;">
  <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 style="margin:0;">Order #<?php echo (int)$order['id']; ?></h1>
    <a href="/admin/orders" class="btn btn-secondary">← Back to Orders</a>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <div class="card">
      <div class="card-header"><h3 style="margin:0;">Customer</h3></div>
      <div style="padding: 12px 16px; font-size: 13px;">
        <div style="margin-bottom: 8px;"><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></div>
        <div style="margin-bottom: 8px;"><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></div>
        <div style="margin-bottom: 8px;"><strong>Address:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></div>
        <div style="margin-bottom: 8px;"><strong>Placed:</strong> <?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3 style="margin:0;">Summary</h3></div>
      <div style="padding: 12px 16px; font-size: 13px; display:flex; gap: 12px; align-items: center; justify-content: space-between;">
        <div>
          <div style="margin-bottom: 6px;"><strong>Status:</strong> <span style="text-transform: capitalize;"><?php echo htmlspecialchars($order['status']); ?></span></div>
          <div><strong>Total:</strong> $<?php echo number_format((float)$order['total'], 2); ?></div>
        </div>
        <form method="post" action="/admin/order/update-status" style="display: inline-flex; gap: 6px; align-items: center;">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
          <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
          <select name="status" class="form-control" style="padding: 6px; font-size: 12px;">
            <?php $statuses = ['pending','processing','shipped','delivered','cancelled'];
              foreach ($statuses as $st): ?>
                <option value="<?php echo $st; ?>" <?php echo $order['status'] === $st ? 'selected' : ''; ?>><?php echo ucfirst($st); ?></option>
            <?php endforeach; ?>
          </select>
          <button type="submit" class="btn btn-sm" style="padding: 6px 10px; font-size: 11px;">Update</button>
        </form>
      </div>
    </div>
  </div>

  <div class="card" style="margin-top: 24px;">
    <div class="card-header"><h3 style="margin:0;">Items</h3></div>
    <div style="overflow-x:auto;">
      <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
        <thead>
          <tr style="border-bottom: 2px solid var(--line);">
            <th style="padding: 12px; text-align: left; font-weight: 600;">Product</th>
            <th style="padding: 12px; text-align: left; font-weight: 600;">Farmer</th>
            <th style="padding: 12px; text-align: left; font-weight: 600;">Price</th>
            <th style="padding: 12px; text-align: left; font-weight: 600;">Qty</th>
            <th style="padding: 12px; text-align: left; font-weight: 600;">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr style="border-bottom: 1px solid var(--line);">
              <td style="padding: 12px;">#<?php echo (int)$it['product_id']; ?> — <?php echo htmlspecialchars($it['product_name']); ?></td>
              <td style="padding: 12px; color: var(--muted);"><?php echo htmlspecialchars($it['farmer_email'] ?? ''); ?></td>
              <td style="padding: 12px;">$<?php echo number_format((float)$it['price'], 2); ?></td>
              <td style="padding: 12px;"><?php echo (int)$it['quantity']; ?></td>
              <td style="padding: 12px; font-weight: 600;">$<?php echo number_format((float)$it['price'] * (int)$it['quantity'], 2); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (empty($items)): ?>
        <p style="text-align:center; color: var(--muted); padding: 24px 0;">No items found for this order.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
