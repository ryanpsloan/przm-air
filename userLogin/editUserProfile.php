<?php
require("../php/user.php");
require("../php/profile.php");
include("../lib/csrf.php");
require("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();
session_start();

$profileObj = $_SESSION['profileObj'];

try {
	$query = "SELECT email FROM user WHERE userId = ?";
	$statement = $mysqli->prepare($query);
	$statement->bind_param("i", $profileObj->__get('userId'));
	$statement->execute();
	$results = $statement->get_result();
	$row = $results->fetch_assoc();
} catch(mysqli_sql_exception $exception){
		$exception->getMessage();
}

$email = $row['email'];


try {
	$query = "SELECT userFirstName, userMiddleName, userLastName, dateOfBirth FROM profile WHERE profileId = ?";
	$statement = $mysqli->prepare($query);
	$statement->bind_param("i", $profileObj->__get("profileId"));
	$statement->execute();
	$results = $statement->get_result();
	$row = $results->fetch_assoc();
} catch(mysqli_sql_exception $exception){
	$exception->getMessage();
}

$firstName = $row['userFirstName'];
$middleName = $row['userMiddleName'];
$lastName = $row['userLastName'];
$newDateObj = DateTime::createFromFormat("Y-m-d H:i:s", $row['dateOfBirth']);
$dateOfBirth = $newDateObj->format("Y-m-d");
?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Edit Profile</title>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="editUserProfile.js"></script>
	<script>
		$(function() {
			$( ".datepicker" ).datepicker();
		});
	</script>
</head>
<body>
<form id="editProfile" action="editUserProfileProcessor.php" method="POST">

	<p><label>First Name</label>
		<input type="text" id="first" name="first" value="<?php echo $firstName ?>"></p>
	<p><label>Middle Name</label>
		<input type="text" id="middle" name="middle" value="<?php echo $middleName ?>"><br>
	<p><label>Last Name</label>
		<input type="text" id="last" name="last" value="<?php echo $lastName ?>"></p>
	<p><label>Date Of Birth</label>
	<input type="text" id="dob" class="datepicker" value="<?php echo $dateOfBirth ?>"></p>
		<input type="text" id="dob" name="dob" ></p>
	<p><label>Email</label>
		<input type="email" id="email" name="email" value="<?php echo $email ?>"></p>
	<button type="submit">Submit Changes</button>
</form>
<div id="outputArea"></div>
</body>
</html>
