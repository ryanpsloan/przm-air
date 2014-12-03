<?php
	include("php/user.php");
	include("php/profile.php");

try {
	session_start();
	var_dump($_SESSION);
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
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
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
		<div role="tabpanel" class="tab-pane fade in active" id="search" aria-labelledby="search-tab"><p>Search
			Components	and Content Go Here</p>
			<!-- datepickers go here -->
			<p><label>Departure Date</label><input type="text" class="datepicker"></p>
			<p><label>Arrival Date</label><input type="text" class="datepicker"></p>


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