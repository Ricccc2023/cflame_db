<?php
include "../../includes/auth.php";
include "../../includes/config.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$q = mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC");
?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>User Management</h2>
<p class="sub">Manage system users</p>
</div>

<div class="page-action">
<a href="create.php" class="btn-add">+ Add User</a>
</div>

</div>

<div class="card">

<div class="table-wrap">

<table>

<thead>
<tr>
<th>ID</th>
<th>Fullname</th>
<th>Role</th>
<th>Attendance</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($q)) { ?>

<tr>

<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['fullname']) ?></td>
<td><?= $row['role'] ?></td>

<td>

<div style="display:flex;flex-direction:column;gap:6px;width:90px">

<a href="time_in.php?id=<?= $row['id'] ?>" 
class="action-btn action-success"
onclick="return confirm('Time IN this staff?')">
IN
</a>

<a href="time_out.php?id=<?= $row['id'] ?>" 
class="action-btn action-danger"
onclick="return confirm('Time OUT this staff?')">
OUT
</a>

</div>

</td>

<td>

<div class="actions">

<a href="view.php?id=<?= $row['id'] ?>" class="action-btn action-secondary">View</a>

<a href="edit.php?id=<?= $row['id'] ?>" class="action-btn action-success">Edit</a>

<a href="delete.php?id=<?= $row['id'] ?>" 
class="action-btn action-danger"
onclick="return confirm('Delete this user?')">Delete</a>

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