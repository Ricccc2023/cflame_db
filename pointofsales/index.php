<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

/* PRODUCTS */
$products = mysqli_query($conn,"
SELECT * FROM products 
WHERE quantity > 0 
ORDER BY product_name ASC
");

/* START POS */
if(isset($_POST['start_pos'])){

    $date = date("Y-m-d");

    mysqli_query($conn,"
    INSERT INTO orders (customer_id,order_date,total,mode_of_payment)
    VALUES (NULL,'$date',0,'Cash')
    ");

    $order_id = mysqli_insert_id($conn);

    $invoice_no = "INV-".date("Ymd")."-".$order_id;

    mysqli_query($conn,"
    UPDATE orders SET invoice_no='$invoice_no' WHERE id=$order_id
    ");

    header("Location: index.php?order=".$order_id);
    exit;
}

/* ADD ITEM (NO DUPLICATE) */
if(isset($_POST['add_item'])){

    $order_id  = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $qty        = $_POST['qty'];

    $existing = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM order_items 
    WHERE order_id='$order_id' AND product_id='$product_id'
    "));

    $product = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM products WHERE id=$product_id
    "));

    if($existing){

        $new_qty = $existing['quantity'] + $qty;

        if($new_qty <= ($product['quantity'] + $existing['quantity'])){

            $subtotal = $existing['price'] * $new_qty;

            mysqli_query($conn,"
            UPDATE order_items 
            SET quantity='$new_qty', subtotal='$subtotal'
            WHERE id=".$existing['id']."
            ");

            mysqli_query($conn,"
            UPDATE products 
            SET quantity = quantity - $qty 
            WHERE id=$product_id
            ");
        }

    }else{

        if($qty <= $product['quantity']){

            $price    = $product['price'];
            $subtotal = $price * $qty;

            mysqli_query($conn,"
            INSERT INTO order_items (order_id,product_id,quantity,price,subtotal)
            VALUES ('$order_id','$product_id','$qty','$price','$subtotal')
            ");

            mysqli_query($conn,"
            UPDATE products SET quantity = quantity - $qty WHERE id=$product_id
            ");
        }
    }

    header("Location: index.php?order=".$order_id);
    exit;
}

/* UPDATE QTY */
if(isset($_POST['update_qty'])){

    $item_id = $_POST['item_id'];
    $qty     = $_POST['qty'];

    $item = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM order_items WHERE id=$item_id
    "));

    $product_id = $item['product_id'];
    $old_qty    = $item['quantity'];

    mysqli_query($conn,"
    UPDATE products SET quantity = quantity + $old_qty WHERE id=$product_id
    ");

    $product = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM products WHERE id=$product_id
    "));

    if($qty <= $product['quantity']){

        $subtotal = $item['price'] * $qty;

        mysqli_query($conn,"
        UPDATE order_items 
        SET quantity='$qty', subtotal='$subtotal'
        WHERE id=$item_id
        ");

        mysqli_query($conn,"
        UPDATE products SET quantity = quantity - $qty WHERE id=$product_id
        ");
    }

    header("Location: index.php?order=".$item['order_id']);
    exit;
}

/* REMOVE */
if(isset($_POST['remove_item'])){

    $item_id = intval($_POST['item_id']);

    $item = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT * FROM order_items WHERE id = $item_id
    "));

    mysqli_query($conn,"
        UPDATE products 
        SET quantity = quantity + ".$item['quantity']." 
        WHERE id = ".$item['product_id']."
    ");

    mysqli_query($conn,"
        DELETE FROM order_items WHERE id = $item_id
    ");

    header("Location: index.php?order=".$item['order_id']);
    exit;
}

/* COMPLETE */
if(isset($_POST['complete_order'])){

    $order_id = $_POST['order_id'];

    $name    = mysqli_real_escape_string($conn,$_POST['customer_name']);
    $contact = mysqli_real_escape_string($conn,$_POST['contact']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);

    $check = mysqli_query($conn,"
    SELECT id FROM customers WHERE customer_name='$name'
    ");

    if(mysqli_num_rows($check)>0){
        $cust = mysqli_fetch_assoc($check);
        $customer_id = $cust['id'];
    }else{
        mysqli_query($conn,"
        INSERT INTO customers (customer_name,contact,address)
        VALUES ('$name','$contact','$address')
        ");
        $customer_id = mysqli_insert_id($conn);
    }

    $total = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM(subtotal) as total FROM order_items WHERE order_id=$order_id
    "));

    mysqli_query($conn,"
    UPDATE orders 
    SET customer_id='$customer_id', total=".$total['total']."
    WHERE id=$order_id
    ");

    header("Location: ../orders/print.php?id=".$order_id);
    exit;
}

$order_id = $_GET['order'] ?? null;
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">
<div class="page-title">
<h2>POS (Walk-in)</h2>
</div>
</div>

<?php if(!$order_id): ?>

<div class="card" style="text-align:center; background-color: #4c463c;">
<form method="POST">
<button class="btn-save" name="start_pos">Start New Sale</button>
</form>
</div>

<?php else: ?>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start;">

<!-- PRODUCTS -->
<div class="card">

<h2>Products</h2>

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px;">

<?php while($p=mysqli_fetch_assoc($products)): ?>

<form method="POST" style="border:1px solid #ddd;padding:10px;">

<input type="hidden" name="order_id" value="<?= $order_id ?>">
<input type="hidden" name="product_id" value="<?= $p['id'] ?>">

<strong><?= $p['product_name'] ?></strong><br>
<small>₱<?= number_format($p['price'],2) ?></small><br>
<small>Stock: <?= $p['quantity'] ?></small><br><br>

<input type="number" name="qty" value="1" min="1" style="width:100%;margin-bottom:5px;">

<button class="btn-save" name="add_item" style="width:100%;">Add</button>

</form>

<?php endwhile; ?>

</div>

</div>

<!-- RIGHT -->
<div style="display:flex;flex-direction:column;gap:15px;">

<!-- CART -->
<div class="card">

<h2>Cart</h2>

<table>

<thead>
<tr>
<th>Product</th>
<th>Qty</th>
<th>Subtotal</th>
<th></th>
</tr>
</thead>

<tbody>

<?php
$items = mysqli_query($conn,"
SELECT order_items.*,products.product_name
FROM order_items
LEFT JOIN products ON products.id = order_items.product_id
WHERE order_id = $order_id
");

$total = 0;

while($row=mysqli_fetch_assoc($items)):
$total += $row['subtotal'];
?>

<tr>

<td><?= $row['product_name'] ?></td>

<td>
<form method="POST" style="display:flex;gap:5px;">
<input type="hidden" name="item_id" value="<?= $row['id'] ?>">
<input type="number" name="qty" value="<?= $row['quantity'] ?>" style="width:60px;">
<button class="action-btn action-secondary" name="update_qty">✓</button>
</form>
</td>

<td>₱<?= number_format($row['subtotal'],2) ?></td>

<td>
<form method="POST">
<input type="hidden" name="item_id" value="<?= $row['id'] ?>">
<button class="action-btn action-danger" name="remove_item">X</button>
</form>
</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

<h3>Total: ₱<?= number_format($total,2) ?></h3>

</div>

<!-- CUSTOMER -->
<div class="card form-wrapper">

<h2>Customer Info</h2>

<form method="POST">

<input type="hidden" name="order_id" value="<?= $order_id ?>">

<div class="form-row">
<label>Name</label>
<input type="text" name="customer_name" required>
</div>

<div class="form-row">
<label>Contact</label>
<input type="text" name="contact">
</div>

<div class="form-row">
<label>Address</label>
<input type="text" name="address">
</div>

<button class="btn-save" name="complete_order">
Complete & Print
</button>

</form>

</div>

</div>

</div>

<?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>