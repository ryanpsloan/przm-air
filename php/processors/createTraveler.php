<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
include("../../php/class/traveler.php");
include('../../lib/csrf.php');

try {
	session_start();

	if(isset($_SESSION['userId'])) {
		$mysqli = MysqliConfiguration::getMysqli();
		$profile = Profile::getProfileByUserId($mysqli,$_SESSION['userId']);
		$fullName =  ucfirst($profile->__get('userFirstName')).' '.ucfirst($profile->__get('userLastName'));
		$userName = <<<EOF
		<a><span
			class="glyphicon glyphicon-user"></span> Welcome, $fullName  </a>

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

	$mysqli = MysqliConfiguration::getMysqli();
	$savedName = $_POST["csrfName"];
	$savedToken = $_POST["csrfToken"];

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled"));
	}

	$tFirst = filter_input(INPUT_POST, "tFirst", FILTER_SANITIZE_STRING);
	$tMiddle = filter_input(INPUT_POST, "tMiddle", FILTER_SANITIZE_STRING);
	$tLast = filter_input(INPUT_POST, "tLast", FILTER_SANITIZE_STRING);
	$tDOB = filter_input(INPUT_POST, "tDOB", FILTER_SANITIZE_STRING);

	echo <<<EOF
<div class="travelerSelect"><input type="checkbox" name="selectTraveler"><p>$name</p></div>
EOF;

}catch(Exception $e){
	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
	?>