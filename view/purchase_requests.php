<h2>Purchase Requests</h2>
<table class="admin-table" id="ordersTable">
    <thead><tr><th>Order ID</th><th>Customer</th><th>Amount</th><th>Address</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
        <tr id="order-row-<?= $order['id'] ?>">
            <td>#<?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
           ৳<?= number_format($order['total_amount'],2) ?>
            <td><?= htmlspecialchars(substr($order['shipping_address'],0,50)) ?>...</td>
            <td><?= $order['order_date'] ?></td>
            <td><span class="status-badge status-<?= $order['status'] ?>"><?= $order['status'] ?></span></td>
            <td>
                <?php if ($order['status'] == 'pending'): ?>
                    <button onclick="updateOrderStatus(<?= $order['id'] ?>, 'accepted')" class="btn-success">Accept</button>
                    <button onclick="updateOrderStatus(<?= $order['id'] ?>, 'rejected')" class="btn-danger">Reject</button>
                <?php else: ?>
                    <em>Processed</em>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
function updateOrderStatus(orderId, status) {
    fetch('index.php?controller=admin&action=updateOrderStatusAjax', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `order_id=${orderId}&status=${status}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
        else alert('Failed to update');
    });
}
</script>