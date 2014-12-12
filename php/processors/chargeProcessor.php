<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("../php/class/transaction");
require_once("../php/class/user");
require_once("../php/class/profile");
require_once('../../lib/csrf.php');

// Set your secret key
Stripe::setApiKey("sk_test_rjlpx8EvsmEGVk5RinBMV0Jj");

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];


// Create the charge on Stripe's servers - this will charge the user's card
try {
	$charge = Stripe_Charge::create(array(
			"amount" => 1000, // amount in cents, again
			"currency" => "usd",
			"card" => $token,
			"description" => "payinguser@example.com")
	);
} catch(Stripe_CardError $e) {
	// The card has been declined
}

// insert new transaction into mysql

$mysqli = MysqliConfiguration::getMysqli();

$profile = Profile::getProfileByUserId($mysqli, $_SESSION['userId']);
$amount = "";
$dateApproved = new DateTime();
$cardToken = "";
$stripeToken ="";

$transaction = new Transaction(null, $profile->__get("ProfileId"), $amount, $dateApproved, $cardToken, $StripeToken);

try {
	$transaction->insert($mysqli);

} catch (Exception $exception) {
	echo "Unable to create transaction.";
}

// insert ticket(s) into mysql



// send ticket(s) via email to user

