<?php
session_start();
require_once("../lib/csrf.php");
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Payment</title>
	<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script type="text/javascript">
		Stripe.setPublishableKey('lksdajSDFmn2345nv');
	</script>
	<script type="text/javascript" src="../js/payment.js"></script>
	<style>
		#formDiv{
			position: absolute;
			height: 30em;
			width: 30em;
			top: 20%;
			left: 35%;
			padding: 2em;
			border: 1px solid lightgrey;
		}
		#outputArea{
			margin-top: 1.5em;
		}
	</style>
</head>
	<body>
	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="../index.php"><span class="glyphicon glyphicon-cloud"
																				  aria-hidden="true"></span> PRZM AIR</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li>
						<a href="#"></a>
					</li>

				</ul>

				<ul class="nav navbar-nav navbar-right">
					<li><a href="#"></a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>

	<div id="payment-errors"></div>

	<div id="formDiv">
	<fieldset>
		<form id="paymentForm" method="post" action="paymentProcessor.php">
			<label for="firstName">First Name</label>
			<input type="text" id="firstName" name="firstName" /><br />
			<label for="middleName">Middle Name</label>
			<input type="text" id="middleName" name="middleName" /><br />
			<label for="lastName">Last Name</label>
			<input type="text" id="lastName" name="lastName" /><br />
			<label for="addressLine1">Address Line 1</label>
			<input type="text" id="addressLine1" name="addressLine1" /><br />
			<label for="addressLine2">Address Line 2</label>
			<input type="text" id="addressLine2" name="addressLine2" /><br />
			<label for="addressCity">City</label>
			<input type="text" id="addressCity" name="addressCity" /><br />
			<label for="addressState">State</label>
			<input type="text" id="addressState" name="addressState" /><br />
			<label for="addressZip">Zip Code</label>
			<input type="text" id="addressZip" name="addressZip" /><br />
			<label for="cardNumber">Card Number</label>
			<input type="text" id="cardNumber" size="20" autocomplete="off" data-stripe="number"><br />
			<span>Enter the number without spaces or hyphens.</span>
			<label for="cardCvc">CVC</label>
			<input type="text" id="cardCvc" size="4" autocomplete="off" data-stripe="cvc"><br />
			<label>Expiration (MM/YYYY)</label>
			<input type="text" id="cardExpiryMonth" size="2" data-stripe="exp-month">
			<span> / </span>
			<input type="text" id="cardExpiryYear" size="4" data-stripe="exp-year"><br />
			<button type="submit" id="submitBtn">Submit</button>
		</form>
	</fieldset>
	<p id="outputArea"></p>
	</body>
</html>