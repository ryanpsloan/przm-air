<?php
require("../class/user.php");
require("../class/profile.php");
require("../class/traveler.php");
require("../class/transaction.php");
require("../class/ticket.php");
require("../class/ticketFlight.php");
require("/etc/apache2/capstone-mysql/przm.php");

session_start();

$flights = $_SESSION['flightObjArray'];
$travelers = $_SESSION['travelerArray'];
$transactionId = 1; // $_SESSION['transactionId'];
$price = 300.00; //$flights[0]->getPrice();
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
			$ticketFlight = new TicketFlight($flight, $tickets[$i]->getTicketId());
		}
}
$_SESSION['tickets'] = $tickets;
header("Location: ../../forms/confirmationPage.php");
?>

