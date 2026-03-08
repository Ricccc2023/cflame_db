<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$id = $_GET['id'];

$product = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM products WHERE id=$id"));

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

$sql = "UPDATE products SET

product_id='$product_id',
product_name='$product_name',
category='$category',
brand='$brand',
model_number='$model',
quantity='$quantity',
price='$price',
unit='$unit',
storage_location='$storage',
status='$status',
date_purchased='$date_purchased',
last_inspection_date='$last_inspection',
next_inspection_date='$next_inspection'

WHERE id=$id";

mysqli_query($conn,$sql);

header("Location:index.php");
exit;
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="card form-wrapper">

<h2>Edit Product</h2>

<form method="POST">

<div class="form-row">
<label>Product ID</label>
<input type="text" name="product_id" value="<?php echo $product['product_id']; ?>">
</div>

<div class="form-row">
<label>Product Name</label>
<input type="text" name="product_name" value="<?php echo $product['product_name']; ?>">
</div>

<div class="form-row">
<label>Category</label>
<input type="text" name="category" value="<?php echo $product['category']; ?>">
</div>

<div class="form-row">
<label>Brand</label>
<input type="text" name="brand" value="<?php echo $product['brand']; ?>">
</div>

<div class="form-row">
<label>Model</label>
<input type="text" name="model" value="<?php echo $product['model_number']; ?>">
</div>

<div class="form-row">
<label>Quantity</label>
<input type="number" name="quantity" value="<?php echo $product['quantity']; ?>">
</div>

<div class="form-row">
<label>Unit</label>
<input type="text" name="unit" value="<?php echo $product['unit']; ?>">
</div>

<div class="form-row">
<label>Price</label>
<input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>">
</div>

<div class="form-row">
<label>Storage Location</label>
<input type="text" name="storage" value="<?php echo $product['storage_location']; ?>">
</div>

<div class="form-row">
<label>Status</label>
<select name="status">

<option <?php if($product['status']=="Available") echo "selected"; ?>>Available</option>
<option <?php if($product['status']=="In Use") echo "selected"; ?>>In Use</option>

</select>
</div>

<div class="form-row">
<label>Date Purchased</label>
<input type="date" name="date_purchased" value="<?php echo $product['date_purchased']; ?>">
</div>

<div class="form-row">
<label>Last Inspection</label>
<input type="date" name="last_inspection" value="<?php echo $product['last_inspection_date']; ?>">
</div>

<div class="form-row">
<label>Next Inspection</label>
<input type="date" name="next_inspection" value="<?php echo $product['next_inspection_date']; ?>">
</div>

<button class="btn-save">Update Product</button>

</form>

</div>
</div>

<?php include '../../includes/footer.php'; ?>