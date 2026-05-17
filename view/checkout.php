<h2>Checkout</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>
<form method="POST" onsubmit="return validateAddress()">
    <input type="hidden" name="step" value="address">
    <div class="form-group">
        <label>Shipping Address</label>
        <textarea name="shipping_address" id="address" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Continue to Invoice</button>
</form>
<script>
function validateAddress() {
    let addr = document.getElementById('address').value.trim();
    if (!addr) { alert('Shipping address is required'); return false; }
    return true;
}
</script>