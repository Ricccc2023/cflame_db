<?php
require_once '../../includes/config.php';

$id=intval($_GET['id']);

$data=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM pending_orders WHERE id=$id
"));

mysqli_query($conn,"
INSERT INTO declined_orders_archive
(customer_name,contact,address,product_id,quantity,message)
VALUES(
'".$data['customer_name']."',
'".$data['contact']."',
'".$data['address']."',
".$data['product_id'].",
".$data['quantity'].",
'".$data['message']."'
)");

mysqli_query($conn,"DELETE FROM pending_orders WHERE id=$id");

header("Location: declined_archive.php");