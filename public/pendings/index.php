<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$customer = $_GET['customer'] ?? '';
$payment = $_GET['payment'] ?? '';

$sql = "SELECT * FROM pending_orders WHERE 1=1";

if($customer != ""){
$sql .= " AND customer_name LIKE '%$customer%'";
}

if($payment != ""){
$sql .= " AND mode_of_payment LIKE '%$payment%'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn,$sql);
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Pending Orders</h2>
</div>

<div class="page-action">
<a href="create.php" class="btn-add">+ Create Pending</a>
</div>

</div>

<div class="card">

<form method="GET" class="filter-bar">

<input type="text" name="customer" placeholder="Customer Name" value="<?= htmlspecialchars($customer) ?>">

<select name="payment">

<option value="">All Payment</option>

<option value="GCash" <?= $payment=="GCash"?"selected":"" ?>>GCash</option>

<option value="Cash on Delivery" <?= $payment=="Cash on Delivery"?"selected":"" ?>>Cash on Delivery</option>

</select>

<button class="btn-search">Filter</button>

</form>

<div class="table-wrap">

<table>

<thead>
<tr>
<th>Receipt</th>
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

<td>

<?php if(!empty($row['receipt_image'])): ?>

<a
class="action-btn action-secondary"
style="padding:4px 10px;font-size:12px;"
href="uploads/<?php echo $row['receipt_image']; ?>"
target="_blank">

View

</a>

<?php else: ?>

<span style="color:#999;font-size:12px;">None</span>

<?php endif; ?>

</td>

<td><?= $row['id'] ?></td>

<td>
<strong><?= htmlspecialchars($row['customer_name']) ?></strong>
</td>

<td>

<?php

$items = mysqli_query($conn,"
SELECT pending_order_items.*, products.product_name
FROM pending_order_items
LEFT JOIN products ON products.id = pending_order_items.product_id
WHERE pending_order_items.pending_order_id = ".$row['id']."
");

while($item = mysqli_fetch_assoc($items)){

echo htmlspecialchars($item['product_name'])." (".$item['quantity'].")<br>";

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

</div>

<?php include '../../includes/footer.php'; ?>