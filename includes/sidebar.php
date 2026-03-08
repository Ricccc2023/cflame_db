<?php
define('BASE_URL', '/cflame_db/public');

$role = $_SESSION['role'] ?? '';
?>

<div class="sidebar">

<div class="nav">

<?php if($role === 'admin'): ?>
<a href="<?= BASE_URL ?>/dashboard.php">
Dashboard
</a>
<?php endif; ?>

<?php if($role === 'admin'): ?>
<a href="<?= BASE_URL ?>/staffs/index.php">
Users
</a>
<?php endif; ?>

<a href="<?= BASE_URL ?>/customers/index.php">
Customers
</a> 

<!-- INVENTORY (ADMIN + STAFF) -->
<a href="<?= BASE_URL ?>/inventory/index.php">
Inventory
</a>

<a href="<?= BASE_URL ?>/orders/index.php">
Orders
</a>

</div>

<div class="power">

<a class="logout-btn" href="<?= BASE_URL ?>/logout.php">
Logout
</a>

</div>

</div>