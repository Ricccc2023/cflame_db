<?php
require_once "../includes/config.php";
require_once "../includes/auth.php";

date_default_timezone_set("Asia/Manila");

/* ===============================
DATE RANGE (LAST 7 DAYS)
=============================== */

$today = date("Y-m-d");
$week_start = date("Y-m-d", strtotime("-6 days"));

/* ===============================
STATS
=============================== */

/* TOTAL SALES */
$sales = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total) as total 
FROM orders 
WHERE payment_status='paid'
AND order_date BETWEEN '$week_start' AND '$today'
"));

$total_sales = $sales['total'] ?? 0;

/* TOTAL ORDERS */
$orders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as count FROM orders
WHERE order_date BETWEEN '$week_start' AND '$today'
"));

$total_orders = $orders['count'];

/* PENDING */
$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as count FROM pending_orders
"));

$total_pending = $pending['count'];

/* LOW STOCK */
$low = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as count FROM products WHERE quantity <= 5
"));

$low_stock = $low['count'];

/* ===============================
SALES TREND
=============================== */

$labels = [];
$data = [];

for($i=6;$i>=0;$i--){

    $date = date("Y-m-d", strtotime("-$i days"));

    $q = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM(total) as total 
    FROM orders 
    WHERE payment_status='paid'
    AND order_date='$date'
    "));

    $labels[] = date("M d", strtotime($date));
    $data[] = $q['total'] ?? 0;
}

/* ===============================
TOP PRODUCTS
=============================== */

$p_labels = [];
$p_data = [];

$top = mysqli_query($conn,"
SELECT 
    products.product_name, 
    SUM(order_items.quantity) as qty
FROM order_items
LEFT JOIN products ON products.id = order_items.product_id
GROUP BY order_items.product_id
ORDER BY qty DESC
LIMIT 5
");

while($r=mysqli_fetch_assoc($top)){
    $p_labels[] = $r['product_name'];
    $p_data[] = $r['qty'];
}
?>

<?php include "../includes/header.php"; ?>
<?php include "../includes/sidebar.php"; ?>

<div class="main">

<div class="page-header">
<h2>Reports Overview</h2>
</div>

<!-- TOP CARDS -->
<div class="dashboard-grid">

<div class="card stat-card">
<h3>Total Sales (7 days)</h3>
<div class="stat-value">₱<?= number_format($total_sales,2) ?></div>
</div>

<div class="card stat-card">
<h3>Total Orders</h3>
<div class="stat-value"><?= $total_orders ?></div>
</div>

<div class="card stat-card">
<h3>Pending Orders</h3>
<div class="stat-value"><?= $total_pending ?></div>
</div>

<div class="card stat-card">
<h3>Low Stocks</h3>
<div class="stat-value"><?= $low_stock ?></div>
</div>

<!-- SALES TREND -->
<div class="card">
<h3>Sales Trend</h3>
<canvas id="salesChart"></canvas>
</div>

<!-- TOP PRODUCTS -->
<div class="card">
<h3>Top Products</h3>
<canvas id="productChart"></canvas>
</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/* SALES */

new Chart(document.getElementById('salesChart'),{
type:'line',
data:{
labels: <?= json_encode($labels) ?>,
datasets:[{
label:'Sales',
data: <?= json_encode($data) ?>,
borderColor:'#8B0000',
backgroundColor:'rgba(139,0,0,0.1)',
fill:true
}]
},
options:{plugins:{legend:{display:false}}}
});

/* PRODUCTS */

new Chart(document.getElementById('productChart'),{
type:'doughnut',
data:{
labels: <?= json_encode($p_labels) ?>,
datasets:[{
data: <?= json_encode($p_data) ?>
}]
}
});

</script>

<style>

/* ===============================
REPORTS CLEAN & COMPACT CSS
=============================== */

/* ===============================
BALANCED REPORTS CSS (FULL HEIGHT)
=============================== */

.dashboard-grid{
display:grid;
grid-template-columns: repeat(4, 1fr);
gap:16px;
margin-top:10px;
min-height: calc(100vh - 180px); /* sakto hanggang footer */
}

/* CARD */

.card {
  background-color: #ffffff; /* white card */
  border-radius: 12px;
  padding: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border: 1px solid #e5e7eb; /* subtle border */
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
}

/* STAT CARDS */

.stat-card{
text-align:center;
padding:14px;
}

.stat-card h3{
font-size:14px;
margin-bottom:6px;
font-weight:500;
color:#ccc;
}

.stat-value{
font-size:20px;
font-weight:bold;
color:#ff4d4d;
}

/* GRAPH LAYOUT */

.dashboard-grid .card:nth-child(5){
grid-column: span 2;
min-height:300px;
}

.dashboard-grid .card:nth-child(6){
grid-column: span 2;
min-height:300px;
}

/* CANVAS */

canvas{
width:100% !important;
height:260px !important;
}

/* RESPONSIVE */

@media(max-width:1200px){

.dashboard-grid{
grid-template-columns:1fr;
min-height:auto;
}

.dashboard-grid .card:nth-child(5),
.dashboard-grid .card:nth-child(6){
grid-column: span 1;
}

canvas{
height:220px !important;
}

}

</style>

<?php include "../includes/footer.php"; ?>