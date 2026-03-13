<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$customer = $_GET['customer'] ?? '';
$date = $_GET['date'] ?? '';

$sql = "
SELECT orders.*, customers.customer_name
FROM orders
LEFT JOIN customers ON orders.customer_id = customers.id
WHERE 1=1
";

if($customer != ""){
$sql .= " AND customers.customer_name LIKE '%$customer%'";
}

if($date != ""){
$sql .= " AND orders.order_date = '$date'";
}

$sql .= " ORDER BY orders.id DESC";

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

<form method="GET" class="filter-bar">

<input type="text" name="customer" placeholder="Customer Name" value="<?= htmlspecialchars($customer) ?>">

<input type="date" name="date" value="<?= htmlspecialchars($date) ?>">

<button class="btn-search">Filter</button>

</form>

<div class="table-wrap">

<table>

<thead>
<tr>
<th>ID</th>
<th>Invoice</th>
<th>Customer</th>
<th>Date</th>
<th>Total</th>
<th>Receipt</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id'] ?></td>

<td>
<strong><?= $row['invoice_no'] ?></strong>
</td>

<td><?= $row['customer_name'] ?></td>

<td><?= $row['order_date'] ?></td>

<td>₱<?= number_format($row['total'],2) ?></td>

<td>

<?php if(!empty($row['receipt_image'])): ?>

<a
class="action-btn action-secondary"
style="padding:4px 10px;font-size:12px;"
href="uploads/<?php echo $row['receipt_image']; ?>" target="_blank">
View

</a>

<?php else: ?>

<span style="color:#999;font-size:12px;">None</span>

<?php endif; ?>

</td>

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

</div>

<?php include '../../includes/footer.php'; ?>