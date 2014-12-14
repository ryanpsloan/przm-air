<?php
session_start();
require("/etc/apache2/capstone-mysql/przm.php");
require_once("../php/class/traveler.php");
require_once("../php/class/profile.php");
if(isset($_SESSION['userId'])) {
	$mysqli = MysqliConfiguration::getMysqli();
	$profile = Profile::getProfileByUserId($mysqli,$_SESSION['userId']);
	$fullName =  ucfirst($profile->__get('userFirstName')).' '.ucfirst($profile->__get('userLastName'));
	$userName = <<<EOF
		<a><span	class="glyphicon glyphicon-user"></span> Welcome, $fullName  </a>
EOF;
	$status = <<< EOF
			<a href="../php/processors/signOut.php">Sign Out</a>
EOF;
	$account = <<< EOF
		<li role="presentation">
			<a href="#account" id="account-tab" role="tab" data-toggle="tab" aria-controls="account"
				aria-expanded="true">
				Account</a>
		</li>
EOF;
}
	$staticTravelers = Traveler::getTravelerByProfileId($mysqli, $profile->__get("profileId"));
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Edit Travelers</title>
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
		#travelerDiv{
			border: 1px solid lightgrey;
			height: 50em;
			width: 30em;
			position: absolute;
			top: 10%;
			left: 30%;

		}
		#innerDiv{
			padding: 2em;
		}
		.inline{
			margin-left: 1em;
			margin-right: 2em;
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
<div id="travelerDiv">
	<div id="innerDiv">
		<table id="travelerTable">
	<?php $i = 0;
			foreach($staticTravelers as $traveler) {
			$travelerId = $traveler->__get("travelerId");
			$name = $traveler->__get("travelerFirstName"). " " .$traveler->__get("travelerLastName");
			$name = ucwords($name);
			echo <<<HTML
				<form id="editTravelerForm" action="../php/processors/editTravelersProcessor.php" method="post">
				<p class="travelerLine"><input class="btn inline" type="submit" name="action" value="Edit">$name
				<input type="hidden" name="traveler" value="$travelerId"></p><hr>
				</form>
HTML;
			$i++;
			}
			echo "</table>";
	?>
			<div id="travelerContainer"></div>
	</div>
</div>

</body>
</html>


