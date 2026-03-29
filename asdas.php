<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";

/* ADMIN SECURITY */
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
echo "<script>
alert('Access Denied. Admin only.');
window.location='../dashboard.php';
</script>";
exit;
}

/* FILTER (DATE RANGE) */
$from = $_GET['from'] ?? date("Y-m-01"); // first day of month
$to   = $_GET['to'] ?? date("Y-m-d");    // today

/* UPDATE PER DAY */
if(isset($_POST['update_salary'])){
$id = intval($_POST['user_id']);
$salary = floatval($_POST['per_day']);

mysqli_query($conn,"
UPDATE users
SET per_day = '$salary'
WHERE id = $id
");
}

/* FETCH STAFF */
$q = mysqli_query($conn,"
SELECT *
FROM users
WHERE role='staff'
ORDER BY fullname ASC
");

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="main">

<div class="page-header">
<div class="page-title">
<h2>Payroll Management</h2>
</div>
</div>

<!-- FILTER DATE RANGE -->
<div class="card">

<form method="GET" class="filter-bar" style="display:flex;gap:10px;align-items:center;">

<label>From:</label>
<input type="date" name="from" value="<?= $from ?>">

<label>To:</label>
<input type="date" name="to" value="<?= $to ?>">

<button class="btn-search">Filter</button>

</form>

</div>

<div class="card">
<div class="table-wrap">

<table>

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Per Day</th>
<th>Days Worked</th>
<th>Total Salary</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($q)): ?>

<?php
$userId = $row['id'];

/* ATTENDANCE QUERY (DATE RANGE) */
$attendance = [];

$res = mysqli_query($conn,"
SELECT DATE(time) as day
FROM attendance
WHERE user_id=$userId
AND type='IN'
AND DATE(time) BETWEEN '$from' AND '$to'
");

while($r=mysqli_fetch_assoc($res)){
$attendance[$r['day']] = true;
}

$daysWorked = count($attendance);

/* SALARY COMPUTATION */
$perDay = $row['per_day'];
$totalSalary = $daysWorked * $perDay;
?>

<tr>

<td><?= $row['id'] ?></td>

<td>
<strong><?= htmlspecialchars($row['fullname']) ?></strong>
</td>

<td>
<form method="POST" style="display:flex;gap:5px;">
<input type="hidden" name="user_id" value="<?= $row['id'] ?>">

<input type="number"
name="per_day"
value="<?= $row['per_day'] ?>"
step="0.01"
style="width:100px;">

<button name="update_salary"
class="action-btn action-success">
Save
</button>
</form>
</td>

<td><?= $daysWorked ?></td>

<td>
<strong style="color:#198754;">
₱<?= number_format($totalSalary,2) ?>
</strong>
</td>

<td>
<div class="actions">

<a href="view.php?id=<?= $row['id'] ?>&from=<?= $from ?>&to=<?= $to ?>"
class="action-btn action-success">
View
</a>

<a href="print.php?id=<?= $row['id'] ?>&from=<?= $from ?>&to=<?= $to ?>"
class="action-btn action-secondary"
target="_blank">
Print
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

<?php include "../includes/footer.php"; ?>