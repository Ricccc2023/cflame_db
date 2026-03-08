<?php
require_once '../../includes/config.php';

$id=intval($_GET['id']);

$pending=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM pending_orders WHERE id=$id
"));

/* FIND OR CREATE CUSTOMER */

$check=mysqli_query($conn,"
SELECT * FROM customers 
WHERE customer_name='".$pending['customer_name']."'
");

if(mysqli_num_rows($check)>0){

$customer=mysqli_fetch_assoc($check);
$customer_id=$customer['id'];

}else{

mysqli_query($conn,"
INSERT INTO customers(customer_name,contact,address)
VALUES(
'".$pending['customer_name']."',
'".$pending['contact']."',
'".$pending['address']."'
)");

$customer_id=mysqli_insert_id($conn);
}

/* CREATE ORDER */

mysqli_query($conn,"
INSERT INTO orders(customer_id,order_date,total)
VALUES($customer_id,CURDATE(),0)
");

$order_id=mysqli_insert_id($conn);

/* GET PRODUCT PRICE */

$product=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT price FROM products WHERE id=".$pending['product_id']
));

$price=$product['price'];
$qty=$pending['quantity'];
$subtotal=$price*$qty;

/* INSERT ORDER ITEM */

mysqli_query($conn,"
INSERT INTO order_items(order_id,product_id,quantity,price,subtotal)
VALUES($order_id,
".$pending['product_id'].",
$qty,
$price,
$subtotal)
");

/* UPDATE ORDER TOTAL */

mysqli_query($conn,"
UPDATE orders SET total=$subtotal WHERE id=$order_id
");

/* DELETE PENDING */

mysqli_query($conn,"DELETE FROM pending_orders WHERE id=$id");

header("Location: ../orders/index.php");