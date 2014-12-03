<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 12/3/14
 * Time: 10:12 AM
 */



require("/etc/apache2/capstone-mysql/przm.php");
require("../php/flight.php");
$mysqli = MysqliConfiguration::getMysqli();

session_start();
	$flightPath = $_SESSION['flightPaths'];

try {
	$thisArrayOfPaths = Flight::getRoutesByUserInput($this->mysqli, $userOrigin, $userDestination,
		$userFlyDateStart, $userFlyDateEnd,
		$numberOfPassengersRequested, $minLayover);
} catch(Exception $exception) {
	throw (new mysqli_sql_exception("Unable to create flight."));
	return;

?>