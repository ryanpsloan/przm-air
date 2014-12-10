<?php
require("../class/user.php");
require("../class/profile.php");
require("../class/traveler.php");
require("../class/transaction.php");
require("../class/ticket.php");
require("../class/ticketflight.php");
require("/etc/apache2/capstone-mysql/przm.php");

session_start();

if(isset($_SESSION['userId'])) {
	$mysqli = MysqliConfiguration::getMysqli();
	$profile = Profile::getProfileByUserId($mysqli,$_SESSION['userId']);
	$fullName =  ucfirst($profile->__get('userFirstName')).' '.ucfirst($profile->__get('userLastName'));
	$userName = <<<EOF
<a><span	class="glyphicon glyphicon-user"></span> Welcome, $fullName  </a>
EOF;
	$status = <<< EOF
<a href="signOut.php">Sign Out</a>
EOF;
	$account = <<< EOF
<li role="presentation">
	<a href="#account" id="account-tab" role="tab" data-toggle="tab" aria-controls="account"
		aria-expanded="true">
		Account</a>
</li>
EOF;
}
 $flights = $_SESSION['flightObjArray'];
 $travelers = $_SESSION['travelerArray'];
 $transactionId = 1; // $_SESSION['transactionId'];
//generate confirmation #
$confirmationNumber = bin2hex(openssl_random_pseudo_bytes(3));
$confirmationNumber = strtoupper($confirmationNumber);
$price = 300.00; //$flights->getPrice();
$status = "PAID";

//create individual tickets
for($i = 0; $i < count($travelers); $i++){
	$tickets[] = new Ticket(null, $confirmationNumber, $price, $status,
										$profile->__get("profileId"), $travelers[$i], $transactionId);
	$tickets->insert($mysqli);
}
foreach($flights as $flight) {

		for($i = 0; $i < count($tickets); $i++){
			$ticketFlight = new TicketFlight($flight, $tickets[$i]->getTicketId());
		}
}
?>

