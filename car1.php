<!DOCTYPE html>
<html>
<head>

<style>
*{
	margin: 0;
	padding: 0;
	font-family: system-ui;
}
body{
	cursor: default;
}
.container{
	margin-top: 45px;
	padding: 15px 0 15px 30px;
	height: 50%;
	width: 40%;
	border: 3px solid #0ef;
	margin-left: 75px;
}
</style>
</head>
<body>

<div class="container">
	<div class="forms">
		<form method="post">
		<div class="usrn">
			<label for="username">Username</label>
			<input type="text" value="" id="username" name="username">
		</div>
		<div class="sel">
			<label for="payment">Payment Method</label>
			<select name="payment" id="payment" style="cursor: pointer">
				<option>OnlinePayment/CreditCard/Gcash</option>
				<option>Cash On Hand</option>  
			</select>
		</form>
	</div>
	<div class="image">
		<img src="static/car1.png">
	</div>
</div>

</body>
</html>