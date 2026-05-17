<div class="admin-header">
    <h2>Medicine Inventory</h2>
    <a href="index.php?controller=medicine&action=create" class="btn btn-success">+ Add New Medicine</a>
</div>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<table class="admin-table">
    <thead>
        <tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Vendor</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
    </thead>
    <tbody>
        <?php foreach ($medicines as $med): ?>
        <tr>
            <td><?= $med['id'] ?></td>
            <td><img src="uploads/medicines/<?= $med['image_path'] ?? 'default.png' ?>" width="50" height="50"></td>
            <td><?= htmlspecialchars($med['name']) ?></td>
            <td><?= htmlspecialchars($med['category_name']) ?></td>
            <td><?= htmlspecialchars($med['vendor_name']) ?></td>
          <td>৳<?= number_format($med['price'], 2) ?></td>
            <td><?= $med['availability'] ?></td>
            <td>
                <a href="index.php?controller=medicine&action=edit&id=<?= $med['id'] ?>" class="btn-edit">Edit</a>
                <a href="index.php?controller=medicine&action=delete&id=<?= $med['id'] ?>" onclick="return confirm('Delete this medicine?')" class="btn-danger">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>