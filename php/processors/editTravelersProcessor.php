<?php
session_start();
require_once("/var/www/html/przm.php");
require_once("../../php/class/traveler.php");
require_once("../../php/class/profile.php");
require_once("../../lib/csrf.php");

try {
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
	$travelerId = $_POST['traveler'];
	if($_POST['action'] === "Edit") {

		$tempTraveler = Traveler::getTravelerByTravelerId($mysqli, $travelerId);
		$tempTravelerFirst = ucwords($tempTraveler->__get("travelerFirstName"));
		$tempTravelerMiddle = ucwords($tempTraveler->__get("travelerMiddleName"));
		$tempTravelerLast = ucwords($tempTraveler->__get("travelerLastName"));
		$tempTravelerDOB = $tempTraveler->__get("travelerDateOfBirth");
		$tempTravelerDOB = $tempTravelerDOB->format("m/d/Y");

		echo <<<HTML
		<div id="formDiv">
		<h4>Edit Traveler Information</h4>
		<hr>
		<div id="innerDiv">

		<form id="editProcessorForm" action="editTravelersProcessor.php" method="post">
		<input type="hidden" name="traveler" value="$travelerId">
		<p>
			<label for="tFirst">First Name:</label><br>
			<input type="text" id="first" name="tFirst" size="30" autocomplete="off" value="$tempTravelerFirst">
		</p><br>
		<p>
			<label for="tMiddle">Middle Name:</label><br>
			<input type="text" id="middle" name="tMiddle" size="30" autocomplete="off" value="$tempTravelerMiddle">
		</p><br>
		<p>
			<label for="tLast">Last Name:</label><br>
			<input type="text" id="last" name="tLast" size="30"autocomplete="off" value="$tempTravelerLast">
		</p><br>
		<p>
			<label for="tDOB">Date of Birth:</label><br>
			<input type="text" class="datepicker" id="dob" name="tDOB" size="10" value="$tempTravelerDOB">
		</p>
		<input type="submit" name="action" value="Change">
		</form>
		</div>
		</div>
HTML;
	}
	elseif($_POST['action'] === "Change"){
		$tempTraveler = Traveler::getTravelerByTravelerId($mysqli, $travelerId);
		$tFirst = filter_input(INPUT_POST, "tFirst", FILTER_SANITIZE_STRING);
		$tMiddle = filter_input(INPUT_POST, "tMiddle", FILTER_SANITIZE_STRING);
		$tLast = filter_input(INPUT_POST, "tLast", FILTER_SANITIZE_STRING);
		if(isset($_POST['tDOB'])) {
			$tDOB = filter_input(INPUT_POST, "tDOB", FILTER_SANITIZE_STRING);
			$tDOB = DateTime::createFromFormat("m/d/Y", $tDOB);
			$tDOB = $tDOB->format('Y-m-d H:i:s');
		}
		$tempTraveler->setFirstName($tFirst);
		$tempTraveler->setMiddleName($tMiddle);
		$tempTraveler->setLastName($tLast);
		$tempTraveler->setDateOfBirth($tDOB);
		$tempTraveler->update($mysqli);

		echo <<<HTML
			<div class='alert alert-success' role='alert'>Traveler Updated</div>
			<script>
				setTimeout(function(){window.location.replace("../../forms/editTravelers.php")}, 1000);
			</script>
HTML;

	}


}catch(Exception $e){
	$msg = $e->getMessage();
	echo <<<HTML
	<div class='alert alert-danger si' role='alert'>$msg</div>
HTML;

}
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Edit Traveler</title>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	<script type="text/javascript" src="../../js/editTravelersProcessor.js"></script>
	<style>
		#formDiv{
			border: 2px solid lightgrey;
			border-radius: 5%;
			position: absolute;
			top: 5%;
			left: 30%;
		}
		#innerDiv{
			padding: 2em;
		}
		h4{
			text-align: center;
		}
	</style>
</head>
<body>

</body>
</html>