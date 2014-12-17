<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("../php/class/flight.php");
require_once("../php/class/profile.php");
require_once("../php/class/ticket.php");
require_once("../php/class/ticketFlight.php");
try {
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
	function array_sort($array, $on, $order=SORT_ASC)
	{
		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
					break;
				case SORT_DESC:
					arsort($sortable_array);
					break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}

		return $new_array;
	}
	if(isset($_POST['confirmationNumber'])) {
		$flights = array();
		$confirmationNumber = $_POST['confirmationNumber'];
		$query = "SELECT ticketId FROM ticket WHERE confirmationNumber = ?";
		$statement = $mysqli->prepare($query);
		$statement->bind_param("s", $confirmationNumber);
		$statement->execute();
		$result = $statement->get_result();
		$row = $result->fetch_assoc();
		$ticketId = $row['ticketId'];

		$ticket = Ticket::getTicketByTicketId($mysqli, $ticketId);

		$ticketFlights = TicketFlight::getTicketFlightByTicketId($mysqli, $ticket->getTicketId());

		foreach($ticketFlights as $ticketFlight) {
			$flightIds[] = $ticketFlight->getFlightId();
		}

		$i = 0;
		foreach($flightIds as $flightId) {
			$flights[$i] = Flight::getFlightByFlightId($mysqli, $flightId);
			$tempDT = $flights[$i]['departureDate']->format("Y-m-d H:i:s");
			$dateTime[] = array('departureDate'=> $tempDT,
									  'flightId'=>	$flights[$i]->getFlightId());
			$i++;
		}
		var_dump($dateTime);

		$sortedArray = array_sort($dateTime,'departureDate');
		var_dump($dateTime);
		if(isset($_SESSION['outboundFlightCount'])) {
			$outboundFlightCount = $_SESSION['outboundFlightCount'];
		}
		$input = <<<HTML
	<div id="formDiv">
		<form id="viewItineraryForm" method="post"  action="viewItinerary.php">
			<p><label for="confirmationNumber">Enter 6 Digit Ticket Confirmation Number to View Itinerary<br>
			<input type="text" name="confirmationNumber"></label></p>
			<p><input type="submit" value="Get Itinerary"></p>
		</form>
	</div>
HTML;


		echo <<<HTML
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Itinerary</title>
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


	<link type="text/css" rel="stylesheet" href="../css/viewItinerary.css">

	<script type="text/javascript" src="../js/selectTravelers.js"></script>


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
					<li class="disabled"> </li>
					<li class="active"></li>
					<li><a href="#"></a></li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
</header>
<!-- Display Flights -->
HTML;
		echo <<<HTML
<main>
<div class="container-fluid">
	$input
HTML;
	$today = new DateTime('now');
	$today = $today->format("h:i:s a m/d/y");
	if(isset($_SESSION['outboundFlightCount'])) {
		$outboundFlightCount = $_SESSION['outboundFlightCount'];
	}
	echo <<<HTML
<div>
	<table>
		<tr>
			<td>
				<ul>

					<li>Today's Date: $today</li>

				</ul>
			</td>
		</tr>
		<tr><th colspan="16"><h3>Your Itinerary</h3></th></tr>




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
		if(isset($_SESSION['outboundFlightCount'])) {
			if(--$outboundFlightCount === 0) {
				echo "<tr><td colspan='16'><b>Return Details</b><hr></td></tr>";
			}
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
	echo "</table>
</div>
</div>
</main>";
	}
	else{
		echo <<<HTML
	<div id="formDiv">
		<form id="viewItineraryForm" method="post"  action="viewItinerary.php">
			<p><label for="confirmationNumber">Enter 6 Digit Ticket Confirmation Number to View Itinerary<br>
			<input type="text" name="confirmationNumber"></label></p>
			<p><input type="submit" value="Get Itinerary"></p>
		</form>
	</div>
HTML;

	}
}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
?>
</body>
</html>

