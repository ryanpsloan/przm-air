<?php
require("/etc/apache2/capstone-mysql/przm.php");
require("../php/class/profile.php");
require("../php/class/traveler.php");
include('../lib/csrf.php');

try{
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
	//$paths = $_SESSION['flightObjArray'];
	$staticTravelers = Traveler::getTravelerByProfileId($mysqli, $profile->__get("profileId"));

}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}

?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Travelers</title>
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

	<script type="text/javascript" src="../js/selectTravelers.js"></script>

	<script>
		$(function() {
			$( ".datepicker" ).datepicker({
				changeMonth: true,
				changeYear: true,
				maxDate: "0d",
				minDate: "-100y"
			});
		});



	</script>
	<style>
		#formDiv{
			position: absolute;
			top: 30%;
			left: 33%;
			display: inline;
		}
		#travelerContainer{
			border: 2px solid lightgrey;
			border-radius: 5%;
			height: 35em;
			width: 27em;
			margin-left: 4.7em;
			margin-top: 2em;
			margin-bottom: 2em;

		}
		#bookFltDiv{
			border: 2px solid lightgrey;
			height: 4em;
			width: 36em;
			border-radius: 15%;
			margin-bottom: 1em;

		}
		#bookFltDiv button{
			padding: .5em;
			margin-left: 14em;
			margin-top: .5em;
			background-color: lightblue;

		}
		.buttonDiv{
			margin-bottom: 2em;
			height: 4em;
			width: 36em;
			border-radius: 15%;
			border: 2px solid lightgrey;
		}
		.innerBtnDiv{
			margin-left: 3.7em;
		}
		#addTravelerDiv{
			height: 30em;
			width: 30em;
			border: 2px solid lightgrey;
			border-radius: 5%;
		}
		#addTInnerDiv input{
			margin-left: 3.5em;
		}
		#addTInnerDiv label{
			margin-left: 3.5em;
		}
		.travelerSelect{
			font-size: 1.2em;
			padding: .5em;
			background-color: white;
			border-radius: 4%;

		}
		.nameSpan{
			margin-left: .4em;
			padding: .5em;
			font-weight: bold;
		}
		#travelerList{
			background-color: white;
			height: 20em;

		}
		#ckBoxes input{
			margin-left: 4.2em;
		}
		table{
			margin-left: 3.3em;
		}
		table td{
			padding: .5em;
			margin: .5em;
		}
		#confirmBtn{
			padding: .5em;
			margin-left: 7.7em;
		}
	</style>
</head>
<body>
<header>
<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="../index.php"><span class="glyphicon glyphicon-cloud"
																			  aria-hidden="true"></span> PRZM AIR</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="disabled"><?php echo $userName?> </li>
				<li class="active"><?php echo $status?></li>
				<li><a href="#"></a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
</header>
<!-- Display Flights -->
<section>
	<div class="jumbotron">
		<div class="flightContainer">
			<!-- Zach will handle this -->
			<!--/* ?php foreach ($paths as $key => $flight){
				echo "<p>" . "Flight Id: " . $flight->flightId . " " . "Origin: " . $flight->origin . " " . "Destination: "
				. $flight->destination . " " . "Duration: " . $flight->duration . " " . "Departure: "
				. $flight->departureDateTime . " " . "Arrival: " .	$flight->arrivalDateTime . " " . "Flight Number: "
				. $flight->flightNumber . " " . "Price: " . $flight->price . " " . "Remaining Seats Available: "
				. $flight->totalSeatsOnPlane . " " . "</p>";
			} ?
			-->
		</div>
	</div>
</section>

<div id="formDiv">
<form id="selectTravelersForm" action="../php/processors/createTraveler.php" method="post">
	<?php echo generateInputTags(); ?>
	<div class="buttonDiv">
		<div class="innerBtnDiv">
			<table>
				<tr><td><button type="submit" name="action" class="btn" value="Remove">Remove Travelers</button></td>
					<td><button type="button" class="btn" data-toggle="modal" data-target="#myModal">
							Add Travelers</button></td></tr>
			</table>
		</div>
	</div>

	<div id="travelerContainer">
	<h3 style="text-align: center">Select travelers:</h3><br>
	<hr>
		<div id="travelerList">
			<div id="ckBoxes">
			<?php
			$travelerArray = array();
			if(count($staticTravelers) > 0) {
				foreach($staticTravelers as $traveler) {
					$name = $traveler->__get("travelerFirstName") . " " . $traveler->__get("travelerLastName");
					$name = ucwords($name);
					$uID = $traveler->__get("travelerId");
					echo <<<EOF
					<div class="travelerSelect"><input type="checkbox" name="travelerArray[]"
					value="$uID"><span class="nameSpan">$name</span></div>
EOF;
				}
			}
			else{
				echo "<p style='text-align: center'>You have not added any travelers</p>";
			}
			?>
			<div id="selectOutput"></div>
			</div>
		</div>
		<hr>

	</div>

	<div id="addTDiv"">
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Modal title</h4>
				</div>
				<div class="modal-body">
					<div id="addTravelerDiv">
						<div id="addTInnerDiv">


							<h4 style="text-align: center">You can have up to 6 travelers per itinerary</h4>
							<label for="tFirst">First Name:</label><br><input type="text" id="first" name="tFirst" size="30"
																							  autocomplete="off"><br>
							<label for="tMiddle">Middle Name:</label><br><input type="text" id="middle" name="tMiddle" size="30"
																								 autocomplete="off"><br>
							<label for="tLast">Last Name:</label><br><input type="text" id="last" name="tLast" size="30"
																							autocomplete="off"><br>
							<label for="tDOB">Date of Birth:</label><br><input type="text" class="datepicker" id="dob" name="tDOB"
																								size="10">

						</div>
						<div id="modalOutput"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" name="action" value="Add">Add</button>
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="buttonDiv">
		<div class="innerBtnDiv">
			<div id="confirmBtn"><button type="submit" name="action" class="btn" value="Confirm">Confirm
					Travelers</button></div>
		</div>
	</div>
	<div id="bookFltDiv" style="visibility: hidden">
		<form action="payment.php">
			<button type="submit" name="action" class="btn" value="Book" href="payment.php">Book Flight</button>
		</form>
	</div>
</form>
</div>
</body>
</html>


