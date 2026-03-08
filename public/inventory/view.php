<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$id = $_GET['id'];

$product = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM products WHERE id=$id"));
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="card">

<h2>Product Details</h2>

<table class="result-table">

<tr>
<th>Product ID</th>
<td><?php echo $product['product_id']; ?></td>
</tr>

<tr>
<th>Product Name</th>
<td><?php echo $product['product_name']; ?></td>
</tr>

<tr>
<th>Category</th>
<td><?php echo $product['category']; ?></td>
</tr>

<tr>
<th>Brand</th>
<td><?php echo $product['brand']; ?></td>
</tr>

<tr>
<th>Model</th>
<td><?php echo $product['model_number']; ?></td>
</tr>

<tr>
<th>Quantity</th>
<td><?php echo $product['quantity']; ?></td>
</tr>

<tr>
<th>Unit</th>
<td><?php echo $product['unit']; ?></td>
</tr>

<tr>
<th>Price</th>
<td>₱<?php echo number_format($product['price'],2); ?></td>
</tr>

<tr>
<th>Storage Location</th>
<td><?php echo $product['storage_location']; ?></td>
</tr>

<tr>
<th>Status</th>
<td><?php echo $product['status']; ?></td>
</tr>

<tr>
<th>Date Purchased</th>
<td><?php echo $product['date_purchased']; ?></td>
</tr>

<tr>
<th>Last Inspection</th>
<td><?php echo $product['last_inspection_date']; ?></td>
</tr>

<tr>
<th>Next Inspection</th>
<td><?php echo $product['next_inspection_date']; ?></td>
</tr>

</table>

</div>
</div>

<?php include '../../includes/footer.php'; ?>