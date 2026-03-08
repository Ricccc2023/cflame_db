<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$id = $_GET['id'];

$order = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT orders.*,customers.customer_name
FROM orders
LEFT JOIN customers ON customers.id=orders.customer_id
WHERE orders.id=$id
"));

$items = mysqli_query($conn,"
SELECT order_items.*,products.product_name
FROM order_items
LEFT JOIN products ON products.id=order_items.product_id
WHERE order_id=$id
");
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="card">

<h2>Order #<?= $order['id'] ?></h2>

<p><b>Customer:</b> <?= $order['customer_name'] ?></p>
<p><b>Date:</b> <?= $order['order_date'] ?></p>

<table>

<thead>
<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Subtotal</th>
</tr>
</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($items)): ?>

<tr>

<td><?= $row['product_name'] ?></td>
<td><?= $row['quantity'] ?></td>
<td>₱<?= number_format($row['price'],2) ?></td>
<td>₱<?= number_format($row['subtotal'],2) ?></td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

<h3>Total: ₱<?= number_format($order['total'],2) ?></h3>

</div>
</div>

<?php include '../../includes/footer.php'; ?>