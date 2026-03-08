<?php
require_once '../../includes/config.php';

$id = intval($_GET['id']);

/* ===============================
GET PENDING ORDER
=============================== */

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM pending_orders WHERE id=$id
"));

if(!$pending){
header("Location:index.php");
exit;
}


/* ===============================
GET ITEMS + STOCK
=============================== */

$items = mysqli_query($conn,"
SELECT pending_order_items.*, 
products.product_name,
products.quantity AS stock
FROM pending_order_items
LEFT JOIN products 
ON products.id = pending_order_items.product_id
WHERE pending_order_items.pending_order_id = $id
");


/* ===============================
CHECK STOCK
=============================== */

$insufficient = [];

while($row=mysqli_fetch_assoc($items)){

if($row['quantity'] > $row['stock']){

$insufficient[] =
$row['product_name']." (Needed ".$row['quantity']." / Stock ".$row['stock'].")";

}

}

if(count($insufficient)>0){

$message="Insufficient stock for: ".implode(", ",$insufficient);

header("Location:index.php?error=".urlencode($message));
exit;

}


/* ===============================
CUSTOMER DETECTION
NAME + CONTACT
=============================== */

$name = mysqli_real_escape_string($conn,$pending['customer_name']);
$contact = mysqli_real_escape_string($conn,$pending['contact']);
$address = mysqli_real_escape_string($conn,$pending['address']);

$check = mysqli_query($conn,"
SELECT * FROM customers
WHERE customer_name='$name'
AND contact='$contact'
LIMIT 1
");

if(mysqli_num_rows($check)>0){

/* EXISTING CUSTOMER */

$customer=mysqli_fetch_assoc($check);
$customer_id=$customer['id'];

/* UPDATE ADDRESS IF CHANGED */

mysqli_query($conn,"
UPDATE customers
SET address='$address'
WHERE id=$customer_id
");

}else{

/* CREATE NEW CUSTOMER */

mysqli_query($conn,"
INSERT INTO customers(customer_name,contact,address)
VALUES('$name','$contact','$address')
");

$customer_id=mysqli_insert_id($conn);

}


/* ===============================
CREATE ORDER
=============================== */

mysqli_query($conn,"
INSERT INTO orders(customer_id,order_date,total,mode_of_payment)
VALUES(
$customer_id,
CURDATE(),
0,
'".$pending['mode_of_payment']."'
)
");

$order_id=mysqli_insert_id($conn);


/* ===============================
GENERATE INVOICE NUMBER
=============================== */

$invoice = "INV-".date("Ymd")."-".$order_id;

mysqli_query($conn,"
UPDATE orders
SET invoice_no='$invoice'
WHERE id=$order_id
");


/* ===============================
INSERT ORDER ITEMS
=============================== */

$items = mysqli_query($conn,"
SELECT * FROM pending_order_items
WHERE pending_order_id=$id
");

$total=0;

while($row=mysqli_fetch_assoc($items)){

$pid=$row['product_id'];
$qty=$row['quantity'];
$price=$row['price'];
$subtotal=$row['subtotal'];

$total += $subtotal;


/* INSERT ORDER ITEM */

mysqli_query($conn,"
INSERT INTO order_items(order_id,product_id,quantity,price,subtotal)
VALUES($order_id,$pid,$qty,$price,$subtotal)
");


/* UPDATE PRODUCT STOCK */

mysqli_query($conn,"
UPDATE products
SET quantity = quantity - $qty
WHERE id = $pid
");

}


/* ===============================
UPDATE ORDER TOTAL
=============================== */

mysqli_query($conn,"
UPDATE orders
SET total=$total,total_amount=$total
WHERE id=$order_id
");


/* ===============================
DELETE PENDING DATA
=============================== */

mysqli_query($conn,"
DELETE FROM pending_order_items
WHERE pending_order_id=$id
");

mysqli_query($conn,"
DELETE FROM pending_orders
WHERE id=$id
");


header("Location: ../orders/index.php");
exit;