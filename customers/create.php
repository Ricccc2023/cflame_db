<?php
include "../../includes/auth.php";
include "../../includes/config.php";
include "../../includes/header.php";
include "../../includes/sidebar.php";

if(isset($_POST['save'])){

$name = mysqli_real_escape_string($conn,$_POST['customer_name']);
$contact = mysqli_real_escape_string($conn,$_POST['contact']);
$address = mysqli_real_escape_string($conn,$_POST['address']);

$check = mysqli_query($conn,"
SELECT id FROM customers WHERE customer_name='$name'
");

if(mysqli_num_rows($check)>0){

echo "<script>alert('Customer already exists');</script>";

}else{

mysqli_query($conn,"
INSERT INTO customers (customer_name,contact,address)
VALUES ('$name','$contact','$address')
");

header("Location: index.php");
exit;

}

}
?>

<div class="main">

<div class="page-header">

<div class="page-title">
<h2>Create Customer</h2>
</div>

</div>

<div class="card">

<form method="POST">

<div class="form-group">
<label>Customer Name</label>
<input type="text" name="customer_name" required>
</div>

<div class="form-group">
<label>Contact</label>
<input type="text" name="contact">
</div>

<div class="form-group">
<label>Address</label>
<textarea name="address"></textarea>
</div>

<button type="submit" name="save" class="btn-save">Save</button>

</form>

</div>

</div>

<?php include "../../includes/footer.php"; ?>