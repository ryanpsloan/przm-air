<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 12/3/14
 * Time: 10:12 AM
 */
// FIXME ADD MORE TRY CATCH THROWS? etcj
// fixme get correct format from datepicker
// fixme copy paste for return trip


require("/etc/apache2/capstone-mysql/przm.php");
require("../php/flight.php");
require("../lib/csrf.php");

try {
	session_start();
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		echo "<div class='alert alert-warning' role=
		'alert'><a href='#' class='alert-link'>Make sure cookies are enabled</a></div>";
	}

	$mysqli = MysqliConfiguration::getMysqli();

	$flightPaths = $_SESSION['flightPathsObj'];

	// clean inputs, adjust dates to needed format
	$userOrigin = filter_input(INPUT_POST,"origin", FILTER_SANITIZE_STRING);
	$userDestination = filter_input(INPUT_POST,"destination", FILTER_SANITIZE_STRING);


	$userFlyDateStartIncoming1 = filter_input(INPUT_POST,"departDate", FILTER_SANITIZE_STRING);
	$userFlyDateStartIncoming2 = $userFlyDateStartIncoming1 . " 07:00:00";
	$userFlyDateStartObj = DateTime::createFromFormat("d-m-Y H:i:s", $userFlyDateStartIncoming2);
	$userFlyDateStart = $userFlyDateStartObj->format("Y-m-d) H:i:s");
	echo $userFlyDateStart;

	// can make this a user input in future to pre-filter results to a user-given duration amount in hours.
	$userFlyDateRange = 24;

	$numberOfPassengersRequested = filter_input(INPUT_POST,"numberOfPassengers", FILTER_SANITIZE_NUMBER_INT);
	$minLayover = filter_input(INPUT_POST,"minLayover", FILTER_SANITIZE_NUMBER_INT);


	// call method
	$thisArrayOfPaths = Flight::getRoutesByUserInput($mysqli, $userOrigin, $userDestination,
			$userFlyDateStart, $userFlyDateRange,
			$numberOfPassengersRequested, $minLayover);

	// set up head of table of search results
	$outputTableHead = "<thead><tr>
										<th>Depart</th>
										<th>Arrive</th>
										<th>Flight #</th>
										<th>Stops</th>
										<th>Travel Time</th>
										<th>Layover</th>
										<th>Price</th>
								</tr></thead>\n";

	// set up variable for rows then fill in with results by looping through array of paths
	$outputTableRows = "";
	for($i=0; empty($thisArrayOfPaths[$i]) === true; $i++) {

		//get index for last flight
		$sizeOfThisPath = count($thisArrayOfPaths[$i]) - 3;


		// origin timezone conversions here
		if($userOrigin = "SEA" || $userOrigin = "LAX") {
			$originTimeZoneString = "PT";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone (new DateTimeZone("America/Los_Angeles"))->format("H:i");
		}
		if($userOrigin = "ABQ" || $userOrigin = "DEN") {
			$originTimeZoneString = "MT";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone (new DateTimeZone("America/Denver"))->format("H:i");
		}
		if($userOrigin = "DFW" || $userOrigin = "ORD" || $userOrigin = "MDW") {
			$originTimeZoneString = "CT";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone (new DateTimeZone("America/Chicago"))->format("H:i");
		}
		if($userOrigin = "JFK" || $userOrigin = "LGA" || $userOrigin = "MIA" || $userOrigin = "DET" || $userOrigin = "ATL") {
			$originTimeZoneString = "ET";
			$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->setTimezone (new DateTimeZone("America/New_York"))->format("H:i");
		}


		// destination timezone conversions here
		if($userDestination = "SEA" || $userDestination = "LAX") {
			$destinationTimeZoneString = "PT";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$sizeOfThisPath]->getArrivalDateTime()->setTimezone (new DateTimeZone("America/Los_Angeles"))->format("H:i");
		}
		if($userDestination = "ABQ" || $userDestination = "DEN") {
			$destinationTimeZoneString = "MT";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$sizeOfThisPath]->getArrivalDateTime()->setTimezone (new DateTimeZone("America/Denver"))->format("H:i");
		}
		if($userDestination = "DFW" || $userDestination = "ORD"  || $userDestination = "MDW") {
			$destinationTimeZoneString = "CT";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$sizeOfThisPath]->getArrivalDateTime()->setTimezone (new DateTimeZone("America/Chicago"))->format("H:i");
		}
		if($userDestination = "JFK" || $userDestination = "LGA" || $userDestination = "MIA" || $userDestination = "DET" || $userDestination = "ATL") {
			$destinationTimeZoneString = "ET";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$sizeOfThisPath]->getArrivalDateTime()->setTimezone (new DateTimeZone("America/New_York"))->format("H:i");
		}



		// set up array for flight number then loop through flights
		$flightNumberArray = array();

		for($j = 0; empty($thisArrayOfPaths[$i][$j + 3]) === true; $j++) {
			$flightNumberArray = $thisArrayOfPaths[$i][$j]->getFlightNumber();
		}

		// turn array to string
		$flightNumber = implode(", ",$flightNumberArray);

		// index of last flight also = number of stops to show user
		if ($sizeOfThisPath = 0) {
			$numberOfStops = "Nonstop";
		} $numberOfStops = $sizeOfThisPath;

		// get total duration from results array and change it to a string
		$totalDurationInterval = $thisArrayOfPaths[$i][$sizeOfThisPath+1];
		$travelTime = $totalDurationInterval->format("%H:%I");

		// set up array for layover then loop through results to calc
		$layoverArray = array();
		for($k = 0; empty($thisArrayOfPaths[$i][$k + 3]) === false; $k++) {
			$layoverInterval = $thisArrayOfPaths[$i][$k]->getArrivalDateTime()->
			diff($thisArrayOfPaths[$i][$k + 1]->getDepartureDateTime());
			$minutes = $layoverInterval->days * 24 * 60;
			$minutes += $layoverInterval->h * 60;
			$minutes += $layoverInterval->i;
			$layoverArray = intval($minutes);
		}

		// turn layover to string of all layovers in route
		if (empty($layoverArray) === true) {
			$layoverString = "None";
		} else {
			$layoverString = implode(", ",$layoverArray);
		}

		// get total price from results
		$totalPrice = $thisArrayOfPaths[$i][$sizeOfThisPath+2];

		// build outputs into table rows
		$outputTableRows = $outputTableRows . "<tr>" .
									"<td>" . $departureFlight1 . "</td>" .
									"<td>" . $arrivalFlightLast . "</td>" .
									"<td>" . $flightNumber . "</td>" .
									"<td>" . $numberOfStops . "</td>" .
									"<td>" . $travelTime .  "</td>" .
									"<td>" . $layoverString .  "</td>" .
									"<td>" . $totalPrice . "</td>" .
									"</tr>\n";
	}

	$outputTable = "<table>\n" . $outputTableHead . "<tbody>" . $outputTableRows . "</tbody>\n</table>\n";
	echo $outputTable;



	// copy code from above, switch origin and destination and depart flight to return -- if user select round-trip.

	if ( )










	// DateTime Math
}catch (Exception $e){
	echo "<div class='alert alert-danger' role='alert'>
  <a href='#' class='alert-link'>".$e->getMessage."</a></div>";
}
?>