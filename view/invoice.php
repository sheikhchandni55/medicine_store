<h2>Order Summary</h2>
<p><strong>Shipping Address:</strong> <?= nl2br(htmlspecialchars($address)) ?></p>

<table class="cart-table">
    <thead>
        <tr><th>Medicine</th><th>Qty</th><th>Unit Price</th><th>Total</th></tr>
    </thead>
    <tbody>
        <?php foreach ($cartItems as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>৳<?= number_format($item['price'], 2) ?></td>
            <td>৳<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="cart-total">
    <strong>Grand Total: ৳<?= number_format($total, 2) ?></strong>
</div>

<form method="POST" onsubmit="return validatePayment()">
    <input type="hidden" name="step" value="payment">
    <h3>Select Payment Method</h3>
    <div class="payment-options">
        <label><input type="radio" name="payment_method" value="Credit Card"> Credit Card</label>
        <label><input type="radio" name="payment_method" value="bKash"> bKash</label>
        <label><input type="radio" name="payment_method" value="Nagad"> Nagad</label>
        <label><input type="radio" name="payment_method" value="Bank Transfer"> Bank Transfer</label>
        <label><input type="radio" name="payment_method" value="Cash on Delivery"> Cash on Delivery</label>
    </div>
    <button type="submit" class="btn btn-success">Confirm Purchase</button>
</form>

<script>
function validatePayment() {
    let selected = document.querySelector('input[name="payment_method"]:checked');
    if (!selected) {
        alert('Please select a payment method');
        return false;
    }
    return true;
}
</script>