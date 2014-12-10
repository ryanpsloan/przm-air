<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
include("../../php/class/traveler.php");
include("../../php/class/profile.php");

try {
	session_start();
	$_SESSION['flightObj'] = 123;

	if($_POST['action'] === "Add") {
			$totalTravelers = Traveler::getTravelerByProfileId($mysqli, $profile->__get("profileId"));
			if($i = count($totalTravelers) > 5) {
				throw(new ErrorException("You cannot add more than 6 travelers to an itinerary"));
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
			echo <<<EOF
<div class="travelerSelect"><input type="checkbox" name="travelerArray[]" value="$tID"><span
class="nameSpan">$name</span></div>
<script>
	$(function(){
		location.reload();
	});
</script>
EOF;

	}
	elseif($_POST['action'] === "Remove"){
		if(isset($_POST['travelerArray'])) {
			$travelerArray = $_POST['travelerArray'];
			for($i = 0; $i < count($travelerArray); $i++) {
				$oldTraveler = Traveler::getTravelerByTravelerId($mysqli, $travelerArray[$i]);
				$oldTraveler->delete($mysqli);

				echo "<script>
						$(function(){
							location.reload();
						});
				</script>";
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
	elseif($_POST['action'] === "Confirm") {
		if(!isset($_POST['travelerArray'])){
			throw(new ErrorException("Please check the travelers for who you are purchasing this flight"));
		}

		if(isset($_SESSION['flightObj'])) {
			$_SESSION['travelerArray'] = $_POST['travelerArray'];
			echo <<<EOF
				<p style="text-align: center">Your travelers have been confirmed</p>
				<script>
					$(function () {
						$('#bookFltDiv').css('visibility', 'visible');
					});

				</script>

EOF;


		} else {
			echo "<p style='text-align: center'>Your travelers have been confirmed</p>";
		}
	}
	elseif($_POST['action'] === "Book"){


	}

}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
?>