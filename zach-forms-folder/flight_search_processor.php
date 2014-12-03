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


		$thisArrayOfPaths = Flight::getRoutesByUserInput($this->mysqli, $userOrigin, $userDestination,
			$userFlyDateStart, $userFlyDateEnd,
			$numberOfPassengersRequested, $minLayover);









}catch (Exception $e){
	echo "<div class='alert alert-danger' role='alert'>
  <a href='#' class='alert-link'>".$e->getMessage."</a></div>";
}
?>