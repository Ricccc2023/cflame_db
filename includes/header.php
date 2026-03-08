<?php
$fullname = $_SESSION["fullname"] ?? "User";
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>CFLAME System</title>

<link rel="stylesheet" href="/cflame_db/fireprotection/assets/css/main.css">

</head>

<body>

<div class="topbar">

<div style="display:flex; justify-content:space-between; align-items:center;">

<div>
CFLAME Fire Protection Equipment Management System
</div>

<div style="font-size:14px;">
Welcome, <b><?= htmlspecialchars($fullname) ?></b>
</div>

</div>

</div>

<div class="app">