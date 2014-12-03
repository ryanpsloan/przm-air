<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("../lib/csrf.php");
require_once("../php/payment.php");


try {
	// verify the form was submitted OK
	if(@isset($_POST["firstName"]) === false || @isset($_POST["lastName"]) === false || @isset($_POST["addressLine1"]) === false
			|| @isset($_POST["addressCity"]) === false || @isset($_POST["addressState"]) === false || @isset($_POST["addressZip"]) === false
			|| @isset($_POST["cardNumber"]) === false || @isset($_POST["cardCvc"]) === false || @isset($_POST["cardExpiryMonth"]) === false
			|| @isset($_POST["cardExpiryYear"]) === false) {
		throw(new RuntimeException("Form variables incomplete or missing"));
	}

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}



} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to process payment: " . $exception->getMessage() . "</div>";
}