<?php
require_once "../../includes/config.php";
require_once "../../includes/auth.php";

$id = intval($_GET['id']);

/* USER INFO */

$user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id=$id
"));

if(!$user){
exit("User not found");
}

/* FILTER */

$month = $_GET['month'] ?? date("m");
$year = $_GET['year'] ?? date("Y");

/* ATTENDANCE */

$q = mysqli_query($conn,"
SELECT *
FROM attendance
WHERE user_id=$id
AND type='IN'
AND MONTH(time)='$month'
AND YEAR(time)='$year'
");

$daysWorked = mysqli_num_rows($q);

/* PAYROLL */

$perDay = $user['per_day'];
$totalSalary = $daysWorked * $perDay;

include "../../includes/header.php";
include "../../includes/sidebar.php";
?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2><?= htmlspecialchars($user['fullname']) ?> Payroll</h2>
</div>

<div class="page-action">
<a href="index.php" class="btn-add">Back</a>
</div>

</div>

<div class="card">

<form method="GET" class="filter-bar">

<input type="hidden" name="id" value="<?= $id ?>">

<select name="month">

<?php for($m=1;$m<=12;$m++): ?>

<option value="<?= $m ?>"
<?= $month==$m?'selected':'' ?>>
<?= date("F",mktime(0,0,0,$m,1)) ?>
</option>

<?php endfor; ?>

</select>

<select name="year">

<?php for($y=date("Y");$y>=2024;$y--): ?>

<option value="<?= $y ?>"
<?= $year==$y?'selected':'' ?>>
<?= $y ?>
</option>

<?php endfor; ?>

</select>

<button class="btn-search">Filter</button>

</form>

</div>

<div class="card">

<h3>Payroll Summary</h3>

<table>

<tr>
<td>Per Day Salary</td>
<td>₱<?= number_format($perDay,2) ?></td>
</tr>

<tr>
<td>Days Worked</td>
<td><?= $daysWorked ?></td>
</tr>

<tr>
<td><strong>Total Salary</strong></td>
<td>
<strong>
₱<?= number_format($totalSalary,2) ?>
</strong>
</td>
</tr>

</table>

</div>

</div>

<?php include "../../includes/footer.php"; ?>