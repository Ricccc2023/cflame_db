<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

/* SAVE FORM */

if(isset($_POST['submit'])){

$customer_name = mysqli_real_escape_string($conn,$_POST['customer_name']);
$contact = mysqli_real_escape_string($conn,$_POST['contact']);
$address = mysqli_real_escape_string($conn,$_POST['address']);
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);
$message = mysqli_real_escape_string($conn,$_POST['message']);

mysqli_query($conn,"
INSERT INTO pending_orders
(customer_name,contact,address,product_id,quantity,message)
VALUES
('$customer_name','$contact','$address',$product_id,$quantity,'$message')
");

header("Location: index.php");
exit;

}

/* PRODUCTS */

$products = mysqli_query($conn,"
SELECT id,product_name,price FROM products ORDER BY product_name ASC
");

?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Create Pending Order</h2>
<p class="sub">Manual Pending Order Entry</p>
</div>

<div class="page-action">
<a href="index.php" class="btn-decline">Back</a>
</div>

</div>


<div class="card">

<form method="POST">

<div class="form-group">
<label>Customer Name</label>
<input type="text" name="customer_name" required>
</div>

<div class="form-group">
<label>Contact</label>
<input type="text" name="contact">
</div>

<div class="form-group">
<label>Address</label>
<textarea name="address"></textarea>
</div>

<div class="form-group">
<label>Product</label>

<select name="product_id" required>

<option value="">Select Product</option>

<?php while($p=mysqli_fetch_assoc($products)): ?>

<option value="<?= $p['id'] ?>">
<?= $p['product_name'] ?> - ₱<?= number_format($p['price'],2) ?>
</option>

<?php endwhile; ?>

</select>

</div>

<div class="form-group">
<label>Quantity</label>
<input type="number" name="quantity" min="1" required>
</div>

<div class="form-group">
<label>Message / Notes</label>
<textarea name="message"></textarea>
</div>

<br>

<button type="submit" name="submit" class="btn-add">
Save Pending Order
</button>

</form>

</div>

</div>

<?php include '../../includes/footer.php'; ?>