<h2>Admin Dashboard</h2>
<div class="stats-grid">
    <div class="stat-card"> Medicines<br><strong><?= $stats['medicines'] ?></strong></div>
    <div class="stat-card"> Categories<br><strong><?= $stats['categories'] ?></strong></div>
    <div class="stat-card">Customers<br><strong><?= $stats['customers'] ?></strong></div>
    <div class="stat-card"> Pending Orders<br><strong><?= $stats['pending_orders'] ?></strong></div>
</div>
<div class="admin-links">
    <a href="index.php?controller=admin&action=customers" class="btn">Manage Customers</a>
    <a href="index.php?controller=admin&action=purchaseRequests" class="btn">Purchase Requests</a>
    <a href="index.php?controller=admin&action=history" class="btn">Order History</a>
</div>