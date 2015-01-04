<?php
require("../php/class/user.php");
require("../php/class/profile.php");
include("../lib/csrf.php");
require("/home/gaster15/przm.php");
try {
session_start();

if(isset($_SESSION['userId'])) {
	$mysqli = MysqliConfiguration::getMysqli();
	$profile = Profile::getProfileByUserId($mysqli,$_SESSION['userId']);
	$fullName =  ucfirst($profile->__get('userFirstName')).' '.ucfirst($profile->__get('userLastName'));
	$userName = <<<HTML
		<a><span
			class="glyphicon glyphicon-user"></span> Welcome, $fullName  </a>

HTML;
	$status = <<< HTML
			<a href="../php/processors/signOut.php">Sign Out</a>

HTML;

}

	$query = "SELECT email FROM user WHERE userId = ?";
	$statement = $mysqli->prepare($query);
	$statement->bind_param("i", $profile->__get('userId'));
	$statement->execute();
	$results = $statement->get_result();
	$row = $results->fetch_assoc();

	$email = $row['email'];

	$query = "SELECT userFirstName, userMiddleName, userLastName, dateOfBirth FROM profile WHERE profileId = ?";
	$statement = $mysqli->prepare($query);
	$statement->bind_param("i", $profile->__get("profileId"));
	$statement->execute();
	$results = $statement->get_result();
	$row = $results->fetch_assoc();

	$firstName = ucwords($row['userFirstName']);
	$middleName = ucwords($row['userMiddleName']);
	$lastName = ucwords($row['userLastName']);
	$newDateObj = DateTime::createFromFormat("Y-m-d H:i:s", $row['dateOfBirth']);
	$dateOfBirth = $newDateObj->format("m/d/Y");

}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'>".$e->getMessage()."</div>";
}
?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Edit Profile</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
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

	<link type="text/css" rel="stylesheet" href="../css/editUserProfile.css">

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
<div id="formDiv">
	<form id="editProfile" action="../php/processors/editUserProfileProcessor.php" method="POST">
		<fieldset>
			<legend>Profile</legend>
			<div id="outputArea"></div>
			<div id="container">

				<p><label>First Name</label></br>
					<input type="text" id="first" name="first" value="<?php echo $firstName ?>"></p>
				<p><label>Middle Name</label></br>
					<input type="text" id="middle" name="middle" value="<?php echo $middleName ?>"></p>
				<p><label>Last Name</label></br>
					<input type="text" id="last" name="last" value="<?php echo $lastName ?>"></p>
				<p><label>Date Of Birth</label></br>
					<input type="text" id="dob" name="dob" class="datepicker" value="<?php echo $dateOfBirth ?>"></p>
				<p><label>Email</label></br>
					<input type="email" id="email" name="email" value="<?php echo $email ?>"></p>
				<?php echo generateInputTags(); ?>
				<button class="btn" type="submit" name="action" value="Change">Submit Changes</button>
			</div>
		</fieldset>
		<br>
		<fieldset>
			<legend>Account</legend>
			<p id="pass"><a href="changePass.php">Change Your Password</a></p>
		</fieldset>
		<fieldset>
			<legend>Danger</legend>
			<p class="red"><input class="btn" type="submit" name="action" value="Delete Your Profile"></p>
		</fieldset>
	</form>


</div>

</body>
</html>
