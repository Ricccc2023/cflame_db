<?php
require_once '../includes/config.php';
require_once '../includes/sms_textbee.php';

$id = intval($_GET['id']);

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM pending_orders WHERE id=$id
"));

if(!$pending){
header("Location:index.php");
exit;
}

/* ===============================
SMS DATA
=============================== */
$name = $pending['customer_name'];
$contact = $pending['contact'];
$address = $pending['address'];
$payment = $pending['mode_of_payment'];
$date = date("F d, Y");

/* ===============================
MOVE RECEIPT
=============================== */

$receipt = $pending['receipt_image'];

if($receipt){
$source = "uploads/".$receipt;
$destination = "../orders/uploads/".$receipt;

if(file_exists($source)){
if(!is_dir("../orders/uploads")){
mkdir("../orders/uploads",0777,true);
}
rename($source,$destination);
}
}

/* ===============================
STOCK CHECK
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
CUSTOMER
=============================== */

$nameEsc = mysqli_real_escape_string($conn,$name);
$contactEsc = mysqli_real_escape_string($conn,$contact);
$addressEsc = mysqli_real_escape_string($conn,$address);

$check = mysqli_query($conn,"
SELECT * FROM customers
WHERE TRIM(customer_name)='$nameEsc'
OR TRIM(contact)='$contactEsc'
LIMIT 1
");

if(mysqli_num_rows($check)>0){
$customer=mysqli_fetch_assoc($check);
$customer_id=$customer['id'];

mysqli_query($conn,"
UPDATE customers SET address='$addressEsc' WHERE id=$customer_id
");

}else{

mysqli_query($conn,"
INSERT INTO customers(customer_name,contact,address)
VALUES('$nameEsc','$contactEsc','$addressEsc')
");

$customer_id=mysqli_insert_id($conn);
}

/* ===============================
CREATE ORDER
=============================== */

mysqli_query($conn,"
INSERT INTO orders(customer_id,order_date,total,mode_of_payment,receipt_image)
VALUES($customer_id,CURDATE(),0,'$payment','$receipt')
");

$order_id=mysqli_insert_id($conn);

$invoice = "INV-".date("Ymd")."-".$order_id;

mysqli_query($conn,"
UPDATE orders SET invoice_no='$invoice' WHERE id=$order_id
");

/* ===============================
ITEMS
=============================== */

$items = mysqli_query($conn,"
SELECT * FROM pending_order_items WHERE pending_order_id=$id
");

$total=0;

while($row=mysqli_fetch_assoc($items)){

$pid=$row['product_id'];
$qty=$row['quantity'];
$price=$row['price'];
$subtotal=$row['subtotal'];

$total += $subtotal;

mysqli_query($conn,"
INSERT INTO order_items(order_id,product_id,quantity,price,subtotal)
VALUES($order_id,$pid,$qty,$price,$subtotal)
");

mysqli_query($conn,"
UPDATE products SET quantity = quantity - $qty WHERE id = $pid
");
}

mysqli_query($conn,"
UPDATE orders SET total=$total,total_amount=$total WHERE id=$order_id
");

/* ===============================
DELETE PENDING
=============================== */

mysqli_query($conn,"DELETE FROM pending_order_items WHERE pending_order_id=$id");
mysqli_query($conn,"DELETE FROM pending_orders WHERE id=$id");

/* ===============================
SEND SMS
=============================== */

$message = "Hi $name,

Your order has been CONFIRMED ✅

Address: $address
Payment: $payment

Delivery Date: $date
Please prepare payment (Cash on Delivery).

Thank you!";

$sms = sms_textbee_send($contact, $message);

$status = $sms['ok'] ? 'confirmed' : 'sms_failed';

/* =============================== */

header("Location: ../orders/index.php?success=".$status);
exit;
?>