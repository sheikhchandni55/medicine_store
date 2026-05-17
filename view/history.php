<h2>Completed Orders History</h2>
<?php if (empty($orders)): ?>
    <p>No completed orders yet.</p>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
        <div class="order-history-card">
            <h3>Order #<?= $order['id'] ?> - <?= $order['customer_name'] ?> – ৳<?= number_format($order['total_amount'],2) ?> ?></h3>
            <p>Date: <?= $order['order_date'] ?> | Payment: <?= $order['payment_method'] ?></p>
            <table class="mini-table">
                <thead><tr><th>Medicine</th><th>Qty</th><th>Unit Price</th></tr></thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                    <tr><td><?= htmlspecialchars($item['medicine_name']) ?></td><td><?= $item['quantity'] ?></td><td>$<?= number_format($item['unit_price'],2) ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
<?php endif; ?>