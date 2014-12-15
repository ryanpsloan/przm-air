<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("../php/class/transaction");
require_once("../php/class/user");
require_once("../php/class/profile");
require_once('../../lib/csrf.php');


$mysqli = MysqliConfiguration::getMysqli();

$email = User::getUserByEmail($mysqli, $_SESSION['userId']);

// Set your secret key
Stripe::setApiKey("sk_test_rjlpx8EvsmEGVk5RinBMV0Jj");

// Get the credit card details submitted by the form
$token = $_POST['stripeToken'];

$amount = $_SESSION['totalInCents'];


// Create the charge on Stripe's servers - this will charge the user's card
try {
	$charge = Stripe_Charge::create(array(
			"amount" => $amount, // amount in cents, again
			"currency" => "usd",
			"card" => $token,
			"description" => $email)
	);
	var_dump($charge);

// Check that it was paid:
	if ($charge->paid === true) {

		// Store the order in the database.
		// Send the email.
		// Celebrate!

		// insert new transaction into mysql


		$profile = Profile::getProfileByUserId($mysqli, $_SESSION['userId']);
		$amount = "";
		$dateApproved = new DateTime();
		$cardToken = null;
		$stripeToken ="";

		$transaction = new Transaction(null, $profile->__get("ProfileId"), $amount, $dateApproved, $cardToken, $StripeToken);

		try {
			$transaction->insert($mysqli);

		} catch (Exception $exception) {
			echo "Unable to create transaction.";
		}



	}
} catch(Stripe_CardError $e) {
	// The card has been declined
}

$flights = $_SESSION['flightIds'];
for($i = 0; $i < count($flights); $i++){
	$tempFlt = Flight::changeNumberOfSeats($mysqli, $flights[$i], -(count($_SESSION['travelerIds'])));
}
$travelers = $_SESSION['travelerIds'];
$transactionId = $_SESSION['transactionId'];
$totalPrice = $_SESSION['total'];
$status = "PAID";

//create individual tickets
for($i = 0; $i < count($travelers); $i++){
	//generate confirmation #
	$confirmationNumber = bin2hex(openssl_random_pseudo_bytes(3));
	$confirmationNumber = strtoupper($confirmationNumber);
	$tickets[$i] = new Ticket(null, $confirmationNumber, $price, $status,
		$profile->__get("profileId"), $travelers[$i], $transactionId);
	$tickets[$i]->insert($mysqli);
}
foreach($flights as $flight) {

	for($i = 0; $i < count($tickets); $i++){
		$ticketFlight[] = new TicketFlight($flight, $tickets[$i]->getTicketId());
		$ticketFlight->insert($mysqli);
	}
}
$_SESSION['tickets'] = $tickets;
$_SESSION['ticketFlights'] = $ticketFlight;
header("Location: ../../forms/displayTickets.php");


