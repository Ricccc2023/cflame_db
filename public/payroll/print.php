<?php
require_once "../../includes/config.php";

$id = intval($_GET['id']);

$user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM users
WHERE id=$id
"));

$month = date("m");
$year = date("Y");

$q = mysqli_query($conn,"
SELECT *
FROM attendance
WHERE user_id=$id
AND type='IN'
AND MONTH(time)='$month'
AND YEAR(time)='$year'
");

$daysWorked = mysqli_num_rows($q);

$salary = $daysWorked * $user['per_day'];
?>

<!DOCTYPE html>
<html>
<head>

<title>Payslip</title>

<style>

body{
font-family:Arial;
}

.wrapper{
width:500px;
margin:auto;
border:1px solid #000;
padding:20px;
}

h2{
text-align:center;
}

table{
width:100%;
}

</style>

</head>

<body>

<div class="wrapper">

<h2>Payslip</h2>

<table>

<tr>
<td>Name</td>
<td><?= $user['fullname'] ?></td>
</tr>

<tr>
<td>Month</td>
<td><?= date("F Y") ?></td>
</tr>

<tr>
<td>Per Day</td>
<td>₱<?= number_format($user['per_day'],2) ?></td>
</tr>

<tr>
<td>Days Worked</td>
<td><?= $daysWorked ?></td>
</tr>

<tr>
<td><strong>Total Salary</strong></td>
<td>
<strong>
₱<?= number_format($salary,2) ?>
</strong>
</td>
</tr>

</table>

</div>

<script>
window.print();
</script>

</body>
</html>