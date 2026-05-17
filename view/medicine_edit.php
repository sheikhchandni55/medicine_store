<div class="form-container">
    <h2>Edit Medicine</h2>
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Medicine Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($medicine['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Category ID</label>
            <input type="number" name="category_id" value="<?= htmlspecialchars($medicine['category_id']) ?>" required>
        </div>
        <div class="form-group">
            <label>Vendor Name</label>
            <input type="text" name="vendor_name" value="<?= htmlspecialchars($medicine['vendor_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Price (BDT ৳)</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($medicine['price']) ?>" required>
        </div>
        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="availability" value="<?= htmlspecialchars($medicine['availability']) ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($medicine['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Current Image</label><br>
            <img src="uploads/medicines/<?= $medicine['image_path'] ?? 'default.png' ?>" width="100"><br>
            <label>Change Image (optional)</label>
            <input type="file" name="image" accept="image/jpeg,image/png">
        </div>
        <button type="submit" class="btn btn-primary">Update Medicine</button>
    </form>
</div>