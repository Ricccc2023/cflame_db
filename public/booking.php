<?php
require_once "../includes/config.php";

if(isset($_POST['submit'])){

$customer=$_POST['customer_name'];
$contact=$_POST['contact'];
$address=$_POST['address'];
$payment=$_POST['mode_of_payment'];

mysqli_query($conn,"
INSERT INTO pending_orders
(customer_name,contact,address,mode_of_payment)
VALUES
('$customer','$contact','$address','$payment')
");

$pending_id=mysqli_insert_id($conn);

$product=$_POST['product_id'];
$qty=$_POST['qty'];
$price=$_POST['price'];

for($i=0;$i<count($product);$i++){

$pid=$product[$i];
$q=$qty[$i];
$p=$price[$i];

$subtotal=$q*$p;

mysqli_query($conn,"
INSERT INTO pending_order_items
(pending_order_id,product_id,quantity,price,subtotal)
VALUES
($pending_id,$pid,$q,$p,$subtotal)
");

}

$success=true;

}

$products=mysqli_query($conn,"SELECT * FROM products ORDER BY product_name ASC");

include "../includes/header.php";
?>

<div class="main">

<div style="display:flex;justify-content:center;margin-top:50px;gap:30px;">

<!-- ORDER CARD -->

<div class="card booking-card">

<div class="form-header">
<h2>Order Products</h2>
</div>

<?php if(isset($success)): ?>

<div class="error-box" style="background:#e9ffe9;border-color:#2ecc71;">
Order submitted successfully. Please wait for confirmation.
</div>

<?php endif; ?>

<form method="POST">

<div class="form-row">
<label>Customer Name</label>
<input type="text" name="customer_name" required>
</div>

<div class="form-row">
<label>Contact</label>
<input type="text" name="contact">
</div>

<div class="form-row">
<label>Address</label>
<input type="text" name="address">
</div>

<div class="form-row">
<label>Mode of Payment</label>

<select name="mode_of_payment" required>

<option value="">Select Payment</option>
<option value="GCash">GCash</option>
<option value="Cash on Delivery">Cash on Delivery</option>

</select>

</div>

<div class="form-row">
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

</div>

<br>

<table id="cart">

<tr>
<th>Product</th>
<th>Qty</th>
<th>Price</th>
<th>Subtotal</th>
<th></th>
</tr>

</table>

<br>

<h3>Total: ₱ <span id="total">0</span></h3>

<br>

<button class="btn-save" name="submit">
Submit Order
</button>

</form>

</div>


<!-- INSTRUCTIONS -->

<div class="card" style="width:320px;height:fit-content;">

<h3>How to Order</h3>

<ol style="margin-top:10px;padding-left:18px;font-size:14px;line-height:1.6;">

<li>Enter your name and contact information.</li>

<li>Select your preferred mode of payment.</li>

<li>Choose products from the dropdown.</li>

<li>Adjust quantity if needed.</li>

<li>Click <b>Submit Order</b>.</li>

<li>Wait for admin confirmation.</li>
<ul>Note:</ul>
<UL style="list-style-type:square;">

        <li>THE CONTACT NUMBER YOU ENTERED WILL BE INDICATED IF YOUR ORDER IS CONFIRMED OR DECLINED.</li>


</ul>
</ol>

</div>

</div>

</div>

<script>

let cartProducts=[];

document.getElementById("productSelect").addEventListener("change",function(){

let option=this.selectedOptions[0];

let id=option.value;
let name=option.dataset.name;
let price=option.dataset.price;

if(!id) return;

if(cartProducts.includes(id)){
alert("Product already added");
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