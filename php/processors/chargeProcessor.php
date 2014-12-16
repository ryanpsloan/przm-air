<!DOCTYPE html>
<html>
<head>
	<title>Process Transaction</title>
</head>
<body>
<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("../class/transaction.php");
require_once("../class/user.php");
require_once("../class/profile.php");
require_once("../class/flight.php");
require_once("../class/ticket.php");
require_once("../class/ticketFlight.php");
require_once('../../lib/csrf.php');

$mysqli = MysqliConfiguration::getMysqli();

$user = User::getUserByUserId($mysqli, $_SESSION['userId']);
$email = $user->getEmail();


// Set your secret key
Stripe::setApiKey("sk_test_rjlpx8EvsmEGVk5RinBMV0Jj");

// Get the credit card details submitted by the form
$token = $_SESSION['stripeToken'];
$amount = $_SESSION['totalInCents'];
$price = $_SESSION['price'];


// Create the charge on Stripe's servers - this will charge the user's card
try {
	$charge = Stripe_Charge::create(array(
			"amount" => $amount, // amount in cents, again
			"currency" => "usd",
			"card" => $token,
			"description" => $email)
	);

	if ($charge->paid === true) {
		$profile = Profile::getProfileByUserId($mysqli, $_SESSION['userId']);
		$dateApproved = new DateTime("NOW");
		$cardToken = null;
		$stripeToken = $charge->id;
		$transaction = new Transaction(null, $profile->__get("profileId"), $amount, $dateApproved, $cardToken,
			$stripeToken);
		$transaction->insert($mysqli);
		$flights = $_SESSION['flightIds'];
		$travelers = $_SESSION['travelerIds'];
		for($i = 0; $i < count($flights); $i++) {
			Flight::changeNumberOfSeats($mysqli, $flights[$i], -(count($travelers)));
		}

		$transactionId = $transaction->getTransactionId();
		$status = "PAID";

		for($i = 0; $i < count($travelers); $i++) {
			//generate confirmation #
			$confirmationNumber = bin2hex(openssl_random_pseudo_bytes(3));
			$confirmationNumber = strtoupper($confirmationNumber);
			$tickets[$i] = new Ticket(null, $confirmationNumber, $price, $status,
				$profile->__get("profileId"), $travelers[$i], $transactionId);
			$tickets[$i]->insert($mysqli);
			$_SESSION['ticketIds'] = $tickets[$i];
		}
		foreach($flights as $flight) {

			for($i = 0; $i < count($tickets); $i++) {
				$ticketFlights[$i] = new TicketFlight($flight, $tickets[$i]->getTicketId());
				$ticketFlights[$i]->insert($mysqli);
			}
		}

		$_SESSION['ticketFlights'] = $ticketFlights;
		header("Location: ../../forms/displayTickets.php");
	}
	else{
		echo <<<HTML
			<div class='alert alert-danger' role='alert'>Payment unsuccessful...Redirecting</div>
			<script>
				setTimeout(function(){window.location.replace("../../forms/confirmationPage.php")}, 3000)
			</script>
HTML;

	}
}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}

?>
</body>
</html>