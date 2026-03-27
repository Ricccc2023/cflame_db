<?php
include "../includes/auth.php";
include "../includes/config.php";
include "../includes/header.php";
include "../includes/sidebar.php";

$id = intval($_GET['id']);

/* CUSTOMER INFO */

$cust = mysqli_query($conn,"
SELECT * FROM customers WHERE id=$id
");

$customer = mysqli_fetch_assoc($cust);

/* GET CUSTOMER ORDERS */

$orders = mysqli_query($conn,"
SELECT * FROM orders
WHERE customer_id=$id
ORDER BY id DESC
");
?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2><?= htmlspecialchars($customer['customer_name']) ?></h2>
<p class="sub">Customer Purchase History</p>
</div>

<div class="page-action">
<a href="index.php" class="btn-decline">Back</a>
</div>

</div>


<div class="card">

<table class="result-table">

<tr>
<th>Customer Name</th>
<td><?= htmlspecialchars($customer['customer_name']) ?></td>
</tr>

<tr>
<th>Contact</th>
<td><?= htmlspecialchars($customer['contact']) ?></td>
</tr>

<tr>
<th>Address</th>
<td><?= htmlspecialchars($customer['address']) ?></td>
</tr>

</table>

</div>


<?php if(mysqli_num_rows($orders)==0){ ?>

<div class="card">
No orders found for this customer.
</div>

<?php } ?>


<?php while($order = mysqli_fetch_assoc($orders)){ ?>

<div class="card">

<div style="display:flex;justify-content:space-between;align-items:center;">

<h3>
Transaction #<?= $order['id'] ?>
</h3>

<a href="../orders/print.php?id=<?= $order['id'] ?>" target="_blank">
<button style="
padding:6px 12px;
background:#000;
color:white;
border:none;
cursor:pointer;
border-radius:4px;
">
Print Receipt
</button>
</a>

</div>

<p>
Order Date: <?= $order['order_date'] ?>
</p>

<table>

<thead>

<tr>
<th>Product</th>
<th>Quantity</th>
<th>Price</th>
<th>Subtotal</th>
</tr>

</thead>

<tbody>

<?php

$items = mysqli_query($conn,"
SELECT order_items.*, products.product_name
FROM order_items
LEFT JOIN products ON order_items.product_id = products.id
WHERE order_items.order_id=".$order['id']."
");

while($item = mysqli_fetch_assoc($items)){

?>

<tr>

<td><?= htmlspecialchars($item['product_name']) ?></td>

<td><?= $item['quantity'] ?></td>

<td>₱<?= number_format($item['price'],2) ?></td>

<td>₱<?= number_format($item['subtotal'],2) ?></td>

</tr>

<?php } ?>

</tbody>

</table>

<h4 style="margin-top:10px;">
Total: ₱<?= number_format($order['total'],2) ?>
</h4>

</div>

<?php } ?>

</div>

<?php include "../includes/footer.php"; ?>