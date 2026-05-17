<div class="form-container">
    <h2>Add New Medicine</h2>
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Medicine Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Category ID (must be a valid ID from categories table)</label>
            <input type="number" name="category_id" value="<?= htmlspecialchars($old['category_id'] ?? '') ?>" required placeholder="e.g., 1, 2, 3">
        </div>
        <div class="form-group">
            <label>Vendor Name</label>
            <input type="text" name="vendor_name" value="<?= htmlspecialchars($old['vendor_name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Price  (BDT ৳)</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($old['price'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="availability" value="<?= htmlspecialchars($old['availability'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Medicine Image (JPG/PNG, max 2MB)</label>
            <input type="file" name="image" accept="image/jpeg,image/png" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Medicine</button>
    </form>
</div>