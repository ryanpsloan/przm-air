<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("../lib/csrf.php");
require_once("../forms/payment.php");
require_once("../php/class/user.php");
require_once("../php/class/transaction.php");

try {

	//@Paul, Ryan Here, I just realized that payment will need to check the profile(use $_SESSION['userId']) to see if
	//Profile:getProfileByUserId();
	//if profile->$__get("customerToken") == null
	//and if True then
	//create a customer token, this can be done by calling the public function createStripeCustomer($email, $token)
	//in the profile class, if you are getting us a live API key please update the API at the top of
	//profile when you have	one
	//Also some guidance, I see you have tested $_POST to see if its values are set the next
	//move is to filter them and set them into local variables. The CSRF can be tricky ask if you have questions.
	//be sure you are calling echo generateInputTags in the form so that they are created and give $_POST values to be
	//tested by payment processor
	//$var = filter_input(INPUT_POST,"postName", FILTER);
	//After that is up to you...

	//Ryan out

	//verify the user has signed in
	if(@isset($_SESSION['userId'])) {

	}

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