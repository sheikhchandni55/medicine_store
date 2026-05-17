<div class="form-container">
    <h2><?= isset($category) ? 'Edit Category' : 'New Category' ?></h2>
    <form method="POST">
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($category['name'] ?? $old['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Type</label>
            <select name="category_type" required>
                <option value="solid" <?= (($category['category_type']??'')=='solid')?'selected':'' ?>>Solid</option>
                <option value="liquid" <?= (($category['category_type']??'')=='liquid')?'selected':'' ?>>Liquid</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>