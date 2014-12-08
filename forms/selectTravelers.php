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
	$paths = $_SESSION['flightObjArray'];
	$profile = $_SESSION['profileObj'];
	$travelers[] = Traveler::getTravelerByProfileId($mysqli, $profile->__get("profileId"));
	$numTravelers = count($travelers);
	$tNames = array();
	foreach($travelers as $traveler){
		$tNames[] = $traveler->getFirstName(). " " . $traveler->getLastName();
	}


}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}

?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Add Passengers</title>
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
	<script type="text/javascript" src="../js/editUserProfile.js"></script>
	<script>
		$(function() {
			$( ".datepicker" ).datepicker({
				changeMonth: true,
				changeYear: true,
				maxDate: "0d",
				minDate: "-100y"
			});
		});
		function createTraveler{
			var input;
			getElementByTagName('input');
		}
	</script>
</head>
<body>
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
<!-- Display Flights -->
<section>
	<div class="jumbotron">
		<div class="container">
			<?php foreach ($paths as $key => $flight){
				echo "<p>" . "Flight Id: " . $flight->flightId . " " . "Origin: " . $flight->origin . " " . "Destination: "
				. $flight->destination . " " . "Duration: " . $flight->duration . " " . "Departure: "
				. $flight->departureDateTime . " " . "Arrival: " .	$flight->arrivalDateTime . " " . "Flight Number: "
				. $flight->flightNumber . " " . "Price: " . $flight->price . " " . "Remaining Seats Available: "
				. $flight->totalSeatsOnPlane . " " . "</p>";
			}
			?>
		</div>
	</div>

</section>
<section>
	<p>Please select travelers:</p><br>
	<?php
		foreach($names as $name){
			echo <<<EOF
				<div class="travelerSelect"><input type="checkbox" name="selectTraveler"><p>$name</p></div>
EOF;
}
?>	//show register travelers with checkbox

	<div class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Add Traveler</h4>
				</div>
				<form name="createTravelerForm" action="createTraveler.php" method="POST">
				<div class="modal-body">
					<!-- inputs -->

					<p>Enter Traveler Details:</p><br>
					<p><label for=""></label><input type="text" id="tFirst"></p><br>
					<p><label for=""></label><input type="text" id="tMiddle"></p><br>
					<p><label for=""></label><input type="text" id="tLast"></p><br>
					<p><label for=""></label><input type="text" id="tDOB" class="datepicker"></p>
					<?php echo generateInputTags();?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="">Save Traveler</button>
				</div>
				</form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	//add traveler use modal





</section>
</body>
</html>

<<<EOF
<div class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">Traveler $i </h4>
			</div>
			<div class="modal-body">
				<p><label for=""></label><br><input type="text" id="tFirst" name="" autocomplete="off"></p>
				<p><label for=""></label><br><input type="text" id="tMiddle" name="" autocomplete="off"></p>
				<p><label for=""></label><br><input type="text" id="tLast" name="" autocomplete="off"></p>
				<p><label for=""></label><br><input type="text" id="tDob" name="" class="datepicker"></p>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="travelerInfo">
	<form id="travelerInfoForm" action="selectTravelersProcessor.php" method="POST">


		<p><button type="submit"></p>
	</form>
</div>
EOF;

