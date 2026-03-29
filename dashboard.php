<?php
require_once "includes/config.php";
require_once "includes/auth.php";

date_default_timezone_set("Asia/Manila");

/*
|--------------------------------------------------------------------------
| DAILY SALES
|--------------------------------------------------------------------------
*/

$today = date("Y-m-d");

$q = mysqli_query($conn,"
SELECT SUM(total) as total
FROM orders
WHERE payment_status='paid'
AND order_date='$today'
");

$row = mysqli_fetch_assoc($q);
$daily_sales = $row['total'] ?? 0;


/*
|--------------------------------------------------------------------------
| WEEKLY SALES
|--------------------------------------------------------------------------
*/

$week_start = date("Y-m-d", strtotime("monday this week"));

$q = mysqli_query($conn,"
SELECT SUM(total) as total
FROM orders
WHERE payment_status='paid'
AND order_date >= '$week_start'
");

$row = mysqli_fetch_assoc($q);
$weekly_sales = $row['total'] ?? 0;


/*
|--------------------------------------------------------------------------
| MONTHLY SALES
|--------------------------------------------------------------------------
*/

$month_start = date("Y-m-01");

$q = mysqli_query($conn,"
SELECT SUM(total) as total
FROM orders
WHERE payment_status='paid'
AND order_date >= '$month_start'
");

$row = mysqli_fetch_assoc($q);
$monthly_sales = $row['total'] ?? 0;


/*
|--------------------------------------------------------------------------
| LOW STOCKS (INVENTORY)
|--------------------------------------------------------------------------
*/

$low_stock_list = [];

$q = mysqli_query($conn,"
SELECT product_name, quantity
FROM products
WHERE quantity <= 5
ORDER BY quantity ASC
LIMIT 5
");

while($row = mysqli_fetch_assoc($q)){
    $low_stock_list[] = $row;
}


/*
|--------------------------------------------------------------------------
| PENDING ORDERS TODAY
|--------------------------------------------------------------------------
*/

$pending_today = 0;

$q = mysqli_query($conn,"
SELECT COUNT(*) as total
FROM pending_orders
WHERE DATE(created_at) = '$today'
");

$row = mysqli_fetch_assoc($q);
$pending_today = $row['total'] ?? 0;


/*
|--------------------------------------------------------------------------
| SALES TREND (LAST 7 DAYS)
|--------------------------------------------------------------------------
*/

$trend_labels = [];
$trend_data = [];

for($i=6;$i>=0;$i--){

    $date = date("Y-m-d",strtotime("-$i days"));

    $q = mysqli_query($conn,"
    SELECT SUM(total) as total
    FROM orders
    WHERE payment_status='paid'
    AND order_date='$date'
    ");

    $r = mysqli_fetch_assoc($q);

    $trend_labels[] = date("M d",strtotime($date));
    $trend_data[] = $r['total'] ?? 0;
}


/*
|--------------------------------------------------------------------------
| TOP SELLING PRODUCTS
|--------------------------------------------------------------------------
*/

$product_labels = [];
$product_data = [];

$q = mysqli_query($conn,"
SELECT products.product_name,
SUM(order_items.quantity) as qty
FROM order_items
LEFT JOIN products ON order_items.product_id = products.id
LEFT JOIN orders ON order_items.order_id = orders.id
WHERE orders.payment_status='paid'
GROUP BY order_items.product_id
ORDER BY qty DESC
LIMIT 5
");

while($row=mysqli_fetch_assoc($q)){
    $product_labels[] = $row['product_name'];
    $product_data[] = $row['qty'];
}
?>

<?php include "includes/header.php"; ?>
<?php include "includes/sidebar.php"; ?>

<div class="main">

<div class="page-header">
<div class="page-title">
<h2>Sales Dashboard</h2>
</div>
</div>

<div class="dashboard-grid">

<!-- SALES OVERVIEW -->

<div class="card stat-card">

<h3>Sales Overview</h3>

<select id="salesView" class="sales-select">
<option value="daily">Daily</option>
<option value="weekly">Weekly</option>
<option value="monthly">Monthly</option>
</select>

<div class="stat-value" id="salesValue">
₱<?= number_format($daily_sales,2) ?>
</div>

</div>


<!-- LOW STOCK ALERT -->

<div class="card stat-card">

<h3>Low Stock Alert</h3>

<div style="text-align:left; margin-top:10px;">

<?php if(count($low_stock_list) > 0): ?>

<?php foreach($low_stock_list as $item): ?>

<div style="margin-bottom:8px;">
<strong><?= $item['product_name'] ?></strong><br>
<small style="color:red;">Qty: <?= $item['quantity'] ?></small>
</div>

<?php endforeach; ?>

<?php else: ?>

<div style="color:#555;">No low stock items</div>

<?php endif; ?>

</div>

</div>


<!-- PENDING ORDERS TODAY -->

<div class="card stat-card">

<h3>Pending Orders Today</h3>

<div class="stat-value">
<?= $pending_today ?>
</div>

</div>


<!-- SALES TREND -->

<div class="card">
<h3>Sales Trend</h3>
<canvas id="salesTrend"></canvas>
</div>


<!-- TOP PRODUCTS -->

<div class="card">
<h3>Top Selling Products</h3>
<canvas id="topProducts"></canvas>
</div>

</div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/* SALES VIEW SWITCHER */

const salesData = {
    daily: <?= $daily_sales ?>,
    weekly: <?= $weekly_sales ?>,
    monthly: <?= $monthly_sales ?>
};

document.getElementById('salesView').addEventListener('change', function(){

    let value = this.value;
    let amount = salesData[value];

    document.getElementById('salesValue').innerText =
        '₱' + Number(amount).toLocaleString(undefined, {minimumFractionDigits:2});

});


/* SALES TREND */

new Chart(document.getElementById('salesTrend'),{

type:'line',

data:{
labels: <?= json_encode($trend_labels) ?>,
datasets:[{
label:'Sales',
data: <?= json_encode($trend_data) ?>,
borderColor:'#8B0000',
backgroundColor:'rgba(139,0,0,0.1)',
fill:true,
tension:0.3
}]
},

options:{
responsive:true,
plugins:{legend:{display:false}}
}

});


/* TOP PRODUCTS */

new Chart(document.getElementById('topProducts'),{

type:'bar',

data:{
labels: <?= json_encode($product_labels) ?>,
datasets:[{
label:'Units Sold',
data: <?= json_encode($product_data) ?>,
backgroundColor:'#8B0000'
}]
},

options:{
responsive:true,
plugins:{legend:{display:false}}
}

});

</script>


<style>

.dashboard-grid{
display:grid;
grid-template-columns: repeat(3, 1fr);
gap:20px;
width:100%;
}

.dashboard-grid .card{
padding:20px;
background:#fff;
border:1px solid #e5e5e5;
border-radius:6px;
width:100%;
box-sizing:border-box;
}

.stat-card{
text-align:center;
}

.stat-card h3{
margin-bottom:5px;
font-weight:500;
}

.stat-value{
font-size:28px;
font-weight:bold;
color:#8B0000;
}

.sales-select{
margin-top:10px;
padding:6px;
width:100%;
border:1px solid #ccc;
border-radius:4px;
}

.dashboard-grid .card:nth-child(4){
grid-column: span 2;
}

.dashboard-grid .card:nth-child(5){
grid-column: span 1;
}

canvas{
width:100% !important;
height:320px !important;
}

@media(max-width:1200px){

.dashboard-grid{
grid-template-columns:1fr;
}

.dashboard-grid .card:nth-child(4),
.dashboard-grid .card:nth-child(5){
grid-column: span 1;
}

}

</style>

<?php include "includes/footer.php"; ?>