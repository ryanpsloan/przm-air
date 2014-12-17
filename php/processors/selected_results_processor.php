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
try {
	session_start();

	require_once("/etc/apache2/capstone-mysql/przm.php");
	require("../../lib/csrf.php");
	require("../class/flight.php");



	//$savedName  = $_POST["csrfName"];
	//$savedToken = $_POST["csrfToken"];


	/*if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled."));
	}*/

// The following code may be needed at later time to decrement seats on each flight at the time of selection by user
//
	$mysqli = MysqliConfiguration::getMysqli();
	$_SESSION['priceWithOutboundPath'] = $_POST ["priceWithOutboundPath"];

	// turn results into array, then grab number of passengers and loop through flight Ids to decrement seats available
	$outboundArray = explode(",", $_POST["priceWithOutboundPath"]);
	$outboundChangeBy = -$outboundArray[1];

	for ($i=2; empty($outboundArray[$i]) === false; $i++){
		$flightId = $outboundArray[$i];
		//Flight::changeNumberOfSeats($mysqli, $flightId, $outboundChangeBy);
	}

	if(!empty($_POST ["priceWithReturnPath"])) {
		$_SESSION['priceWithReturnPath'] = $_POST ["priceWithReturnPath"];

		// turn results into array, then grab number of passengers and loop through flight Ids to decrement seats available
		$returnArray = explode(",", $_POST["priceWithReturnPath"]);
		$returnChangeBy = -$returnArray[1];

		for ($j=2; empty($returnArray[$j]) === false; $j++){
			$flightId = $returnArray[$j];
			//Flight::changeNumberOfSeats($mysqli, $flightId, $returnChangeBy);
		}
	}


	if(isset($_SESSION['userId'])){
		header("Location: ../../forms/selectTravelers.php");
	}
	else{
		header('Location: ../../forms/signIn.php');

	}

//	__SESSION alert($('input[name="selectFlightA"]:checked').val());
//	alert($('input[name="selectFlightB"]:checked').val());

}catch (Exception $e){
	//$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>
  ".$e->getMessage()."</div>";
}
?>
