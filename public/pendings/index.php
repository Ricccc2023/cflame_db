<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$sql="SELECT pending_orders.*, products.product_name
FROM pending_orders
LEFT JOIN products ON products.id=pending_orders.product_id
ORDER BY pending_orders.id DESC";

$result=mysqli_query($conn,$sql);
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Pending Orders</h2>
<p class="sub">Public Orders Waiting Approval</p>
</div>

<div class="page-action">
<a href="create.php" class="btn-add">+ Manual Pending Order</a>
</div>

</div>

<div class="card">

<table>

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Product</th>
<th>Qty</th>
<th>Contact</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['customer_name'] ?></td>
<td><?= $row['product_name'] ?></td>
<td><?= $row['quantity'] ?></td>
<td><?= $row['contact'] ?></td>

<td>

<a class="action-btn action-success"
href="confirm.php?id=<?= $row['id'] ?>">
Confirm
</a>

<a class="action-btn action-danger"
href="decline.php?id=<?= $row['id'] ?>">
Decline
</a>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>
</div>

<?php include '../../includes/footer.php'; ?>