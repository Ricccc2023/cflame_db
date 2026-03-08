<?php
require_once '../../includes/config.php';

$id = intval($_GET['id']);

$order = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
orders.*,
customers.customer_name,
customers.contact
FROM orders
LEFT JOIN customers ON customers.id = orders.customer_id
WHERE orders.id = $id
"));

$items = mysqli_query($conn,"
SELECT 
order_items.*,
products.product_name
FROM order_items
LEFT JOIN products ON products.id = order_items.product_id
WHERE order_items.order_id = $id
");

?>
<!DOCTYPE html>
<html>
<head>

<title>Receipt</title>

<style>

body{
font-family: Arial;
background:#f4f6f9;
padding:30px;
}

.report-container{
width:900px;
margin:auto;
background:#fff;
padding:40px;
}

.header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.logo-section{
display:flex;
align-items:center;
gap:15px;
}

.logo-section img{
width:60px;
}

.clinic-name{
font-size:22px;
font-weight:bold;
}

.header-info{
text-align:right;
font-size:14px;
}

table{
width:100%;
border-collapse:collapse;
margin-top:10px;
}

th,td{
border:1px solid #ddd;
padding:8px;
font-size:13px;
}

th{
background:#f2f4f7;
}

@media print{
body{padding:0;background:white;}
}

</style>

</head>

<body>

<div class="report-container">

<div class="header">

<div class="logo-section">

<img src="logo.png">

<div>
<div class="clinic-name">CFLAME STORE</div>
<div>Laguna Philippines</div>
</div>

</div>

<div class="header-info">
<h2>RECEIPT</h2>
</div>

</div>


<table>

<tr>
<td width="200"><b>Invoice #</b></td>
<td><?= $order['invoice_no'] ?></td>
</tr>

<tr>
<td><b>Name</b></td>
<td><?= $order['customer_name'] ?></td>
</tr>

<tr>
<td><b>Contact</b></td>
<td><?= $order['contact'] ?></td>
</tr>

<tr>
<td><b>Order Date</b></td>
<td><?= $order['order_date'] ?></td>
</tr>

<tr>
<td><b>Printed Time</b></td>
<td><?= date("Y-m-d H:i:s") ?></td>
</tr>

</table>


<h3 style="margin-top:20px;">ORDER ITEMS</h3>

<table>

<tr>
<th>Item</th>
<th>Qty</th>
<th>Price</th>
<th>Total</th>
</tr>

<?php while($row = mysqli_fetch_assoc($items)): ?>

<tr>

<td><?= $row['product_name'] ?></td>

<td><?= $row['quantity'] ?></td>

<td>₱<?= number_format($row['price'],2) ?></td>

<td>₱<?= number_format($row['subtotal'],2) ?></td>

</tr>

<?php endwhile; ?>

<tr>

<td colspan="3"><b>Total</b></td>

<td><b>₱<?= number_format($order['total'],2) ?></b></td>

</tr>

</table>

<p style="margin-top:20px;">Printed by System</p>

</div>

<script>
window.print();
</script>

</body>
</html>