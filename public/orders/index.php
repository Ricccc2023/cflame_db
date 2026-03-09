<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$sql = "SELECT orders.*, customers.customer_name
FROM orders
LEFT JOIN customers ON orders.customer_id = customers.id
ORDER BY orders.id DESC";

$result = mysqli_query($conn,$sql);
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Order Management</h2>
</div>

<div class="page-action">
<a href="create.php" class="btn-add">+ Create Order</a>
</div>

</div>


<div class="card">

<table>

<thead>
<tr>
<th>ID</th>
<th>Invoice</th>
<th>Customer</th>
<th>Date</th>
<th>Total</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id'] ?></td>

<td>
<b><?= $row['invoice_no'] ?></b>
</td>

<td><?= $row['customer_name'] ?></td>

<td><?= $row['order_date'] ?></td>

<td>₱<?= number_format($row['total'],2) ?></td>

<td>

<div class="actions">

<a class="action-btn action-success"
href="view.php?id=<?= $row['id'] ?>">
View
</a>

<a class="action-btn action-secondary"
href="print.php?id=<?= $row['id'] ?>" target="_blank">
Print
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