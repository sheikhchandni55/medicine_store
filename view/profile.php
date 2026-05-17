<div class="form-container">
    <h2>My Profile</h2>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $err): ?>
            <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <form method="POST" action="index.php?controller=profile&action=update" enctype="multipart/form-data">
        <div class="form-group">
            <label>Profile Picture</label>
            <?php if ($user['profile_picture']): ?>
                <img src="uploads/profiles/<?= $user['profile_picture'] ?>" width="80" style="border-radius:50%"><br>
            <?php endif; ?>
            <input type="file" name="profile_picture" accept="image/jpeg,image/png">
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" required><?= htmlspecialchars($user['address']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
        </div>

        <hr><h3>Change Password (optional)</h3>
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password">
        </div>
        <div class="form-group">
            <label>New Password (min 8 chars)</label>
            <input type="password" name="new_password">
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password">
        </div>
        <input type="hidden" name="change_password" value="1">
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>