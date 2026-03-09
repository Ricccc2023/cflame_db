<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$sql = "SELECT * FROM pending_orders ORDER BY id DESC";
$result = mysqli_query($conn,$sql);
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-title">

<h2>Pending Orders</h2>
<?php if(isset($_GET['error'])): ?>

<div style="
background:#ffdede;
color:#a10000;
padding:10px;
margin-top:10px;
border-left:5px solid red;
">

<?= htmlspecialchars($_GET['error']) ?>

</div>

<?php endif; ?>

</div>

<div class="page-action">
<a href="create.php" class="btn-add">+ Create Pending</a>
</div>

</div>


<div class="card">

<table>

<thead>
<tr>
<th>ID</th>
<th>Customer</th>
<th>Products</th>
<th>Payment</th>
<th>Date</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= htmlspecialchars($row['customer_name']) ?></td>

<td>

<?php

$items = mysqli_query($conn,"
SELECT pending_order_items.*, products.product_name
FROM pending_order_items
LEFT JOIN products 
ON products.id = pending_order_items.product_id
WHERE pending_order_items.pending_order_id = ".$row['id']."
");

while($item = mysqli_fetch_assoc($items)){

echo htmlspecialchars($item['product_name']) . " (" . $item['quantity'] . ")<br>";

}

?>

</td>

<td><?= htmlspecialchars($row['mode_of_payment']) ?></td>

<td><?= $row['created_at'] ?></td>

<td>

<div class="actions">

<a class="action-btn action-success"
href="confirm.php?id=<?= $row['id'] ?>">
Confirm
</a>

<a class="action-btn action-danger"
href="decline.php?id=<?= $row['id'] ?>">
Decline
</a>

</div>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

<?php include '../../includes/footer.php'; ?>