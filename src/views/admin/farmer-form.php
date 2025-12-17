<?php require __DIR__ . '/../layout/admin-header.php'; ?>

<div style="padding: 40px 0; max-width: 720px;">
  <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 style="margin:0;">Add Farmer</h1>
    <a href="/admin/farmers" class="btn btn-secondary">← Back</a>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-error" style="margin-bottom: 16px;">
      <span>⚠️</span>
      <div><?php echo htmlspecialchars($error); ?></div>
    </div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="alert alert-success" style="margin-bottom: 16px;">
      <span>✓</span>
      <div><?php echo htmlspecialchars($success); ?></div>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header"><h3 style="margin:0;">Farmer Details</h3></div>
    <form method="post" action="/admin/farmers/create" style="padding: 16px;">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">

      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required placeholder="farmer@example.com">
      </div>

      <div class="form-group">
        <label class="form-label">Full Name (optional)</label>
        <input type="text" name="name" class="form-control" placeholder="Farmer name">
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required placeholder="Temporary password">
        <div class="form-help">Share this password with the farmer; they can change it later.</div>
      </div>

      <button type="submit" class="btn btn-primary">Create Farmer</button>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
