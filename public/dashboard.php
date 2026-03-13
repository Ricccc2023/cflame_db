<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";

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

<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>

<div class="main">

<div class="page-header">
<div class="page-title">
<h2>Sales Dashboard</h2>
</div>
</div>

<div class="dashboard-grid">

<!-- DAILY -->

<div class="card stat-card">
<h3>Daily Sales</h3>
<div class="stat-value">
₱<?= number_format($daily_sales,2) ?>
</div>
</div>

<!-- WEEKLY -->

<div class="card stat-card">
<h3>Weekly Sales</h3>
<div class="stat-value">
₱<?= number_format($weekly_sales,2) ?>
</div>
</div>

<!-- MONTHLY -->

<div class="card stat-card">
<h3>Monthly Sales</h3>
<div class="stat-value">
₱<?= number_format($monthly_sales,2) ?>
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
margin-top:20px;
width:100%;
}

/* CARD DESIGN SAME AS INVENTORY */

.dashboard-grid .card{
padding:20px;
background:#fff;
border:1px solid #e5e5e5;
border-radius:6px;
width:100%;
box-sizing:border-box;
}

/* SALES STAT */

.stat-card{
text-align:center;
}

.stat-card h3{
margin-bottom:10px;
font-weight:500;
}

.stat-value{
font-size:28px;
font-weight:bold;
color:#8B0000;
}

/* GRAPH AREA */

.dashboard-grid .card:nth-child(4){
grid-column: span 2;
}

.dashboard-grid .card:nth-child(5){
grid-column: span 1;
}

/* CHART SIZE */

canvas{
width:100% !important;
height:320px !important;
}

/* RESPONSIVE */

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

<?php include "../includes/footer.php"; ?>