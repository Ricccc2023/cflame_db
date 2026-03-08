<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$order_id = $_GET['id'];

$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(subtotal) as total
FROM order_items
WHERE order_id=$order_id
"));

mysqli_query($conn,"
UPDATE orders
SET total=".$total['total']."
WHERE id=$order_id
");

header("Location:view.php?id=$order_id");