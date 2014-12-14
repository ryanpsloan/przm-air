<?php
session_start();
require("/etc/apache2/capstone-mysql/przm.php");
require_once("../php/class/transaction.php");
require_once("../php/class/user.php");
require_once("../php/class/profile.php");

if(isset($_SESSION['userId'])) {
	$mysqli = MysqliConfiguration::getMysqli();
	$profile = Profile::getProfileByUserId($mysqli, $_SESSION['userId']);
	$fullName = ucfirst($profile->__get('userFirstName')) . ' ' . ucfirst($profile->__get('userLastName'));
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
			Stripe.setPublishableKey('pk_test_y7K8SRtvByY4GmoKMeQ2qmn2');
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
						<li class="disabled"><?php echo $userName ?> </li>
						<li class="active"><?php echo $status ?></li>
						<li><a href="#"></a></li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
	</header>
		<?php
		$flightSchedule = $_SESSION['flightObj'];

		foreach($flightSchedule as $flight){
			echo <<<EOF
				<div class="displayFlt">
				<table class="flightData table">
				<tr>
					<th>Flight Number</th>
					<th>"Origin</th>
					<th>Destination</th>
					<th>Duration</th>
					<th>Departure</th>
					<th>Arrival</th>
					<th>Price</th>
				</tr>
				<tr>
					<td>$flight->flightNumber</td>
					<td>$flight->origin</td>
					<td>$flight->destination</td>
					<td>$flight->duration</td>
					<td>$flight->departureDateTime</td>
					<td>$flight->arrivalDateTime</td>
					<td>$flight->price</td>
				<tr>
			</table>
			</div>
EOF;

		}
		$amount = 250.00;
		$amountInCents = $amount * 100;
		$numberOfTickets = 2;
		$dataDescription = "2 Tickets ($" . money_format('%i', $amount) . ")";
		?>

	<div id="payment-errors"></div>

		<!-- get amount & description from the tickets created -->


		<form action="../php/processors/chargeProcessor.php" method="POST">
			<script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="pk_test_y7K8SRtvByY4GmoKMeQ2qmn2"
				data-amount= "<?php echo $amountInCents ?>"
				data-name="PRZM AIR"
				data-description="<?php echo $dataDescription ?>"
				data-image="/128x128.png">
			</script>
		</form>

	</body>
</html>