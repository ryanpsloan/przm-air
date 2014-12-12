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
<a href="signOut.php">Sign Out</a>
EOF;
		$account = <<< EOF
<li role="presentation">
	<a href="#account" id="account-tab" role="tab" data-toggle="tab" aria-controls="account"
		aria-expanded="true">
		Account</a>
</li>
EOF;
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
	<title>Travelers</title>
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
	<style>
		.displayFlt{
			width: 90%;
			border: 2px solid lightgrey;
			margin-top: 1em;
			margin-left: 4.2em;
			font-size: 1.2em;
			border-radius: 5%;
		}
		.flightData td{
			padding: .5em;
		}
		#travelerDiv {
			width: 30%;
			border-radius: 5%;
			border: 2px solid lightgray;
		}
		#ul{
			list-style: none;
		}
		#ul li{
			margin-top: 1em;
			border: 1px solid red;
		}
		#paymentDiv{
			border-radius: 5%;
			border: 2px solid lightgray;
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

<h2 style="text-align: center">Confirm Your Flight Details</h2>
<?php
	$flightIds = $_SESSION['flightIds'];

	for($i =0; $i < count($flightIds); $i++){
		$flights[] = Flight::getFlightByFlightId($mysqli, $flightIds[$i]);
	}
	$travelerArray = $_SESSION['travelerArray'];
	foreach($flights as $flight){
		$fltNum = $flight->getFlightNumber();
		$origin = $flight->getOrigin();
		$destination = $flight->getDestination();
		$duration =  $flight->getDuration()->format("%H:%i");
		$depTime = $flight->getDepartureDateTime()->format("m/d/Y H:i:s");
		$arrTime = $flight->getArrivalDateTime()->format("m/d/Y H:i:s");
		$price = $flight->getPrice();
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
					<th>Price</th>
				</tr>
				<tr>
					<td>$fltNum</td>
					<td>$origin</td>
					<td>$destination</td>
					<td>$duration</td>
					<td>$depTime</td>
					<td>$arrTime</td>
					<td>$price</td>


				<tr>
			</table>
			</div>
HTML;

	}
	echo <<<HTML
	<div id='wrapper' class="container">
		<div class="row">
			<div class="col-lg-4"><h2 id="travelersHeader">Travelers</h2></div>
			<div class="col-lg-4"><h2 id="paymentHeader" class="col-lg-8" style="text-align: center">Transaction
			 Details</h2></div>
		</div>
		<div class="row">
			<div id="travelerDiv" class="col-lg-4">
				<ul id="ul">


HTML;

			$travelerIds = $_SESSION['travelerIds'];
			foreach($travelerIds as $tId) {
				$traveler = Traveler::getTravelerByTravelerId($mysqli, $tId);
				$name = $traveler->__get("travelerFirstName"). " " . $traveler->__get("travelerLastName");
				$name = ucwords($name);
				echo "<li>$name</li><hr>";

			}
			echo <<<HTML
				</ul>
			</div>

			<div id="paymentDiv" class="col-lg-8">
				<div id="transactionDetails" class="col-md-2">
				<?php

				?>
				</div>
				<div id="paymentDetails">
				<?php

				?>
				</div>
				<div id
			</div>
		</div>
	</div>
HTML;
?>

</body>
</html>
