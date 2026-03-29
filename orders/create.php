<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$order_id = $_GET['order'] ?? null;
$error = "";

/* ===============================
UPLOAD FOLDER
=============================== */

$uploadDir = "uploads/";

if(!is_dir($uploadDir)){
    mkdir($uploadDir,0777,true);
}

/* ===============================
CREATE ORDER
=============================== */

if(isset($_POST['start_order'])){

    $customer = $_POST['customer'];
    $date     = $_POST['date'];
    $payment  = $_POST['mode_of_payment'];

    /* GET ADDRESS FROM CUSTOMER */
    $cust = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT address FROM customers WHERE id='$customer'
    "));
    $address = $cust['address'] ?? '';

    $receipt = NULL;

    if($payment=="GCash" && !empty($_FILES['receipt']['name'])){

        $filename   = time().'_'.basename($_FILES['receipt']['name']);
        $targetPath = $uploadDir.$filename;

        if(move_uploaded_file($_FILES['receipt']['tmp_name'],$targetPath)){
            $receipt = $filename;
        }
    }

    /* INSERT ORDER WITH ADDRESS */

    mysqli_query($conn,"
    INSERT INTO orders (customer_id,address,order_date,total,mode_of_payment,receipt_image)
    VALUES ('$customer','$address','$date',0,'$payment','$receipt')
    ");

    $order_id = mysqli_insert_id($conn);

    /* GENERATE INVOICE */

    $invoice_no = "INV-".date("Ymd")."-".$order_id;

    mysqli_query($conn,"
    UPDATE orders
    SET invoice_no='$invoice_no'
    WHERE id=$order_id
    ");

    header("Location:create.php?order=".$order_id);
    exit;
}

/* ===============================
ADD PRODUCT
=============================== */

if(isset($_POST['add_product'])){

    $order_id  = $_POST['order_id'];
    $product_id = $_POST['product'];
    $qty        = $_POST['qty'];

    $product = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT * FROM products WHERE id=$product_id")
    );

    if($qty > $product['quantity']){

        $error = "Not enough stock available.";

    }else{

        $price    = $product['price'];
        $subtotal = $price * $qty;

        mysqli_query($conn,"
        INSERT INTO order_items
        (order_id,product_id,quantity,price,subtotal)
        VALUES
        ('$order_id','$product_id','$qty','$price','$subtotal')
        ");

        mysqli_query($conn,"
        UPDATE products
        SET quantity = quantity - $qty
        WHERE id=$product_id
        ");

        header("Location:create.php?order=".$order_id);
        exit;
    }
}

/* ===============================
CONFIRM ORDER
=============================== */

if(isset($_POST['confirm_order'])){

    $order_id = $_POST['order_id'];

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

    header("Location:view.php?id=".$order_id);
    exit;
}

$customers = mysqli_query($conn,"SELECT * FROM customers");
$products  = mysqli_query($conn,"SELECT * FROM products WHERE quantity > 0");
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-action">
<a href="index.php" class="btn-decline">Back</a>
</div>

</div>

<?php if(!$order_id): ?>

<div class="card form-wrapper">

<h2>Create Order</h2>

<form method="POST" enctype="multipart/form-data">

<div class="form-row">
<label>Customer</label>

<select name="customer">

<?php while($c=mysqli_fetch_assoc($customers)): ?>

<option value="<?= $c['id'] ?>">
<?= $c['customer_name'] ?>
</option>

<?php endwhile; ?>

</select>

</div>

<!-- ADDRESS DISPLAY -->

<div class="form-row">
<label>Address</label>
<input type="text" id="customerAddress" readonly>
</div>

<div class="form-row">
<label>Order Date</label>
<input type="date" name="date" required>
</div>

<div class="form-row">
<label>Mode of Payment</label>

<select name="mode_of_payment" id="payment" required>

<option value="">Select Payment</option>
<option value="GCash">GCash</option>
<option value="Cash on Delivery">Cash on Delivery</option>

</select>

</div>

<div class="form-row" id="receiptField" style="display:none;">
<label>Upload GCash Receipt</label>
<input type="file" name="receipt" accept="image/*">
</div>

<button class="btn-save" name="start_order">
Start Order
</button>

</form>

</div>

<?php else: ?>

<div class="card">

<h2>Add Products to Order</h2>

<form method="POST">

<input type="hidden" name="order_id" value="<?= $order_id ?>">

<div class="form-row">
<label>Product</label>

<select name="product">

<?php while($p=mysqli_fetch_assoc($products)): ?>

<option value="<?= $p['id'] ?>">
<?= $p['product_name'] ?> (Stock: <?= $p['quantity'] ?>)
</option>

<?php endwhile; ?>

</select>

</div>

<div class="form-row">
<label>Quantity</label>
<input type="number" name="qty" required>
</div>

<div style="display:flex; align-items:center; gap:10px;">

<button class="btn-save" name="add_product">
Add Product
</button>

<?php if(!empty($error)): ?>
<span style="color:#dc3545;font-weight:600;">
<?= $error ?>
</span>
<?php endif; ?>

</div>

</form>

</div>

<div class="card">

<h2>Order Items</h2>

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

<?php

$items = mysqli_query($conn,"
SELECT order_items.*,products.product_name
FROM order_items
LEFT JOIN products
ON products.id = order_items.product_id
WHERE order_id = $order_id
");

$total = 0;

while($row=mysqli_fetch_assoc($items)):

$total += $row['subtotal'];

?>

<tr>
<td><?= $row['product_name'] ?></td>
<td><?= $row['quantity'] ?></td>
<td>₱<?= number_format($row['price'],2) ?></td>
<td>₱<?= number_format($row['subtotal'],2) ?></td>
</tr>

<?php endwhile; ?>

</tbody>

</table>

<h3>Total: ₱<?= number_format($total,2) ?></h3>

<form method="POST">

<input type="hidden" name="order_id" value="<?= $order_id ?>">

<button class="btn-save" name="confirm_order">
Confirm Order
</button>

</form>

</div>

<?php endif; ?>

</div>

<script>

/* PAYMENT TOGGLE */
document.getElementById("payment").addEventListener("change",function(){
    document.getElementById("receiptField").style.display =
    this.value=="GCash" ? "block" : "none";
});

/* AUTO LOAD ADDRESS */

const customerData = {};

<?php
$cust_js = mysqli_query($conn,"SELECT id,address FROM customers");
while($c = mysqli_fetch_assoc($cust_js)){
    echo "customerData['".$c['id']."'] = '".addslashes($c['address'])."';";
}
?>

document.querySelector('select[name="customer"]').addEventListener('change',function(){
    document.getElementById('customerAddress').value =
    customerData[this.value] ?? '';
});

/* INITIAL LOAD */
document.querySelector('select[name="customer"]').dispatchEvent(new Event('change'));

</script>

<?php include '../includes/footer.php'; ?>