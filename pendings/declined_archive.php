<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$sql="SELECT declined_orders_archive.*,products.product_name
FROM declined_orders_archive
LEFT JOIN products ON products.id=declined_orders_archive.product_id
ORDER BY declined_orders_archive.id DESC";

$result=mysqli_query($conn,$sql);
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<h2>Declined Orders Archive</h2>

<div class="card">

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Product</th>
<th>Qty</th>
<th>Date Declined</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['customer_name'] ?></td>
<td><?= $row['product_name'] ?></td>
<td><?= $row['quantity'] ?></td>
<td><?= $row['declined_at'] ?></td>

</tr>

<?php endwhile; ?>

</table>

</div>
</div>

<?php include '../../includes/footer.php'; ?>