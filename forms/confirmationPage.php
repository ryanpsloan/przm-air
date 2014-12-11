<?php
session_start();
require("/etc/apache2/capstone-mysql/przm.php");
require_once("../lib/csrf.php");



try {
	$savedName = $_POST["csrfName"];
	$savedToken = $_POST["csrfToken"];

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled"));
	}

	if(isset($_SESSION['userId'])) {
		$mysqli = MysqliConfiguration::getMysqli();
		$profile = Profile::getProfileByUserId($mysqli, $_SESSION['userId']);
		$fullName = ucfirst($profile->__get('userFirstName')) . ' ' . ucfirst($profile->__get('userLastName'));
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
}catch(Exception $e){
	$_SESSION[$savedName] = $savedToken;

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Ticketing</title>
	<style>
		.displayFlt{

		}
		.flightData{

		}
		.flightData td{
			padding: .5em;

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
</body>
</html>

<?php
	$flightSchedule = $_SESSION['flightObj'];

	foreach($flightSchedule as $flight){
	echo <<<HTML
				<div class="displayFlt">
				<table class="flightData table">
				<tr>
					<th>Flight Number</th>
					<th>"Origin</th>
					<th>Destination</th>
					<th>Duration</th>
					<th>Departure</th>
					<th>Arrival</th>
					<th>Price</th>
				</tr>
				<tr>
					<td>$flight->flightNumber</td>
					<td>$flight->origin</td>
					<td>$flight->destination</td>
					<td>$flight->duration</td>
					<td>$flight->departureDateTime</td>
					<td>$flight->arrivalDateTime</td>
					<td>$flight->price</td>
				<tr>
			</table>
			</div>
HTML;

}
	foreach($travelerArray as $traveler){
		echo "<ul>"
		echo <<<HTML

HTML;

	}

?>

?>