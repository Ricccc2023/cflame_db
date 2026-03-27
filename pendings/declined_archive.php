<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$sql = "
SELECT d.*, p.product_name
FROM declined_orders_archive d
LEFT JOIN products p ON p.id = d.product_id
ORDER BY d.id DESC
";

$result = mysqli_query($conn, $sql);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">

<h2>Declined Orders Archive</h2>

<div class="page-action">
<a href="index.php" class="btn-add">Back</a>
</div>

<div class="card">

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Name</th>
<th>Product</th>
<th>Qty</th>
<th>Date Declined</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)): ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['customer_name'] ?></td>
<td><?= $row['product_name'] ?? 'N/A' ?></td>
<td><?= $row['quantity'] ?? 'N/A' ?></td>
<td><?= $row['declined_at'] ?></td>
</tr>

<?php endwhile; ?>

</table>

</div>
</div>

<?php include '../includes/footer.php'; ?>