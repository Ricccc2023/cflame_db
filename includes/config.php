<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "cflame_db";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
    die("Database connection failed");
}
define('TEXTBEE_API_BASE', 'https://api.textbee.dev/api/v1');
define('TEXTBEE_API_KEY',  '1d1574c1-c777-4ce6-8c47-cfb3e87d1795');      // 🔁 palitan mo
define('TEXTBEE_DEVICE_ID','69dd0d51b5cd3ce4c75125f7');
?>