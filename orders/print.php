<?php
require_once '../../includes/config.php';
session_start();

$id = intval($_GET['id']);

/* GET ORDER + CUSTOMER */
$order = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
orders.*,
customers.customer_name,
customers.contact
FROM orders
LEFT JOIN customers ON customers.id = orders.customer_id
WHERE orders.id = $id
"));

/* GET ORDER ITEMS */
$items = mysqli_query($conn,"
SELECT 
order_items.*,
products.product_name
FROM order_items
LEFT JOIN products ON products.id = order_items.product_id
WHERE order_items.order_id = $id
");

/* GET CURRENT USER */
$staff = $_SESSION['fullname'] ?? "Authorized Staff";
?>

<!DOCTYPE html>
<html>
<head>

<title>Receipt</title>

<style>

body{
font-family: Arial, sans-serif;
background:#f4f6f9;
padding:30px;
}

.report-container{
width:900px;
margin:auto;
background:#fff;
padding:40px;
box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.logo-section{
display:flex;
align-items:center;
gap:15px;
}

.logo-section img{
width:60px;
height:60px;
object-fit:contain;
}

.clinic-name{
font-size:24px;
font-weight:bold;
}

.header-info{
text-align:right;
}

.header-info h2{
margin:0;
}

/* TABLE */

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

/* PRINT BUTTON */

.no-print{
margin-bottom:10px;
}

@media print{

body{
padding:0;
background:white;
}

.no-print{
display:none;
}

.report-container{
box-shadow:none;
width:100%;
}

}

/* SIGNATURE */

.signature-area{
margin-top:60px;
display:flex;
justify-content:flex-end;
}

.signature-box{
text-align:center;
width:250px;
}

.signature-line{
border-top:1px solid #000;
margin-top:60px;
}

.signature-label{
margin-top:5px;
font-size:13px;
font-weight:bold;
}

</style>

</head>

<body>

<div class="report-container">

<div class="header">

<div class="logo-section">

<img src="logo.png">

<div>
<div class="clinic-name">C'Flame Fire Protection Product Trading</div>
<div>MPJR BLDG General Malvar Ave, Poblacion 4, Santo Tomas, Philippines, 4234</div>
</div>

</div>

<div class="header-info">

<div class="no-print">
<button onclick="window.print()" style="
padding:8px 15px;
background:#000;
border:none;
color:white;
cursor:pointer;
border-radius:4px;
">
Print Receipt
</button>
</div>

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


<h3 style="margin-top:20px;">ORDERED ITEMS</h3>

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


<!-- SIGNATURE -->

<div class="signature-area">

<div class="signature-box">

<div class="signature-line"></div>

<div class="signature-label">
<?= $staff ?>
</div>

</div>

</div>

</div>

</body>
</html>