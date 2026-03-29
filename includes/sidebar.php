<?php
define('BASE_URL', '/cflame_db');

$role = $_SESSION['role'] ?? '';
?>

<div class="sidebar">

<div class="nav">

<a href="<?= BASE_URL ?>/dashboard.php">
Dashboard
</a>

<?php if($role === 'admin'): ?>
<a href="<?= BASE_URL ?>/pointofsales/index.php">
POS
</a>
<?php endif; ?>

<!-- INVENTORY (ADMIN + STAFF) -->
<a href="<?= BASE_URL ?>/reports/index.php">
Reports
</a>

<!-- INVENTORY (ADMIN + STAFF) -->
<a href="<?= BASE_URL ?>/inventory/index.php">
Inventory
</a>

<a href="<?= BASE_URL ?>/customers/index.php">
Customers
</a>

<a href="<?= BASE_URL ?>/orders/index.php">
Orders
</a>

<a href="<?= BASE_URL ?>/pendings/index.php">
Pending 
</a>
</div>

<div class="power">

<?php if($role === 'admin'): ?>
<a href="<?= BASE_URL ?>/staffs/index.php">
Users
</a>
<?php endif; ?>

<?php if($role === 'admin'): ?>
<a style="display:flex;  margin-bottom:40px;margin-top:40px; gap:30px;"
href="<?= BASE_URL ?>/payroll/index.php">
Payroll
</a>
<?php endif; ?>

<a class="logout-btn" href="<?= BASE_URL ?>/logout.php">
Logout
</a>

</div>

</div>