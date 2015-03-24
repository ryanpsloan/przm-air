<?php
session_start();
require_once("/var/www/html/przm.php");
require_once("../php/class/traveler.php");
require_once("../php/class/profile.php");
require_once("../php/class/flight.php");
require_once("../php/class/ticket.php");
require_once("../php/class/ticketFlight.php");


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

//pull the tickets out of the session
//set it values  into variables
//set the variables into HTML to display

}
else{
	$userName = "";
	$status = "";
}

$travelerIds = $_SESSION['travelerIds'];
$tempPrices = $_SESSION['prices'];
if(count($tempPrices) > 1){
	$total = $tempPrices[0] + $tempPrices[1];
}
else{
	$total = $tempPrices[0];
}
$outboundFltCount = $_SESSION['outboundFlightCount'];
$returnFltCount = $_SESSION['returnFlightCount'];
$flightIds = $_SESSION['flightIds'];
?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Tickets</title>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

	<link type="text/css" rel="stylesheet" href="../css/displayTickets.css">
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
					<li><a href="javascript:window.print()">
							<img src="../img/printer-icon.png" alt="print this page" id="print-button" />
						</a>
					</li>
					<li></li>
					<li class="disabled"><?php echo $userName?> </li>
					<li class="active"><?php echo $status?></li>
					<li><a href="#"></a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
</header>
<div id="headDiv">
	<h3>Payment Successful! Thanks for flying with us!</h3>
</div>
<div id="displayArea" class="col-lg-12">
<?php

	foreach($flightIds as $flightId) {
		$flights[] = Flight::getFlightByFlightId($mysqli, $flightId);
	}

//^this gives me the flight data for each flight on the ticket
	foreach($travelerIds as $travelerId) {

		$traveler = Traveler::getTravelerByTravelerId($mysqli, $travelerId);
		$ticket = Ticket::getTicketByTravelerId($mysqli, $traveler->__get("travelerId"));

		//^this gives me each tickets flights and travelers


		$travelerName = $traveler->__get("travelerFirstName") . " " . $traveler->__get("travelerLastName");
		$travelerName = ucwords($travelerName);
		$price = $ticket->getPrice();
		$price = money_format("%n", $price);
		$confNum = $ticket->getConfirmationNumber();
		$confNum = strtoupper($confNum);
		$today = new DateTime();
		$today = $today->format("m/d/Y");
		$status = $ticket->getStatus();
		echo <<<HTML
			<div class="ticket">
				<h1 style="text-align: center"><img id="cloud" src="../img/cloud-icon.png">PRZM AIR</h1>

			<hr>
			<h2 style="text-align:center">Ticket</h2>
			<table>
			<tbody>
			<tr>
				<td colspan="14">Traveler: <span style="font-size: 1.5em"><strong>$travelerName</strong></span></td>
				<td>
					<ul>
						<li>Date of Purchase</li>
						<li>$today</li>

					</ul>
				</td>
			</tr>

		<tr>
			<td colspan="14">
				Status: $status
			</td>
			<td colspan="2">
				<ul>
					<li>Ticket Price</li>
					 <li>$$price</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td colspan="16">
				Confirmation Number: <b>$confNum</b>
			</td>
		</tr>
		<tr><td colspan="16"><b>Departure Details</b><hr></td></tr>
HTML;
		foreach($flights as $flight) {
			$fltNum = $flight->getFlightNumber();
			$origin = $flight->getOrigin();
			$destination = $flight->getDestination();
			$duration = $flight->getDuration()->format("%H:%I");
			$depTime = $flight->getDepartureDateTime()->format("h:i:s a");
			$depDate = $flight->getDepartureDateTime()->format("m/d/Y");
			$arrTime = $flight->getArrivalDateTime()->format("h:i:s a");
			$arrDate = $flight->getArrivalDateTime()->format("m/d/Y");
			if($outboundFltCount-- === 0 && $returnFltCount > 0) {
				echo "<tr><td colspan='16'><b>Return Details</b><hr></td></tr>";
			}
			echo <<<HTML

			<tr>
			<td>
				<ul>
				<li>Origin: $origin</li>
				<li>Destination: $destination</li>
				</ul>
			</td>
			<td colspan="10">
				<ul>
					<li>Depart: $depTime</li>
					<li>Arrive: $arrTime</li>
				</ul>
			</td>
			<td>
				Flight # $fltNum
			</td>
			<td colspan="5">
				<ul>
					<li>$depDate</li>
					<li>Duration</li>
					<li>$duration</li>
				</ul>
			</td>
		</tr>
HTML;
		}

		$outboundFltCount = $_SESSION['outboundFlightCount'];
		echo "</tbody>
			</table>
			</div>";
	}
?>

</div>
</body>
</html>