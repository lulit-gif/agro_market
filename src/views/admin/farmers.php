<?php require __DIR__ . '/../layout/admin-header.php'; ?>

<div style="padding: 40px 0;">
  <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 style="margin:0;">Manage Farmers</h1>
    <a href="/admin/farmers/new" class="btn btn-primary">+ Add Farmer</a>
  </div>

  <div class="card">
    <div style="overflow-x:auto;">
      <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
        <thead>
          <tr style="border-bottom: 2px solid var(--line);">
            <th style="padding: 12px; text-align: left; font-weight: 600;">ID</th>
            <th style="padding: 12px; text-align: left; font-weight: 600;">Email</th>
            <th style="padding: 12px; text-align: left; font-weight: 600;">Name</th>
            <th style="padding: 12px; text-align: left; font-weight: 600;">Joined</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($farmers as $f): ?>
            <tr style="border-bottom: 1px solid var(--line);">
              <td style="padding: 12px; font-weight: 600;">#<?php echo (int)$f['id']; ?></td>
              <td style="padding: 12px;"><?php echo htmlspecialchars($f['email']); ?></td>
              <td style="padding: 12px;"><?php echo htmlspecialchars($f['name'] ?? ''); ?></td>
              <td style="padding: 12px; color: var(--muted);"><?php echo date('M d, Y', strtotime($f['created_at'])); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (empty($farmers)): ?>
        <p style="text-align:center; color: var(--muted); padding: 24px 0;">No farmers found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
