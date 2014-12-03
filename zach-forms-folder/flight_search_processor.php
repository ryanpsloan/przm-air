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



	$thisArrayOfPaths = Flight::getRoutesByUserInput($this->mysqli, $userOrigin, $userDestination,
			$userFlyDateStart, $userFlyDateEnd,
			$numberOfPassengersRequested, $minLayover);









}catch (Exception $e){
	echo "<div class='alert alert-danger' role='alert'>
  <a href='#' class='alert-link'>".$e->getMessage."</a></div>";
}
?>