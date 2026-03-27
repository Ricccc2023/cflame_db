<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$product_name = $_GET['product_name'] ?? '';
$category = $_GET['category'] ?? '';
$brand = $_GET['brand'] ?? '';

$sql = "SELECT * FROM products WHERE 1=1";

if($product_name != ""){
$sql .= " AND product_name LIKE '%$product_name%'";
}

if($category != ""){
$sql .= " AND category LIKE '%$category%'";
}

if($brand != ""){
$sql .= " AND brand LIKE '%$brand%'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn,$sql);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Inventory Management</h2>
</div>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
    <div class="page-action">
        <a href="create.php" class="btn-add">Add Product</a>
    </div>
<?php } ?>

</div>

<div class="card">

<form method="GET" class="filter-bar">

<input type="text" name="product_name" placeholder="Product Name" value="<?= htmlspecialchars($product_name) ?>">

<input type="text" name="category" placeholder="Category" value="<?= htmlspecialchars($category) ?>">

<input type="text" name="brand" placeholder="Brand" value="<?= htmlspecialchars($brand) ?>">

<button class="btn-search">Filter</button>

</form>

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

<td><?= $row['product_id']; ?></td>

<td>
<strong><?= $row['product_name']; ?></strong>
</td>

<td><?= $row['category']; ?></td>

<td><?= $row['brand']; ?></td>

<td><?= $row['model_number']; ?></td>

<td><?= $row['quantity']; ?></td>

<td><?= $row['unit']; ?></td>

<td>₱<?= number_format($row['price'],2); ?></td>

<td><?= $row['storage_location']; ?></td>

<td>

<div class="actions">

<a class="action-btn action-success"
href="view.php?id=<?= $row['id']; ?>">
View
</a>

<a class="action-btn action-secondary"
href="edit.php?id=<?= $row['id']; ?>">
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

<?php include '../includes/footer.php'; ?>