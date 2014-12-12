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

	//$_SESSION['OutboundPath'] = array();
	$_SESSION['OutboundPath'] = $_POST ["OutboundPath"];

	if(!empty($_POST ["ReturnPath"])) {
		//$_SESSION['ReturnPath'] = array();
		$_SESSION['ReturnPath'] = $_POST ["ReturnPath"];
	}

	/*if(isset($_SESSION['userId'])){
		header("Location: ../../forms/selectTravelers.php");
	}
	else{
		header('Location: ../../forms/signIn.php');
	}*/

//	__SESSION alert($('input[name="selectFlightA"]:checked').val());
//	alert($('input[name="selectFlightB"]:checked').val());

}catch (Exception $e){
	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>
  ".$e->getMessage()."</div>";
}
?>