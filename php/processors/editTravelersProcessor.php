<?php
session_start();
require_once("/etc/apache2/capstone-mysql/przm.php");
require_once("../../php/class/traveler.php");
require_once("../../php/class/profile.php");
require_once("../../lib/csrf.php");

try {


	$savedName  = $_POST["csrfName"];
	$savedToken = $_POST["csrfToken"];

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled"));
	}
	$mysqli = MysqliConfiguration::getMysqli();
	$profile = Profile::getProfileByUserId($mysqli, $_SESSION['userId']);
	if($_POST['action'] === "Add") {
		$totalTravelers = Traveler::getTravelerByProfileId($mysqli, $profile->__get("profileId"));
		if($i = count($totalTravelers) > 7) {
			throw(new ErrorException("You cannot add more than 8 travelers to an itinerary"));
		}

		$tFirst = filter_input(INPUT_POST, "tFirst", FILTER_SANITIZE_STRING);
		$tMiddle = filter_input(INPUT_POST, "tMiddle", FILTER_SANITIZE_STRING);
		$tLast = filter_input(INPUT_POST, "tLast", FILTER_SANITIZE_STRING);
		if(isset($_POST['tDOB'])) {
			$tDOB = filter_input(INPUT_POST, "tDOB", FILTER_SANITIZE_STRING);
			$tDOB = DateTime::createFromFormat("m/d/Y", $tDOB);
			$tDOB = $tDOB->format('Y-m-d H:i:s');
		}

		$newTraveler = new Traveler(null, $profile->__get('profileId'), $tFirst, $tMiddle, $tLast, $tDOB);
		$newTraveler->insert($mysqli);
		$name = $newTraveler->__get("travelerFirstName") . " " . $newTraveler->__get("travelerLastName");
		$name = ucwords($name);
		$tID = $newTraveler->__get("travelerId");
		echo <<<HTML
				<div class="travelerSelect">
					<input type="checkbox" name="travelerArray[]" value="$tID">
					<span class="nameSpan">$name</span>
				</div>
<script>
	$(function(){
		location.reload();
	});
</script>
HTML;

	}
	elseif($_POST['action'] === "Remove"){
		if(isset($_POST['travelerArray'])) {
			$travelerArray = $_POST['travelerArray'];
			for($i = 0; $i < count($travelerArray); $i++) {
				$oldTraveler = Traveler::getTravelerByTravelerId($mysqli, $travelerArray[$i]);
				$oldTraveler->delete($mysqli);

			}
		}
		else{
			if(count(Traveler::getTravelerByProfileId($mysqli, $profile->__get("profileId"))) > 0) {
				throw(new Exception("Please select the travelers you wish to delete"));
			}
			else{
				throw(new Exception("There are currently no travelers added"));
			}
		}
	}
	elseif($_POST['action'] === "Edit") {
		if(!isset($_POST['travelerArray'])){
			throw(new ErrorException("Please check the travelers for whom you are purchasing this flight"));
		}

		if(isset($_SESSION['priceWithOutboundPath'])) {
			$_SESSION['travelerIds'] = $_POST['travelerArray'];
			echo <<<HTML
				<div class='alert alert-success si' role='alert' style="text-align: center">
				Your travelers have been confirmed</div>

HTML;


		} else {
			echo "<div class='alert alert-success si' role='alert' style=\"text-align: center\">
				Your travelers have been successfully edited</div>";
		}
	}
	elseif($_POST['action'] === "Book"){
		header("Location: ../../forms/confirmationPage.php");

	}

}catch(Exception $e){
	$_SESSION[$savedName] = $savedToken;
	$msg = $e->getMessage();
	echo <<<HTML
	<div class='alert alert-danger si' role='alert'>$msg</div>
	<script>
	setTimeout(function(){location.reload()}, 5000)
	</script>
HTML;


}
?>