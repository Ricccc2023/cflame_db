<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";

/* GET USER */

$id = intval($_GET['id']);

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
$year  = $_GET['year'] ?? date("Y");

/* ATTENDANCE DATA */

$attendance = [];

$q = mysqli_query($conn,"
SELECT DATE(time) as day
FROM attendance
WHERE user_id=$id
AND type='IN'
AND MONTH(time)='$month'
AND YEAR(time)='$year'
");

while($row=mysqli_fetch_assoc($q)){
$attendance[$row['day']] = true;
}

/* DAYS WORKED */

$daysWorked = count($attendance);

/* SALARY */

$perDay = $user['per_day'];
$totalSalary = $daysWorked * $perDay;

/* CALENDAR DATA */

$daysInMonth = cal_days_in_month(CAL_GREGORIAN,$month,$year);
$firstDay = date('w', strtotime("$year-$month-01"));
$monthName = date("F", mktime(0,0,0,$month,1));

include "../includes/header.php";
include "../includes/sidebar.php";
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


<!-- FILTER -->

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


<div class="card" style="margin-bottom:20px;">

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">

<h3 style="margin:0;">Payroll Summary</h3>

<a 
href="print.php?id=<?= $id ?>&month=<?= $month ?>&year=<?= $year ?>"
target="_blank"
class="action-btn action-secondary"
>
Print
</a>
</div>

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
<strong style="color:#198754;font-size:18px;">
₱<?= number_format($totalSalary,2) ?>
</strong>
</td>
</tr>

</table>

</div>

</div>


<!-- RIGHT SIDE CALENDAR -->

<div style="width:350px;">

<div class="card">

<h3 style="margin-bottom:10px;">
Attendance — <?= $monthName ?> <?= $year ?>
</h3>

<table style="text-align:center; width:100%;">

<thead>

<tr>
<th>Sun</th>
<th>Mon</th>
<th>Tue</th>
<th>Wed</th>
<th>Thu</th>
<th>Fri</th>
<th>Sat</th>
</tr>

</thead>

<tbody>

<tr>

<?php

$counter = 0;

/* EMPTY CELLS */

for($i=0;$i<$firstDay;$i++){
echo "<td></td>";
$counter++;
}

/* DAYS LOOP */

for($d=1;$d<=$daysInMonth;$d++){

$dateCheck = "$year-$month-".str_pad($d,2,'0',STR_PAD_LEFT);

$bg="#ffffff";
$color="#000";

if(isset($attendance[$dateCheck])){
$bg="#198754";
$color="#fff";
}

echo "<td style='background:$bg;color:$color;'>$d</td>";

$counter++;

if($counter % 7 == 0){
echo "</tr><tr>";
}

}

?>

</tr>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include "../includes/footer.php"; ?>