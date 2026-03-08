<?php
include "../../includes/auth.php";
include "../../includes/config.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

$id = intval($_GET['id']);

/* USER DATA */
$data = mysqli_query($conn,"SELECT * FROM users WHERE id=$id");
$row = mysqli_fetch_assoc($data);

/* ATTENDANCE DATA */
$attendance = mysqli_query($conn,"
SELECT * FROM attendance
WHERE user_id = $id
ORDER BY time DESC
LIMIT 30
");
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

</table>

</div>


<div class="card">

<h3>Attendance Calendar</h3>

<table class="result-table">

<tr>
<th>Date</th>
<th>Time</th>
<th>Status</th>
</tr>

<?php if($attendance && mysqli_num_rows($attendance) > 0){ ?>

<?php while($a = mysqli_fetch_assoc($attendance)) { ?>

<tr>

<td><?= date("M d, Y", strtotime($a['time'])) ?></td>

<td><?= date("h:i A", strtotime($a['time'])) ?></td>

<td>

<?php if($a['type'] == "IN"){ ?>

<span style="color:green;font-weight:bold">IN</span>

<?php } else { ?>

<span style="color:red;font-weight:bold">OUT</span>

<?php } ?>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>
<td colspan="3" style="text-align:center;">No attendance records yet</td>
</tr>

<?php } ?>

</table>

</div>

</div>

<?php include "../../includes/footer.php"; ?>