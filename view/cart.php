<h2>Shopping Cart</h2>
<?php if (empty($cartItems)): ?>
    <p>Your cart is empty. <a href="index.php?controller=home&action=index">Continue shopping</a></p>
<?php else: ?>
    <table class="cart-table">
        <thead><tr><th>Medicine</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead>
        <tbody id="cartBody">
            <?php foreach ($cartItems as $item): ?>
            <tr id="cart-row-<?= $item['cart_id'] ?>">
                <td><?= htmlspecialchars($item['name']) ?><br><small><?= $item['vendor_name'] ?></small></td>
                <td>৳<?= number_format($item['price'],2) ?></td>
                <td><input type="number" min="1" value="<?= $item['quantity'] ?>" onchange="updateCart(<?= $item['cart_id'] ?>, this.value)" class="qty-input"></td>
                <td class="subtotal">৳<?= number_format($item['price'] * $item['quantity'],2) ?></td>
                <td><button onclick="removeCartItem(<?= $item['cart_id'] ?>)" class="btn-danger">Remove</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="cart-total">
        <strong>Total: ৳<span id="cartTotal"><?= number_format($total,2) ?></span></strong>
        <a href="index.php?controller=order&action=checkout" class="btn btn-primary">Proceed to Checkout</a>
    </div>
<?php endif; ?>
<script>
function updateCart(cartId, qty) {
    if (qty < 1) qty = 1;
    fetch('index.php?controller=cart&action=updateAjax', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `cart_id=${cartId}&quantity=${qty}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
        else alert('Update failed');
    });
}
function removeCartItem(cartId) {
    if (confirm('Remove item?')) {
        fetch('index.php?controller=cart&action=removeAjax', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `cart_id=${cartId}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
        });
    }
}
</script>