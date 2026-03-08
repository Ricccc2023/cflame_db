<?php
include "../../includes/auth.php";
include "../../includes/config.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

/* EXCLUDE ADMIN FROM LIST */
$q = mysqli_query($conn,"SELECT * FROM users WHERE role != 'admin' ORDER BY id DESC");
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
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($q)) { ?>

<?php

$uid = $row['id'];

/* CHECK LAST ATTENDANCE */

$last = mysqli_query($conn,"
SELECT type
FROM attendance
WHERE user_id = $uid
ORDER BY time DESC
LIMIT 1
");

$lastType = "OUT";

if($last && mysqli_num_rows($last) > 0){
$r = mysqli_fetch_assoc($last);
$lastType = $r['type'];
}

?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= htmlspecialchars($row['fullname']) ?></td>

<td><?= $row['role'] ?></td>

<td>

<?php if($lastType == "IN"){ ?>

<span style="color:#198754;font-weight:bold;">ON DUTY</span>

<?php } else { ?>

<span style="color:#dc3545;font-weight:bold;">OFF DUTY</span>

<?php } ?>

</td>

<td>

<div class="actions">

<?php if($lastType == "IN"){ ?>

<a href="time_out.php?id=<?= $row['id'] ?>"
class="action-btn action-danger"
onclick="return confirm('Time OUT this staff?')">

TIME OUT

</a>

<?php } else { ?>

<a href="time_in.php?id=<?= $row['id'] ?>"
class="action-btn action-success"
onclick="return confirm('Time IN this staff?')">

TIME IN

</a>

<?php } ?>

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