<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 12/3/14
 * Time: 10:12 AM
 */
// fixme get correct format from datepicker


require("/etc/apache2/capstone-mysql/przm.php");
require("../class/flight.php");
require("../../lib/csrf.php");

/**
 * sets up all other needed variables that are same for outbound and return searches, then calls the method with all inputs
 * @param 	resource $mysqli pointer to temp mySQL connection, by reference
 * @param 	string $userOrigin with 3 letter origin city
 * @param 	string $userDestination with 3 letter destination city
 * @param 	string $userFlyDateStart of 7AM on user's chosen fly date
 * @return 	mixed $outputTable html table of search results
 **/
function completeSearch (&$mysqli, $userOrigin, $userDestination,
								 $userFlyDateStart) {

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
	for($i=0; empty($thisArrayOfPaths[$i]) === false; $i++) {

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

		// else origin is ET
		else {
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

		// else destination is ET
		else {
			$arrivalFlightLast = "ET";
			$arrivalFlightLast = $thisArrayOfPaths[$i][$sizeOfThisPath]->getArrivalDateTime()->setTimezone (new DateTimeZone("America/New_York"))->format("H:i");
		}

		// set up array for flight number then loop through flights
		$flightNumberArray = array();

		for($j = 0; empty($thisArrayOfPaths[$i][$j + 3]) === false; $j++) {
			$flightNumberArray = $thisArrayOfPaths[$i][$j]->getFlightNumber();
		}

		// turn array to string
		$flightNumber = implode(", ",$flightNumberArray);

		// index of last flight also = number of stops to show user
		if ($sizeOfThisPath = 0) {
			$numberOfStops = "Nonstop";
		} else {$numberOfStops = $sizeOfThisPath;}

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
		if (empty($layoverArray) === false) {
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
	return $outputTable;
}



try {
//	session_start();
//	$savedName  = $_POST["csrfName"];
//	$savedToken = $_POST["csrfToken"];
//
//
//	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
//		throw(new RuntimeException("Make sure cookies are enabled."));
//	}

	$mysqli = MysqliConfiguration::getMysqli();
//	$flightPaths = $_SESSION['flightPathsObj'];
	$flightPaths =

	// clean inputs, adjust dates to needed format for outbound flight
	$userOrigin = filter_input(INPUT_POST,"origin", FILTER_SANITIZE_STRING);
	$userDestination = filter_input(INPUT_POST,"destination", FILTER_SANITIZE_STRING);


	$userFlyDateStartIncoming1 = filter_input(INPUT_POST,"departDate", FILTER_SANITIZE_STRING);
		$userFlyDateStartIncoming2 = $userFlyDateStartIncoming1 . " 07:00:00";
		$userFlyDateStartObj = DateTime::createFromFormat("m/d/Y H:i:s", $userFlyDateStartIncoming2);
		$userFlyDateStart = $userFlyDateStartObj->format("Y-m-d H:i:s");
	echo $userFlyDateStart;

	$outputTableOutbound = completeSearch($mysqli, $userOrigin, $userDestination,
														$userFlyDateStart);

	echo $outputTableOutbound . "\n";

	//check to see if return trip search needed and execute if so
	if ($_POST ["roundTripOrOneWay"] === 1) {
		// clean inputs, adjust dates to needed format
		$userOrigin = filter_input(INPUT_POST, "destination", FILTER_SANITIZE_STRING);
		$userDestination = filter_input(INPUT_POST, "origin", FILTER_SANITIZE_STRING);


		$userFlyDateStart = filter_input(INPUT_POST, "returnDate", FILTER_SANITIZE_STRING);
		//	$userFlyDateStartIncoming2 = $userFlyDateStartIncoming1 . " 07:00:00";
		//	$userFlyDateStartObj = DateTime::createFromFormat("d-m-Y H:i:s", $userFlyDateStartIncoming2);
		//	$userFlyDateStart = $userFlyDateStartObj->format("Y-m-d) H:i:s");
		echo $userFlyDateStart;

		$outputTableInbound = completeSearch($mysqli, $userOrigin, $userDestination,
			$userFlyDateStart);

		echo $outputTableInbound;
	}




	// DateTime Math
}catch (Exception $e){
//	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>
  ".$e->getMessage()."</div>";
}
?>