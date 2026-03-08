<?php
include "../../includes/auth.php";
include "../../includes/config.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$id = intval($_GET['id']);

$data = mysqli_query($conn,"SELECT * FROM users WHERE id=$id");
$row = mysqli_fetch_assoc($data);
?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>View User</h2>
<p class="sub">User information</p>
</div>

</div>

<div class="card">

<table class="result-table">

<tr>
<th>ID</th>
<td><?= $row['id'] ?></td>
</tr>

<tr>
<th>Full Name</th>
<td><?= htmlspecialchars($row['fullname']) ?></td>
</tr>

<tr>
<th>Username</th>
<td><?= htmlspecialchars($row['username']) ?></td>
</tr>

<tr>
<th>Role</th>
<td><?= $row['role'] ?></td>
</tr>

<tr>
<th>Availability</th>
<td><?= $row['availability'] ? 'Available' : 'Offline' ?></td>
</tr>

</table>

</div>

</div>

<?php include "../../includes/footer.php"; ?>