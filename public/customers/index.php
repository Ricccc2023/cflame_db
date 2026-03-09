<?php
include "../../includes/auth.php";
include "../../includes/config.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$q = mysqli_query($conn,"SELECT * FROM customers ORDER BY id DESC");
?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Customers Management</h2>
<p class="sub">Customers who purchased equipment</p>
</div>

<div class="page-action">
<a href="create.php" class="btn-add">+ Add Customer</a>
</div>

</div>

<div class="card">

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
<td><?= htmlspecialchars($row['customer_name']) ?></td>
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