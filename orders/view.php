<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$id = intval($_GET['id']);

$order = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT orders.*, customers.customer_name
FROM orders
LEFT JOIN customers ON customers.id = orders.customer_id
WHERE orders.id = $id
"));

$items = mysqli_query($conn,"
SELECT order_items.*, products.product_name
FROM order_items
LEFT JOIN products ON products.id = order_items.product_id
WHERE order_items.order_id = $id
");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-action">
<a href="index.php" class="btn-decline">Back</a>
</div>

</div>

<div class="card">

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">


<h2>Order #<?= $order['id'] ?></h2>

<a href="print.php?id=<?= $order['id'] ?>" target="_blank">
<button style="
padding:8px 15px;
background:#000000;
border:none;
color:white;
cursor:pointer;
border-radius:4px;
">
Print Receipt
</button>
</a>

</div>

<p><b>Customer:</b> <?= $order['customer_name'] ?></p>
<p><b>Date:</b> <?= $order['order_date'] ?></p>

<table style="width:100%;border-collapse:collapse;margin-top:15px;">

<thead>
<tr>
<th style="border:1px solid #ddd;padding:8px;">Product</th>
<th style="border:1px solid #ddd;padding:8px;">Qty</th>
<th style="border:1px solid #ddd;padding:8px;">Price</th>
<th style="border:1px solid #ddd;padding:8px;">Subtotal</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($items)): ?>

<tr>

<td style="border:1px solid #ddd;padding:8px;">
<?= $row['product_name'] ?>
</td>

<td style="border:1px solid #ddd;padding:8px;">
<?= $row['quantity'] ?>
</td>

<td style="border:1px solid #ddd;padding:8px;">
₱<?= number_format($row['price'],2) ?>
</td>

<td style="border:1px solid #ddd;padding:8px;">
₱<?= number_format($row['subtotal'],2) ?>
</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

<h3 style="margin-top:20px;">
Total: ₱<?= number_format($order['total'],2) ?>
</h3>

</div>
</div>

<?php include '../includes/footer.php'; ?>