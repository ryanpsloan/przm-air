<?php
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
	$flights = $_SESSION['flightObjArray'];
	$profile = $_SESSION['profile'];
	$travelers = Traveler::getTravelerByProfileId($mysqli, $profile->__get("profileId"));
}
catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}

?>

//Display Flights

//Get Traveler Info -> populate drop down of saved travelers