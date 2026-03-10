<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

if(isset($_POST['submit'])){

$customer = $_POST['customer_name'];
$contact = $_POST['contact'];
$address = $_POST['address'];
$payment = $_POST['mode_of_payment'];

mysqli_query($conn,"
INSERT INTO pending_orders(customer_name,contact,address,mode_of_payment)
VALUES('$customer','$contact','$address','$payment')
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

<div class="page-header">

<div class="page-title">
<h2>Create Pending Order</h2>
</div>

<div class="page-action">
<a href="index.php" class="btn-decline">Back</a>
</div>

</div>

<div class="card">

<form method="POST">

<label>Customer Name</label>
<input type="text" name="customer_name" required>

<label>Contact</label>
<input type="text" name="contact">

<label>Address</label>
<textarea name="address"></textarea>

<br><br>

<label>Mode of Payment</label>

<select name="mode_of_payment" required>

<option value="">Select Payment</option>
<option value="GCash">GCash</option>
<option value="Cash on Delivery">Cash on Delivery</option>

</select>

<br><br>

<label>Select Product</label>

<select id="productSelect">

<option value="">Select Product</option>

<?php while($p=mysqli_fetch_assoc($products)): ?>

<option value="<?= $p['id'] ?>"
data-name="<?= $p['product_name'] ?>"
data-price="<?= $p['price'] ?>">

<?= $p['product_name'] ?> - ₱<?= number_format($p['price'],2) ?>

</option>

<?php endwhile; ?>

</select>

<br><br>

<h3>Selected Products</h3>

<table id="cart">

<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Subtotal</th>
<th></th>
</tr>

</table>

<h3>Total: ₱ <span id="total">0</span></h3>

<br>

<button type="submit" name="submit" class="btn-add">
Save Pending Order
</button>

</form>

</div>

</div>

<script>

let cartProducts = [];

document.getElementById("productSelect").addEventListener("change",function(){

let option=this.selectedOptions[0];

let id=option.value;
let name=option.dataset.name;
let price=option.dataset.price;

if(!id) return;

if(cartProducts.includes(id)){

alert("Product already selected");
return;

}

cartProducts.push(id);

let table=document.getElementById("cart");

let row=table.insertRow();

row.innerHTML=`

<td>

${name}

<input type="hidden" name="product_id[]" value="${id}">

</td>

<td>

<input type="number" name="qty[]" value="1" min="1" class="qty">

</td>

<td>

<input type="text" name="price[]" value="${price}" readonly>

</td>

<td class="subtotal">${price}</td>

<td>

<button type="button" onclick="removeItem(this,'${id}')">X</button>

</td>

`;

calculate();

});

document.addEventListener("input",function(e){

if(e.target.classList.contains("qty")){

calculate();

}

});

function calculate(){

let rows=document.querySelectorAll("#cart tr");

let total=0;

rows.forEach((row,i)=>{

if(i==0) return;

let qty=row.querySelector(".qty").value;
let price=row.querySelector("input[name='price[]']").value;

let sub=qty*price;

row.querySelector(".subtotal").innerText=sub;

total+=Number(sub);

});

document.getElementById("total").innerText=total;

}

function removeItem(btn,id){

cartProducts=cartProducts.filter(p=>p!=id);

btn.closest("tr").remove();

calculate();

}

</script>

<?php include '../../includes/footer.php'; ?>