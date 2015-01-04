<?php
	session_start();
	require("/home/gaster15/przm.php");
	include("php/class/user.php");
	include("php/class/profile.php");
	include("php/class/flight.php");

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
	}else{
		$status = <<< EOF
	<a href="forms/signIn.php">Sign In</a>
EOF;

	}
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>The Team</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!-- CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
	<!-- JS -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<style>
		span{
			font-size: 1em;
		}
		h1{
			font-size: 1,8em;
		}
		#container{

		}
		.element{
			background-color: lightgrey;
			border: 1px solid skyblue;
			padding: 1em;
			margin: 1em 2em 1em 2em;
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
				<a class="navbar-brand" href="# "><span class="glyphicon glyphicon-cloud"
																	 aria-hidden="true"></span> PRZM AIR</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li></li>
					<li><a href="#"></a></li>
					<li></li>
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
<div id="container">
<!--Paul-->
	<div class="element">
		<h1><span><em>P</em></span>aul Morbitzer</h1>
		<div class="text">
			<p>text goes here</p>
		</div>
	</div>

<!--Ryan-->
	<div class="element">
		<h1><span><em>R</em></span>yan Pace Sloan</h1>
		<div class="text">
			<p>While a student at Deep Dive Coders Web Development Bootcamp I worked with Paul, Zach,
				and Marc to build this site. <br><br>Below is a list of my contributions:
			<h3>Database Design</h3>
			<ul>
				<li>Helped with Design</li>
				<li>Created ERD Diagram</li>
				<li>Coded table structure with SQL</li>
			</ul>
			<h3>Classes I wrote in PHP and Unit Tested</h3>
				<ul>
					<li>User Class</li>
					<li>Profile Class</li>
					<li>Traveler Class</li>
				</ul>
				<h3>Forms I developed using PHP, Mysqli, jQuery, JavaScript, HTML and CSS</h3>
				<ul>
					<li>sign/in, sign/up</li>
					<li>forgot/change password</li>
					<li>edit profile/traveler</li>
					<li>select travelers</li>
					<li>confirmation page</li>
					<li>ticket display</li>
					<li>view itinerary</li>
				</ul>
			<h3>PHP processors</h3>
				<ul>
					<li>sign/in, sign/up</li>
					<li>forgot/change password</li>
					<li>edit profile/traveler</li>
					<li>select travelers</li>
					<li>confimation and charge</li>
				</ul>
			</p>
		</div>
	</div>
<!--Zach-->
	<div class="element">
		<h1><span><em>Z</em></span>ach Grant</h1>
		<div class="text">
			<p>text goes here</p>
		</div>
	</div>
<!-- Marc -->
	<div class="element">
		<h1><span><em>M</em></span>arc Hayes</h1>
		<div class="text">
			<p>text goes here</p>
		</div>
	</div>
</div>
</body>
</html>