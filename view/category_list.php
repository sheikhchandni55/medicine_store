<div class="admin-header">
    <h2>Categories</h2>
    <a href="index.php?controller=category&action=create" class="btn btn-success">+ Add Category</a>
</div>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<table class="admin-table">
    <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?= $cat['id'] ?></td>
            <td><?= htmlspecialchars($cat['name']) ?></td>
            <td><?= $cat['category_type'] ?></td>
            <td>
                <a href="index.php?controller=category&action=edit&id=<?= $cat['id'] ?>" class="btn-edit">Edit</a>
                <a href="index.php?controller=category&action=delete&id=<?= $cat['id'] ?>" onclick="return confirm('Delete category?')" class="btn-danger">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>