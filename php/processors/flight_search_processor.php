<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 12/3/14
 * Time: 10:12 AM
 */



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


	$userOrigin = filter_input(INPUT_POST,"origin", FILTER_SANITIZE_STRING);
	$userDestination = filter_input(INPUT_POST,"destination", FILTER_SANITIZE_STRING);
	$userFlyDateStart = filter_input(INPUT_POST,"departDate", FILTER_SANITIZE_STRING);
	echo $userFlyDateStart;

	// can make this a user input in future to pre-filter results to a user-given duration amount in hours.
	$userFlyDateRange = 24;

	$numberOfPassengersRequested = filter_input(INPUT_POST,"numberOfPassengers", FILTER_SANITIZE_NUMBER_INT);
	$minLayover = filter_input(INPUT_POST,"minLayover", FILTER_SANITIZE_NUMBER_INT);



	$thisArrayOfPaths = Flight::getRoutesByUserInput($mysqli, $userOrigin, $userDestination,
			$userFlyDateStart, $userFlyDateRange,
			$numberOfPassengersRequested, $minLayover);

	$outputTable = "<table>";
	foreach($thisArrayOfPaths as $path) {
		// build on to outputTable
		$tableRow[$path] = $thisArrayOfPaths[$path];
	}



	$outputTableHead = "<thead><tr>
										<th>Depart</th>
										<th>Arrive</th>
										<th>Flight #</th>
										<th>Stops</th>
										<th>Travel Time</th>
										<th>Layover</th>
										<th>Price</th>
								</tr></thead>\n";

	$outputTableRows = "";

	for($i=0; empty($thisArrayOfPaths[$i]) === true; $i++) {

		$sizeOfThisPath = count($thisArrayOfPaths[$i]) - 3;
		$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime()->format("%H:%i");
		$arrivalFlightLast = $thisArrayOfPaths[$i][$sizeOfThisPath]->getArrivalDateTime()->format("%H:%i");
		$flightNumberArray = array();

		for($j = 0; empty($thisArrayOfPaths[$i][$j + 3]) === true; $j++) {
			$flightNumberArray = $thisArrayOfPaths[$i][$j]->getFlightNumber();
		}
		$flightNumber = implode(", ",$flightNumberArray);

		$numberOfStops = $sizeOfThisPath;

		$totalDurationInterval = $thisArrayOfPaths[$i][$sizeOfThisPath+1];
		$travelTime = $totalDurationInterval->format("%H:%i");

		//fixme time zone.  3 Options: 1. seed data 2. if statements or loop through all our origins/destinations and assign time zone, then do DateTime math.  3.  Google Places -- get Time Zone then do Date Time Math
		$layoverArray = array();
		for($k = 0; empty($thisArrayOfPaths[$i][$k + 3]) === false; $k++) {

			$layoverInterval = $thisArrayOfPaths[$i][$k]->getArrivalDateTime()->
			diff($thisArrayOfPaths[$i][$k + 1]->getDepartureDateTime());
			$minutes = $layoverInterval->days * 24 * 60;
			$minutes += $layoverInterval->h * 60;
			$minutes += $layoverInterval->i;

			$layoverArray = intval($minutes);
		}

		if (empty($layoverArray) === true) {
			$layoverString = "None";
		} else {
			$layoverString = implode(", ",$layoverArray);
		}

		$totalPrice = $thisArrayOfPaths[$i][$sizeOfThisPath+2];

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

}catch (Exception $e){
	echo "<div class='alert alert-danger' role='alert'>
  <a href='#' class='alert-link'>".$e->getMessage."</a></div>";
}
?>