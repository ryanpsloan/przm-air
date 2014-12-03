<?php
	include("php/user.php");
	include("php/profile.php");
try {
	require("php/flight.php");
	session_start();
	if(isset($_SESSION['userId'])) {
		$status = <<< EOF
			<a href="userLogin/signOut.php">Sign Out</a>

EOF;
		$account = <<< EOF
		<li><a href="userLogin/editUserProfile.php">Edit Profile</a></li>
EOF;
	}
	else {
		$status = <<< EOF
			<a href="userLogin/signIn.php">Sign In</a>
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
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script type="text/javascript" src="flight_search.js"></script>


	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>


	<!-- Latest compiled and minified CSS -->
	<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">



	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<link type="text/css" rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	<style>
		h1{
			text-align: center;

		}

	</style>
	<script>
		$(function() {
			$( ".datepicker" ).datepicker({
				changeMonth: true,
				changeYear: true,
				maxDate: "+1y",
				minDate: "0d"
			});
		});
	</script>
</head>
<body>
<header>

	<h1> PRZM AIR</h1>
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
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="#">Link </a></li>
					<li><a href="#">Link</a></li>
					<li><a href="#">Link</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					<li class="active"><?php echo $status?></li>
					<li><?php echo $account?></li>
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
			<a href="#reservation" id="reservation-tab" role="tab" data-toggle="tab" aria-controls="reservation"
				aria-expanded="true">
				Reservations</a>
		</li>
		<li role="presentation">
			<a href="#checkIn" id="checkIn-tab" role="tab" data-toggle="tab" aria-controls="checkIn"
				aria-expanded="true">CheckIn</a>
		</li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div role="tabpanel" class="tab-pane fade in active" id="search" aria-labelledby="search-tab">

			<form class="navbar-form navbar-left" role="search" id="flightSearch" action="flight_search_processor.php" method="POST">
				<div class="form-group">

					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-primary active">
							<input type="radio" name="roundTrip" id="roundTrip" autocomplete="off" checked>
							Round Trip
						</label>
						<label class="btn btn-primary">
							<input type="radio" name="oneWay" id="oneWay" autocomplete="off">
							One Way
						</label>
					</div>
					<p></p>
					<p></p>

					<p><label>From:</label><br/>
						<input type="text" class="form-control" id="origin" name="origin"><br/>
						<em>enter city or airport code</em></p>
						<p></p>
						<p></p>

					<p><label>To:</label><br/>
						<input type="text" class="form-control" id="destination" name="destination"><br/>
						<em>enter city or airport code</em></p>
						<p></p>
						<p></p>
						<p></p>

					<p><label>Departure Date:</label><br/>
						<input type="text" class="datepicker" id="departDate" name="departDate"></p>
						<p></p>
						<p></p>

					<p><label>Return Date:</label><br/>
						<input type="text" class="datepicker" id="returnDate" name="returnDate"></p>
						<p></p>
						<p></p>

					<label class="btn btn-primary active">
						<input type="checkbox" name="options" id="flexDatesBoolean" name="flexDatesBoolean" autocomplete="off">
						Flexible Dates?
					</label><p><em>  select to see grid of cheapest fares in month</em></p>
					<p></p>
					<p></p>

					<p><label>Number of Passengers:</label><br/>
						<input type="text" class="form-control" id="numberOfPassengers" name="numberOfPassengers"></p>
						<p></p>
						<p></p>

					<p><label>Minimum Layover: </label><br/>
						<input type="text" class="form-control" id="minLayover" name="minLayover"><br/>
						<em>enter number of minutes</em></p>
					<p></p>
					<p></p>


					<button type="submit" class="btn btn-default">Submit</button>
				</div>
			</form>
			<div id="searchOutputArea">
				<ul>
					<li>


						<button type="selectRoute" class="btn btn-default">Select Route</button>

					</li>


				</ul>



			</div>

		</div>
		<div role="tabpanel" class="tab-pane fade" id="reservation"
			  aria-labelledby="reservation-tab"><p>Links to change functions go here
			Here</p>
		</div>
		<div role="tabpanel" class="tab-pane fade" id="checkIn"
			  aria-labelledby="checkIn-tab"><p>Links to check in go here</p>
		</div>
	</div>
</div>

</body>
</html>