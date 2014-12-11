<?php

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

?>

<!DOCTYPE html>
<html>
<head>
	<title>Tickets</title>
</head>
<body>

</body>
</html>
<?php
	$flightSchedule = $_SESSION['flightObj'];

	foreach($flightSchedule as $flight){

	}
?>