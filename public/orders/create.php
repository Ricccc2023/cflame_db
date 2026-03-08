<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

$customers = mysqli_query($conn,"SELECT * FROM customers");

if($_SERVER['REQUEST_METHOD']=="POST"){

$customer = $_POST['customer'];
$date = $_POST['date'];

mysqli_query($conn,"
INSERT INTO orders (customer_id,order_date,total)
VALUES ('$customer','$date',0)
");

$order_id = mysqli_insert_id($conn);

header("Location:add_items.php?id=$order_id");
exit;
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<div class="card form-wrapper">

<h2>Create Order</h2>

<form method="POST">

<div class="form-row">
<label>Customer</label>
<select name="customer">

<?php while($c=mysqli_fetch_assoc($customers)): ?>

<option value="<?= $c['id'] ?>">
<?= $c['customer_name'] ?>
</option>

<?php endwhile; ?>

</select>
</div>

<div class="form-row">
<label>Order Date</label>
<input type="date" name="date" required>
</div>

<button class="btn-save">Next</button>

</form>

</div>
</div>

<?php include '../../includes/footer.php'; ?>