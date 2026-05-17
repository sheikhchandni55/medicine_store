<div class="form-container">
    <h2>Create Account</h2>
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <form method="POST" action="index.php?controller=auth&action=register" onsubmit="return validateRegister()">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Password (min 8 characters)</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" required><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
<script>
function validateRegister() {
    let pwd = document.getElementById('password').value;
    let conf = document.getElementById('confirm_password').value;
    if (pwd.length < 8) { alert('Password must be at least 8 characters'); return false; }
    if (pwd !== conf) { alert('Passwords do not match'); return false; }
    return true;
}
</script>