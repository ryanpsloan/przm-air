<?php
session_start();

include("php/class/user.php");
include("php/class/profile.php");
include("php/class/flight.php");
include("lib/csrf.php");

try {


	$mysqli = MysqliConfiguration::getMysqli();

	if(isset($_SESSION['userId'])) {
		$profile = Profile::getProfileByUserId($mysqli, $_SESSION['userId']);
		$fullName =  ucfirst($profile->__get('userFirstName')).' '.ucfirst($profile->__get('userLastName'));
		$userName = <<<EOF
		<a><span
			class="glyphicon glyphicon-user"></span> Welcome, $fullName  </a>

EOF;
		$status = <<< EOF
			<a href="php/processors/signOut.php">Sign Out</a>

EOF;
		$account = <<< EOF
		<li role="presentation">
			<a href="#account" id="account-tab" role="tab" data-toggle="tab" aria-controls="account"
				aria-expanded="true">
				Account</a>
		</li>


EOF;
	}
	else {
		$userName = "";
		$status = <<< EOF
			<a href="forms/signIn.php">Sign In</a>
EOF;
		$account = "";
	}
} catch(Exception $e){

}
?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>PRZM AIR</title>
	<!-- CSS -->
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<link type="text/css" rel="stylesheet" href="css/index.css">

	<!-- JS -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	<script type="text/javascript" src="js/typeahead.js"></script>
	<script type="text/javascript" src="js/utility.js"></script>
	<script type="text/javascript" src="js/indexValidation.js"></script>






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
					<a class="navbar-brand" href="# "><span class="glyphicon glyphicon-cloud"
																					  aria-hidden="true"></span> PRZM AIR</a>
					</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li></li>
						<li><a href="#"></a></li>
						<li><a href="#">About</a></li>
						<li></li>
						<li></li>
						<li></li>

					</ul>

					<ul class="nav navbar-nav navbar-right">
						<li class="disabled"><?php echo $userName?> </li>
						<li class="active"><?php echo $status?></li>
						<li></li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
	</header>
	<div class="bs-example bs-example-tabs" role="tabpanel">
		<ul id="myTabs" class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active">
				<a href="#search" id="search-tab" role="tab" data-toggle="tab" aria-controls="search" aria-expanded="true">Plan
					Your Flight</a>
			</li>

			<li role="presentation">
				<a href="#checkIn" id="checkIn-tab" role="tab" data-toggle="tab" aria-controls="checkIn"
					aria-expanded="true">CheckIn</a>
			</li>

			<?php echo $account?>
		</ul>
		<div id="myTabContent" class="tab-content">

			<div role="tabpanel" class="tab-pane fade in active" id="search" aria-labelledby="search-tab">
				<form class="navbar-form navbar-left" id="flightSearchForm"
							 action="php/processors/search_results_processor2.php" method="POST">
					<div class="form-group">
						<div class="btn-group styleBtn" data-toggle="buttons">
							<label class="btn btn-primary active">
								<input type="radio" class="form-control" name="roundTripOrOneWay" id="roundTrip" autocomplete="off" checked
										 value="no">
								Round Trip
							</label>
							<label class="btn btn-primary">
								<input type="radio" class="form-control" name="roundTripOrOneWay" id="oneWay" autocomplete="off" value="yes">
								One Way
							</label>
						</div>
						<div id="multiple-datasets">

							<p><label>From:</label><br/>
								<input type="text" class="form-control typeahead" placeholder="search for origin" id="origin" name="origin" autocomplete="off"><br/>
							</p>


							<p><label>To:</label><br/>
								<input type="text" class="form-control typeahead" placeholder="search for destination" id="destination" name="destination" autocomplete="off"><br/>
							</p>

						</div>
						<p>
							<label>Departure Date:</label><br/>
							<input type="text" class="datepicker" id="departDate" name="departDate" autocomplete="off">
						</p>


						<p id="returnDateP">
							<label>Return Date:</label><br/>
							<input type="text" class="datepicker" id="returnDate" name="returnDate" disabled="disabled" autocomplete="off">
						</p>

						<p><label>Number of Travelers:</label><br/>
							<input type="text" class="form-control" id="numberOfPassengers" name="numberOfPassengers" size="5" value = "1"
									 autocomplete="off">
						</p>

						<p><label>Minimum Layover: </label><br/>
							<input type="text" class="form-control" id="minLayover" name="minLayover" size="5" value = "30" autocomplete="off"
								><br/>
							<em>enter number of minutes</em>
						</p>

						<p><button type="submit" class="btn btn-default">Search Flights</button></p>
						<hr>
						<p><label class="btn btn-primary active">
							<input type="checkbox" name="options" id="flexDatesBoolean" name="flexDatesBoolean" autocomplete="off">
								Flexible Dates?
							</label>
						</p>
						<div style="text-align: left">
								<em>**still under construction**</em><br/>select to see grid of cheapest fares in month</em>
						</div>
						<?php /*echo generateInputTags()
							fixme csrf stuff, needs to be validated in your form processor uncomment when ready
							to implement*/?>
					</div>
				</form>
				<img src="img/white-clouds-and-blue-sky_1600x1200_78559.jpg">
			</div>


			<div role="tabpanel" class="tab-pane fade" id="checkIn"
				  aria-labelledby="checkIn-tab">
				<div id="checkInLinksDiv">
					<ul id="checkInLinksList">
						<li><p><a href="#">
							<span class="glyphicon glyphicon-plus"></span>Check Flight Status</a></p></li>
						<li><p><a href="#">
							<span class="glyphicon glyphicon-plus"></span>Check In</a></p></li>
					</ul>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="account"
				  aria-labelledby="account-tab">
				<div id="accountLinksDiv">
					<ul id="accountLinksList">
						<li><p><a href="forms/editUserProfile.php">
							<span class="glyphicon glyphicon-plus"></span>Edit Profile</a></p></li>
						<li><p><a href="forms/editTravelers.php">
									<span class="glyphicon glyphicon-plus"></span>Edit Travelers</a></p></li>
						<li><p><a href="forms/viewItinerary.php">
									<span class="glyphicon glyphicon-plus"></span>View Itinerary</a></p></li>
						<li><p><a href="">
									<span class="glyphicon glyphicon-minus"></span>Cancel Flight</a></p></li>

					</ul>
				</div>
			</div>


		</div>
	</div>
</body>
</html>