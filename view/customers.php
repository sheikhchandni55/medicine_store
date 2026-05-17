<h2>All Customers</h2>
<table class="admin-table">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($customers as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['name']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['phone']) ?></td>
            <td><?= htmlspecialchars($c['address']) ?></td>
            <td><a href="index.php?controller=admin&action=deleteCustomer&id=<?= $c['id'] ?>" onclick="return confirm('Delete customer and all orders?')" class="btn-danger">Delete</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>