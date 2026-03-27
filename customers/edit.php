<?php
include "../includes/auth.php";
include "../includes/config.php";
include "../includes/header.php";
include "../includes/sidebar.php";

$id = intval($_GET['id']);

$data = mysqli_query($conn,"SELECT * FROM customers WHERE id=$id");
$row = mysqli_fetch_assoc($data);

if(isset($_POST['save'])){

$name = mysqli_real_escape_string($conn,$_POST['customer_name']);
$contact = mysqli_real_escape_string($conn,$_POST['contact']);
$address = mysqli_real_escape_string($conn,$_POST['address']);

mysqli_query($conn,"
UPDATE customers
SET
customer_name='$name',
contact='$contact',
address='$address'
WHERE id=$id
");

header("Location: index.php");
exit;

}
?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Edit Customer</h2>
</div>

</div>

<div class="card">

<form method="POST">

<div class="form-group">
<label>Customer Name</label>
<input type="text" name="customer_name" value="<?= htmlspecialchars($row['customer_name']) ?>">
</div>

<div class="form-group">
<label>Contact</label>
<input type="text" name="contact" value="<?= htmlspecialchars($row['contact']) ?>">
</div>

<div class="form-group">
<label>Address</label>
<textarea name="address"><?= htmlspecialchars($row['address']) ?></textarea>
</div>

<button type="submit" name="save" class="btn-save">Update</button>

</form>

</div>

</div>

<?php include "../includes/footer.php"; ?>