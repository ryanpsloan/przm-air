<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("../php/class/profile.php");
require_once("../lib/csrf.php");

if(isset($_SESSION['userId'])) {
	$mysqli = MysqliConfiguration::getMysqli();
	$profile = Profile::getProfileByUserId($mysqli,$_SESSION['userId']);
	$fullName =  ucfirst($profile->__get('userFirstName')).' '.ucfirst($profile->__get('userLastName'));
	$userName = <<<EOF
<a><span	class="glyphicon glyphicon-user"></span> Welcome, $fullName  </a>
EOF;
	$status = <<< EOF
<a href="../php/processors/signOut.php">Sign Out</a>
EOF;
	$account = <<< EOF
<li role="presentation">
	<a href="#account" id="account-tab" role="tab" data-toggle="tab" aria-controls="account"
		aria-expanded="true">
		Account</a>
</li>
EOF;
}
$_SESSION['totalPrice'] = $_POST['total'];
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
			height: 60em;
			width: 35em;
			top: 11%;
			left: 30%;
			padding: 2em;
			border: 1px solid lightgrey;
		}
		#outputArea{
			margin-top: 1.5em;
		}
		#totalDiv{
			border: 1px solid lightgrey;
			height: 15em;
			width: 15em;
			position: absolute;
			top: 5%;
			right: 5%;
			padding: 2em;
		}

	</style>
</head>
	<body>
	<header>
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
						<li></li>
					</ul>

					<ul class="nav navbar-nav navbar-right">
						<li class="disabled"><?php echo $userName?> </li>
						<li class="active"><?php echo $status?></li>
						<li><a href="#"></a></li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
	</header>
	<div class="col-lg-12">
		<div class="row">
			<div id="totalDiv">
				<p>Total: </p>
				<p><?php echo "$".$_POST['total'] ?></p>
			</div>
		</div>
	</div>
	<div id="payment-errors"></div>

	<div id="formDiv">
		<h3>Enter Payment Information</h3>
	<fieldset>
		<form id="paymentForm" method="post" action="paymentProcessor.php">
			<label for="firstName">First Name</label><br/>
			<input type="text" id="firstName" name="firstName" value="<?php echo ucwords($profile->__get("userFirstName"));
			?>"/><br/>
			<label for="middleName">Middle Name</label><br/>
			<input type="text" id="middleName" name="middleName" value="<?php echo ucwords($profile->__get
			("userMiddleName"));
			?>" /><br/>
			<label for="lastName">Last Name</label><br/>
			<input type="text" id="lastName" name="lastName" value="<?php echo ucwords($profile->__get("userLastName"));
			?>" /><br />
			<label for="addressLine1">Address Line 1</label><br/>
			<input type="text" id="addressLine1" name="addressLine1" /><br />
			<label for="addressLine2">Address Line 2</label><br/>
			<input type="text" id="addressLine2" name="addressLine2" /><br />
			<label for="addressCity">City</label><br/>
			<input type="text" id="addressCity" name="addressCity" /><br />
			<label for="addressState">State</label><br/>
			<input type="text" id="addressState" name="addressState" /><br />
			<label for="addressZip">Zip Code</label><br/>
			<input type="text" id="addressZip" name="addressZip" /><br />
			<hr>
			<label for="cardNumber">Card Number</label><br/>
			<input type="text" id="cardNumber" size="20" autocomplete="off" data-stripe="number"><br />
			<span>Enter the number without spaces or hyphens.</span><br/>
			<label for="cardCvc">CVC</label><br/>
			<input type="text" id="cardCvc" size="4" autocomplete="off" data-stripe="cvc"><br />
			<label>Expiration (MM/YYYY)</label><br/>
			<input type="text" id="cardExpiryMonth" size="2" data-stripe="exp-month">
			<span> / </span>
			<input type="text" id="cardExpiryYear" size="4" data-stripe="exp-year"><br />
			<br/>
			<hr>
			<button type="submit" id="submitBtn">Submit</button>
		</form>
	</fieldset>
	<p id="outputArea"></p>
	</body>
</html>