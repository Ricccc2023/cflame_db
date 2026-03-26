<?php
include "../../includes/auth.php";
include "../../includes/config.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$search_name = $_GET['customer_name'] ?? '';
$search_contact = $_GET['contact'] ?? '';
$search_address = $_GET['address'] ?? '';

$sql = "SELECT * FROM customers WHERE 1=1";

if($search_name != ""){
$sql .= " AND customer_name LIKE '%$search_name%'";
}

if($search_contact != ""){
$sql .= " AND contact LIKE '%$search_contact%'";
}

if($search_address != ""){
$sql .= " AND address LIKE '%$search_address%'";
}

$sql .= " ORDER BY id DESC";

$q = mysqli_query($conn,$sql);
?>

<div class="main">

<div class="page-header">
<div class="page-title">
<h2>Customers Management</h2>
</div>

<div class="page-action">
<a href="create.php" class="btn-add">+ Add Customer</a>
</div>
</div>

<div class="card">

<form method="GET" class="filter-bar">

<input type="text" name="customer_name" placeholder="Customer Name" value="<?= htmlspecialchars($search_name) ?>">

<input type="text" name="contact" placeholder="Contact" value="<?= htmlspecialchars($search_contact) ?>">

<input type="text" name="address" placeholder="Address" value="<?= htmlspecialchars($search_address) ?>">

<button class="btn-search">Filter</button>

</form>

<div class="table-wrap">

<table>

<thead>
<tr>
<th>ID</th>
<th>Customer Name</th>
<th>Contact</th>
<th>Address</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($q)) { ?>

<tr>

<td><?= $row['id'] ?></td>

<td>
<strong><?= htmlspecialchars($row['customer_name']) ?></strong>
</td>

<td><?= htmlspecialchars($row['contact']) ?></td>

<td><?= htmlspecialchars($row['address']) ?></td>

<td>

<div class="actions">

<a href="view.php?id=<?= $row['id'] ?>" class="action-btn action-secondary">View</a>

<a href="edit.php?id=<?= $row['id'] ?>" class="action-btn action-success">Edit</a>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include "../../includes/footer.php"; ?>