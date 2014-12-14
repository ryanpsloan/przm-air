<?php
require("/etc/apache2/capstone-mysql/przm.php");
require("../class/user.php");
require("../class/profile.php");
require("../class/traveler.php");
include("../../lib/csrf.php");

try {
	session_start();
	$savedName = $_POST["csrfName"];
	$savedToken = $_POST["csrfToken"];

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled"));
	}
	$mysqli = MysqliConfiguration::getMysqli();
	if($_POST['action'] === "Change") {

		$user = User::getUserByUserId($mysqli, $_SESSION['userId']);
		$profile = Profile::getProfileByUserId($mysqli, $user->getUserId());
		$profileId = $profile->__get("profileId");
		$firstName = $profile->__get("userFirstName");
		$lastName = $profile->__get("userLastName");

		$query = "SELECT travelerId FROM traveler WHERE profileId = ? AND travelerFirstName = ? AND
					travelerLastName = ?";
		$statement = $mysqli->prepare($query);
		$statement->bind_param("iss", $profileId, $firstName, $lastName);
		$statement->execute();
		$result = $statement->get_result();
		$row = $result->fetch_assoc();
		$travelerId = $row['travelerId'];

		$traveler = Traveler::getTravelerByTravelerId($mysqli, $travelerId);

		$profile->setFirstName($newFirstName = filter_input(INPUT_POST, "first", FILTER_SANITIZE_STRING));
		$traveler->setFirstName($newFirstName);
		$newMiddleName = filter_input(INPUT_POST, "middle", FILTER_SANITIZE_STRING);
		if($newMiddleName !== "" || $newMiddleName !== " " || $newMiddleName !== null) {
			$profile->setMiddleName($newMiddleName);
			$traveler->setMiddleName($newMiddleName);
		}
		$profile->setLastName($newLastName = filter_input(INPUT_POST, "last", FILTER_SANITIZE_STRING));
		$traveler->setLastName($newLastName);
		$newDOB = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_STRING);
		$newDOB = DateTime::createFromFormat("m/d/Y", $newDOB);
		$profile->setDateOfBirth($newDOB->format("Y-m-d H:i:s"));
		$traveler->setDateOfBirth($newDOB->format("Y-m-d H:i:s"));
		$user->setEmail($newEmail = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL));

		$user->update($mysqli);
		$profile->update($mysqli);
		$traveler->update($mysqli);

		echo "<div class='alert alert-success' role='alert'>
  			Your profile has been updated with your changes</div>
			<script>
						$(document).ready(function() {
							$(':input').attr('disabled', true);
						});
			</script>";
	}
	elseif ($_POST['action'] === "Delete Your Profile"){

		$user = User::getUserByUserId($mysqli, $_SESSION['userId']);
		$profile = Profile::getProfileByUserId($mysqli, $user->getUserId());
		$travelers = Traveler::getTravelerByProfileId($mysqli, $profile->__get("profileId"));
		for($i = 0; $i < count($travelers); $i++){
			$travelers[$i]->delete($mysqli);
		}
		$profile->delete($mysqli);
		$user->delete($mysqli);

		$_SESSION = array();
		$params = session_get_cookie_params();
		setcookie(session_name(), "", 1, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		session_unset();
		session_destroy();

		echo <<<HTML
	<div class='alert alert-success' role='alert'>Your profile was successfully deleted</div>
	<script>
		setTimeout(function(){window.location.replace("../../index.php")}, 2000);
	</script>
HTML;

	}
}catch (Exception $e){
	$_SESSION[$savedName] = $savedToken;
	echo "<div class='alert alert-danger' role='alert'>"
  			.$e->getMessage()."</div>";
}
?>
