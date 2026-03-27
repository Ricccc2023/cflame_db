<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| GENERATE CSRF TOKEN
|--------------------------------------------------------------------------
*/

if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/*
|--------------------------------------------------------------------------
| HANDLE PAYMENT STATUS UPDATE (SECURE)
|--------------------------------------------------------------------------
*/

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_paid'])){

    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
        die("Invalid CSRF Token");
    }

    $order_id = intval($_POST['order_id']);

    $stmt = $conn->prepare("SELECT payment_status FROM orders WHERE id=?");
    $stmt->bind_param("i",$order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if($row && $row['payment_status'] === 'unpaid'){

        $update = $conn->prepare("UPDATE orders SET payment_status='paid' WHERE id=?");
        $update->bind_param("i",$order_id);
        $update->execute();

    }

    header("Location: index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

$customer = $_GET['customer'] ?? '';
$date = $_GET['date'] ?? '';

$sql = "
SELECT orders.*, customers.customer_name
FROM orders
LEFT JOIN customers ON orders.customer_id = customers.id
WHERE 1=1
";

if($customer != ""){
    $sql .= " AND customers.customer_name LIKE '%$customer%'";
}

if($date != ""){
    $sql .= " AND orders.order_date = '$date'";
}

$sql .= " ORDER BY orders.id DESC";

$result = mysqli_query($conn,$sql);
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Order Management</h2>
</div>

<div class="page-action">
<a href="create.php" class="btn-add">+ Create Order</a>
</div>

</div>

<div class="card">

<form method="GET" class="filter-bar">

<input type="text" name="customer" placeholder="Customer Name" value="<?= htmlspecialchars($customer) ?>">

<input type="date" name="date" value="<?= htmlspecialchars($date) ?>">

<button class="btn-search">Filter</button>

</form>

<div class="table-wrap">

<table>

<thead>
<tr>
<th>ID</th>
<th>Invoice</th>
<th>Customer</th>
<th>Date</th>
<th>Total</th>
<th>Payment</th>
<th>Receipt</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id'] ?></td>

<td>
<strong><?= htmlspecialchars($row['invoice_no']) ?></strong>
</td>

<td><?= htmlspecialchars($row['customer_name']) ?></td>

<td><?= $row['order_date'] ?></td>

<td>₱<?= number_format($row['total'],2) ?></td>

<td>

<form method="POST" style="display:inline;">

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<input type="hidden" name="order_id" value="<?= $row['id'] ?>">
<input type="hidden" name="toggle_payment" value="1">

<?php if($row['payment_status'] === 'paid'): ?>

<button
class="action-btn action-success"
style="padding:4px 10px;font-size:12px;cursor:not-allowed;opacity:0.8;"
disabled>
Paid
</button>

<?php else: ?>

<form method="POST" style="display:inline;">

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<input type="hidden" name="order_id" value="<?= $row['id'] ?>">
<input type="hidden" name="mark_paid" value="1">

<button
type="submit"
class="action-btn action-secondary"
style="padding:4px 10px;font-size:12px;">
Unpaid
</button>

</form>

<?php endif; ?>

</form>

</td>

<td>

<?php if(!empty($row['receipt_image'])): ?>

<a
class="action-btn action-secondary"
style="padding:4px 10px;font-size:12px;"
href="uploads/<?php echo $row['receipt_image']; ?>" target="_blank">
View
</a>

<?php else: ?>

<span style="color:#999;font-size:12px;">None</span>

<?php endif; ?>

</td>

<td>

<div class="actions">

<a class="action-btn action-success"
href="view.php?id=<?= $row['id'] ?>">
View
</a>

<a class="action-btn action-secondary"
href="print.php?id=<?= $row['id'] ?>" target="_blank">
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

<?php include '../includes/footer.php'; ?>