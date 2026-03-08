<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$result = mysqli_query($conn,"SELECT * FROM products ORDER BY id DESC");
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Inventory</h2>
<p class="sub">Equipment List</p>
</div>

<div class="page-action">
<a href="create.php" class="btn-add">+ Add Product</a>
</div>

</div>

<div class="card">

<div class="table-wrap">

<table>

<thead>
<tr>
<th>Product ID</th>
<th>Product Name</th>
<th>Category</th>
<th>Brand</th>
<th>Model</th>
<th>Quantity</th>
<th>Unit</th>
<th>Price</th>
<th>Storage</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)): ?>

<tr>

<td><?php echo $row['product_id']; ?></td>
<td><?php echo $row['product_name']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['brand']; ?></td>
<td><?php echo $row['model_number']; ?></td>
<td><?php echo $row['quantity']; ?></td>
<td><?php echo $row['unit']; ?></td>
<td>₱<?php echo number_format($row['price'],2); ?></td>
<td><?php echo $row['storage_location']; ?></td>

<td>
<div class="actions">

<a class="action-btn action-success"
href="view.php?id=<?php echo $row['id']; ?>">
View
</a>

<a class="action-btn action-secondary"
href="edit.php?id=<?php echo $row['id']; ?>">
Edit
</a>

</div>
</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>
</div>
</div>

<?php include '../../includes/footer.php'; ?>