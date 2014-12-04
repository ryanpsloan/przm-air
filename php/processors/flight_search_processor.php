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



	$outputTable = "<table>";
	for($i=0; empty($thisArrayOfPaths[$i]) === true; $i++) {

		$sizeOfThisPath = count($thisArrayOfPaths[$i]) - 3;
		$departureFlight1 = $thisArrayOfPaths[$i][0]->getDepartureDateTime();
		$arrivalFlightLast = $thisArrayOfPaths[$i][$sizeOfThisPath]->getArrivalDateTime();
		$flightNumber = array();

		for($j = 0; empty($thisArrayOfPaths[$i][$j + 3]) === true; $j++) {
			$flightNumber = $thisArrayOfPaths[$i][$j]->getFlightNumber();
		}

		$numberOfStops = $sizeOfThisPath;

		$totalDurationInterval = $thisArrayOfPaths[$i][$sizeOfThisPath+1];
		$travelTime = $totalDurationInterval->format("H:i");

		$totalPrice = $thisArrayOfPaths[$i][$sizeOfThisPath+2];

		$outputTable = $outputTable . "<tr>" . $departureFlight1 . $arrivalFlightLast . $flightNumber . $numberOfStops . $travelTime . $totalPrice . "</tr>";
	}



	$outputTable = $outputTable . "</table>";
	echo $outputTable;





}catch (Exception $e){
	echo "<div class='alert alert-danger' role='alert'>
  <a href='#' class='alert-link'>".$e->getMessage."</a></div>";
}
?>