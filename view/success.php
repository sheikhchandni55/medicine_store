<div class="success-container">
    <h2>✅ Order Placed Successfully!</h2>
    
    <?php if (isset($order) && !empty($order)): ?>
        <p><strong>Order ID:</strong> #<?= htmlspecialchars($order['id']) ?></p>
        <p><strong>Total Amount:</strong> ৳<?= number_format($order['total_amount'], 2) ?></p>
        <p><strong>Status:</strong> <span class="status-badge status-pending">Pending Admin Approval</span></p>
        
        <h3>Items:</h3>
        <ul>
            <?php foreach ($order['items'] as $item): ?>
                <li><?= htmlspecialchars($item['medicine_name']) ?> x <?= $item['quantity'] ?> = ৳<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Your order has been received. You will be notified once approved.</p>
    <?php endif; ?>
    
    <a href="index.php?controller=home&action=index" class="btn btn-primary">Continue Shopping</a>
</div>