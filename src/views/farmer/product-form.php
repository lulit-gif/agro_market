<?php require __DIR__ . '/../layout/farmer-header.php'; $isEdit = !empty($product); ?>

<div style="padding: 40px 0;">
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 16px;">
        <a href="/farmer/dashboard" style="color: var(--accent-dark); font-weight: 600;">← Back to Dashboard</a>
    </div>
    <h1><?php echo $isEdit ? 'Edit Product' : 'Add New Product'; ?></h1>
    
    <div style="max-width: 600px;">
        <form method="post" enctype="multipart/form-data" action="<?php echo $isEdit ? '/farmer/product/edit' : '/farmer/product/add'; ?>" class="card" style="padding: 0;">
            <div style="padding: 20px;">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES); ?>">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., Fresh Tomatoes" value="<?php echo htmlspecialchars($product['name'] ?? '', ENT_QUOTES); ?>" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Price ($) *</label>
                        <input type="number" name="price" step="0.01" min="0" class="form-control" placeholder="0.00" value="<?php echo htmlspecialchars($product['price'] ?? '', ENT_QUOTES); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Stock (units) *</label>
                        <input type="number" name="stock" min="0" class="form-control" placeholder="0" value="<?php echo htmlspecialchars($product['stock'] ?? '', ENT_QUOTES); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" style="min-height: 100px; font-family: var(--font-family-base);" placeholder="Describe your product..."><?php echo htmlspecialchars($product['description'] ?? '', ENT_QUOTES); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Product Image (JPEG/PNG)</label>
                    <?php if ($isEdit && !empty($product['image'])): ?>
                        <div style="margin-bottom:8px;">
                            <img src="/img/products/<?php echo htmlspecialchars($product['image']); ?>" alt="Current image" style="height: 80px; border-radius: 6px; border: 1px solid var(--line);">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/jpeg,image/png" class="form-control">
                    <div class="form-help">Optional. If provided on edit, it will replace the current image.</div>
                </div>
                
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <?php echo $isEdit ? 'Save Changes' : 'Add Product'; ?>
                    </button>
                    <a href="/farmer/dashboard" class="btn btn-secondary" style="flex: 1; text-align: center;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
