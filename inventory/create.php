<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if($_SERVER['REQUEST_METHOD']=="POST"){

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$category = $_POST['category'];
$brand = $_POST['brand'];
$model = $_POST['model'];
$quantity = $_POST['quantity'];
$unit = $_POST['unit'];
$price = $_POST['price'];
$storage = $_POST['storage'];
$status = $_POST['status'];
$date_purchased = $_POST['date_purchased'];
$last_inspection = $_POST['last_inspection'];
$next_inspection = $_POST['next_inspection'];
//
$sql = "INSERT INTO products
(product_id,product_name,category,brand,model_number,quantity,price,unit,storage_location,status,date_purchased,last_inspection_date,next_inspection_date)

VALUES
('$product_id','$product_name','$category','$brand','$model','$quantity','$price','$unit','$storage','$status','$date_purchased','$last_inspection','$next_inspection')";

mysqli_query($conn,$sql);

header("Location:index.php");
exit;
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">
<div class="page-header">

<div class="page-action">
<a href="index.php" class="btn-decline">Back</a>
</div>

</div>
<div class="card form-wrapper">

<h2>Add Product</h2>

<form method="POST">

<div class="form-row">
<label>Product ID</label>
<input type="text" name="product_id" required>
</div>

<div class="form-row">
<label>Product Name</label>
<input type="text" name="product_name" required>
</div>

<div class="form-row">
<label>Category</label>
<input type="text" name="category">
</div>

<div class="form-row">
<label>Brand / Manufacturer</label>
<input type="text" name="brand">
</div>

<div class="form-row">
<label>Model Number</label>
<input type="text" name="model">
</div>

<div class="form-row">
<label>Quantity</label>
<input type="number" name="quantity">
</div>

<div class="form-row">
<label>Unit</label>
<input type="text" name="unit">
</div>

<div class="form-row">
<label>Price</label>
<input type="number" step="0.01" name="price">
</div>

<div class="form-row">
<label>Storage Location</label>
<input type="text" name="storage">
</div>

<div class="form-row">
<label>Status</label>
<select name="status">
<option>Available</option>
<option>In Use</option>
</select>
</div>

<div class="form-row">
<label>Date Purchased</label>
<input type="date" name="date_purchased">
</div>

<div class="form-row">
<label>Last Inspection Date</label>
<input type="date" name="last_inspection">
</div>

<div class="form-row">
<label>Next Inspection Date</label>
<input type="date" name="next_inspection">
</div>

<button class="btn-save">Save Product</button>

</form>

</div>
</div>

<?php include '../includes/footer.php'; ?>