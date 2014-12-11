<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 12/11/14
 * Time: 2:22 PM
 *
 *
 * Stores search selection(s) in session.  If user is signed in, sends user selection on search results page to the traveler page.  Else sends user to sign in page first.
 */

require("../../lib/csrf.php");


try {
	session_start();
	$mysqli = MysqliConfiguration::getMysqli();
	$savedName  = $_POST["csrfName"];
	$savedToken = $_POST["csrfToken"];


	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled."));
	}


	if(isset($_SESSION['userId'])){
		header("Location: selectTravelers.php");
	}
	else{
		header('Location: ../../forms/signIn.php');
	}




	__SESSION alert($('input[name="selectFlightA"]:checked').val());
	alert($('input[name="selectFlightB"]:checked').val());


	// if not return trip, build and echo output string with outbound only
	if ($_POST ["roundTripOrOneWay"] === 0) {
		echo $tableStringStart . "SELECT DEPARTURE FLIGHT</thead>" . $outputTableOutbound . $tableStringEnd;;
	} else {
		// otherwise, execute return search flight with same process: clean inputs, adjust dates to needed format for return trip
		$userOrigin = filter_input(INPUT_POST, "destination", FILTER_SANITIZE_STRING);
		$userDestination = filter_input(INPUT_POST, "origin", FILTER_SANITIZE_STRING);

		$userFlyDateStartIncoming1 = filter_input(INPUT_POST, "returnDate", FILTER_SANITIZE_STRING);
		$userFlyDateStartIncoming2 = $userFlyDateStartIncoming1 . " 07:00:00";
		$userFlyDateStartObj = DateTime::createFromFormat("d-m-Y H:i:s", $userFlyDateStartIncoming2, new DateTimeZone('UTC'));
		$userFlyDateStart = $userFlyDateStartObj->format("Y-m-d) H:i:s");

		// execute inbound flight search
		$outputTableInbound = completeSearch($mysqli, $userOrigin, $userDestination,
			$userFlyDateStart, "B");

		// build and echo output string with return flight
		echo 	$tableStringStart . "SELECT DEPARTURE FLIGHT</thead>" . $outputTableOutbound . $tableStringMid .
			"SELECT RETURN FLIGHT</thead>" . $outputTableInbound . $tableStringEnd;
	}




	// if user is not signed in, send to sign in page here.




}catch (Exception $e){
	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>
  ".$e->getMessage()."</div>";
}
?>