<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

if(isset($_POST['submit'])){

$customer = $_POST['customer_name'];
$contact = $_POST['contact'];
$address = $_POST['address'];

mysqli_query($conn,"
INSERT INTO pending_orders(customer_name,contact,address)
VALUES('$customer','$contact','$address')
");

$pending_id = mysqli_insert_id($conn);

$product = $_POST['product_id'];
$qty = $_POST['qty'];
$price = $_POST['price'];

for($i=0;$i<count($product);$i++){

$pid = $product[$i];
$q = $qty[$i];
$p = $price[$i];
$subtotal = $q*$p;

mysqli_query($conn,"
INSERT INTO pending_order_items
(pending_order_id,product_id,quantity,price,subtotal)
VALUES($pending_id,$pid,$q,$p,$subtotal)
");

}

header("Location:index.php");
exit;

}

$products = mysqli_query($conn,"SELECT * FROM products ORDER BY product_name ASC");
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<div class="main">

<h2>Create Pending Order</h2>

<div class="card">

<form method="POST">

<label>Customer Name</label>
<input type="text" name="customer_name" required>

<label>Contact</label>
<input type="text" name="contact">

<label>Address</label>
<textarea name="address"></textarea>

<br><br>

<table id="orderTable">

<thead>
<tr>
<th>Product</th>
<th>Price</th>
<th>Qty</th>
<th>Subtotal</th>
<th></th>
</tr>
</thead>

<tbody id="items">

<tr>

<td>

<select name="product_id[]" class="product">

<option value="">Select Product</option>

<?php while($p=mysqli_fetch_assoc($products)): ?>

<option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>">
<?= $p['product_name'] ?>
</option>

<?php endwhile; ?>

</select>

</td>

<td>
<input type="text" name="price[]" class="price" readonly>
</td>

<td>
<input type="number" name="qty[]" class="qty" value="1">
</td>

<td>
<input type="text" class="subtotal" readonly>
</td>

<td>
<button type="button" onclick="removeRow(this)">X</button>
</td>

</tr>

</tbody>

</table>

<br>

<button type="button" onclick="addRow()">+ Add Product</button>

<h3>Total: ₱ <span id="total">0</span></h3>

<br>

<button type="submit" name="submit" class="btn-add">
Save Pending Order
</button>

</form>

</div>

</div>

<script>

function addRow(){

let table=document.getElementById("items");

let row=table.rows[0].cloneNode(true);

row.querySelectorAll("input").forEach(input=>input.value="");

table.appendChild(row);

}

function removeRow(btn){

let rows=document.querySelectorAll("#items tr");

if(rows.length>1){

btn.closest("tr").remove();

calculateTotal();

}

}

document.addEventListener("change",function(e){

if(e.target.classList.contains("product")){

let row=e.target.closest("tr");

let price=e.target.selectedOptions[0].dataset.price || 0;

row.querySelector(".price").value=price;

calculateRow(row);

}

});

document.addEventListener("input",function(e){

if(e.target.classList.contains("qty")){

calculateRow(e.target.closest("tr"));

}

});

function calculateRow(row){

let price=row.querySelector(".price").value || 0;

let qty=row.querySelector(".qty").value || 0;

let subtotal=price*qty;

row.querySelector(".subtotal").value=subtotal;

calculateTotal();

}

function calculateTotal(){

let total=0;

document.querySelectorAll(".subtotal").forEach(s=>{

total+=Number(s.value);

});

document.getElementById("total").innerText=total;

}

</script>

<?php include '../../includes/footer.php'; ?>