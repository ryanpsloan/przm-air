<?php
session_start();
require("/etc/apache2/capstone-mysql/przm.php");
require("../php/class/profile.php");
require("../php/class/flight.php");
require("../php/class/traveler.php");
require_once("../lib/csrf.php");




try {
	//$savedName = $_POST["csrfName"];
	//$savedToken = $_POST["csrfToken"];

/*	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled"));
	}*/
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

	}
	$prices = $_SESSION['prices'];

	if(isset($_POST['stripeToken'])) {
		$_SESSION['stripeToken'] = $_POST['stripeToken'];
		header("Location: ../php/processors/chargeProcessor.php");
	}

}catch(Exception $e){
	//$_SESSION[$savedName] = $savedToken;
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Confirmation and Purchase</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script type="text/javascript">
		Stripe.setPublishableKey('pk_test_y7K8SRtvByY4GmoKMeQ2qmn2');
	</script>


	<style>
		.displayFlt{
			width: 100%;
			border: 1px solid lightgrey;
			margin-top: 1em;
			font-size: 1.2em;

		}
		.flightData td{
			padding: .5em;
			background-color: lightblue;
		}
		table.paddedA tr td {
			padding-left: 10em;
			padding-top: .6em;
			padding-bottom: .6em;
		}

		table.paddedA tr td:first-child {
			padding-left: 0;
		}
		table.paddedB{
			margin-top: 1em;
		}
		table.paddedB tr td {
			padding-left: 14em;
			padding-top: .em;
			padding-bottom: .3em;
		}

		table.paddedB tr td:first-child {
			padding-left: 0;
		}

		#paymentBtn{
			margin-left: 32em;
		}
		#travelerDiv {
			width: 30%;
			border: 1px solid lightgray;
		}
		#paymentDiv{
			border: 1px solid lightgray;
			margin-left: 1em;
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
				<a class="navbar-brand" href="../php/processors/clearSession.php"><span class="glyphicon glyphicon-cloud"
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

<h2 class="head" style="text-align:center">Outbound Flight Itinerary - <?php echo "$".money_format("%n",
		$prices[0]);?></h2>
<?php

	$flightIds = $_SESSION['flightIds'];

	for($i =0; $i < count($flightIds); $i++){
		$flights[] = Flight::getFlightByFlightId($mysqli, $flightIds[$i]);
	}
	$outboundFlightCount = $_SESSION['outboundFlightCount'];
	foreach($flights as $flight){
		$fltNum = $flight->getFlightNumber();
		$origin = $flight->getOrigin();
		$destination = $flight->getDestination();
		$duration =  $flight->getDuration()->format("%H:%I");
		$depTime = $flight->getDepartureDateTime()->format("h:i:s a m/d/Y");
		$arrTime = $flight->getArrivalDateTime()->format("h:i:s a m/d/Y");
		if($outboundFlightCount-- === 0) {
			$money = "$".money_format("%n", $prices[1]);
			echo <<<HTML
		<h2 style="text-align:center">Inbound Flight Itinerary - $money </h2>
HTML;
		}

	echo <<<HTML
				<div class="displayFlt">
				<table class="flightData table">
				<tr>
					<th>Flight Number</th>
					<th>Origin</th>
					<th>Destination</th>
					<th>Duration</th>
					<th>Departure</th>
					<th>Arrival</th>

				</tr>
				<tr>
					<td>$fltNum</td>
					<td>$origin</td>
					<td>$destination</td>
					<td>$duration</td>
					<td>$depTime</td>
					<td>$arrTime</td>
				<tr>
			</table>
			</div>
HTML;

	}
	echo <<<HTML
	<div id='wrapper' class="container">
		<div class="row">
			<div class="col-lg-4"><h2 id="travelersHeader">Travelers</h2></div>
			<div class="col-lg-8"><h2 id="paymentHeader" class="col-lg-8">
			Transaction Details</h2></div>
		</div>
		<div class="row">
			<div id="travelerDiv" class="col-lg-4">
				<table class="paddedA">

HTML;
				$travelerIds = $_SESSION['travelerIds'];

				$i = 0;
				foreach($travelerIds as $tId) {
					$travelers[] = Traveler::getTravelerByTravelerId($mysqli, $tId);
					$name = $travelers[$i]->__get("travelerFirstName"). " " . $travelers[$i]->__get("travelerLastName");
					$name = ucwords($name);
					$price = money_format("%n", floatval($prices[0]) + floatval($prices[1]));
					echo "<tr><td>$name</td><td>$$price</td></tr>";
					$i++;
				}
				?>
				</table>
			</div>

			<div id="paymentDiv" class="col-lg-8">
				<?php
				$_SESSION['price'] = $price;
				 $numTravelers = count($travelers);
				 $newPrice = money_format("%n" ,($numTravelers * $price));

				 $salesTax = money_format("%n", (.07 * $newPrice));
				 $fees = money_format("%n", (.10 * $newPrice));
				 $totalPrice = money_format("%n", ($newPrice + $salesTax + $fees));
				 $totalInCents = $totalPrice * 100;
				 $_SESSION['totalInCents'] = $totalInCents;

				 echo <<<HTML
				 	<form id="confirmForm"  method="post">
					<table class="paddedB">
					<tr><td></td><td>Travelers($numTravelers)</td><td>$$newPrice</td></tr>
					<tr><td></td><td>Sales Tax</td><td>$$salesTax</td></tr>
					<tr><td></td><td>Fees</td><td>$$fees</td></tr>
					<tr><td></td><td>Total</td><td>$$totalPrice</td></tr>
					</table>
					<hr>
					<input type="hidden" name="total" value="$totalPrice"/>
					<div id="paymentBtn">
					<form method="POST">
						<script
							src="https://checkout.stripe.com/checkout.js" class="stripe-button"
							data-key="pk_test_y7K8SRtvByY4GmoKMeQ2qmn2"
							data-amount= $totalInCents
							data-name="PRZM AIR"
							data-description="Purchase Airfare"
							data-image="../img/cloud-icon.png">
						</script>
					</form>
					</div>
HTML;

				?>

			</div>
		</div>
	</div>



</body>
</html>
