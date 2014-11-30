<?php
include("../lib/csrf.php");
session_start();

if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
	throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
}

$profileObj = $_SESSION['profileObj'];

try {
	$query = "SELECT email FROM user WHERE userId = ?";
	$statement = $mysqli->prepare($query);
	$statment->bind_param("i", $profileObj->__get('userId'));
	$statement->execute();
	$results = $statement->get_results();
	$row = $results->fetch_assoc();
} catch(mysqli_sql_exception $exception){
		$exception->getMessage();
}

$email = $row['email'];


try {
	$query = "SELECT userFirstName, userMiddleName, userLastName, dateOfBirth FROM profile WHERE profileId = ?";
	$statement = $mysqli->prepare($query);
	$statement = $statement->bind_param("i", $profileObj->__get("profileId"));
	$statement->execute();
	$results = $statement->get_results();
	$row = $results->fetch_assoc();
} catch(mysqli_sql_exception $exception){
	$exception->getMessage();
}

$firstName = $row['userFirstName'];
$middleName = $row['userMiddleName'];
$lastName = $row['userLastName'];
$dateOfBirth = $row['dateOfBirth'];
?>


<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Sign Up</title>
</head>
<body>
<form action="editUserProfileProcessor.php" method="POST">
	<?php echo generateInputTags();?>
	<p><label>First Name</label>
		<input type="text" id="first" name="first" required="true" value="<?php echo $firstName ?>"></p>
	<p><label>Middle Name</label>
		<input type="text" id="middle" name="middle" value="<?php echo $middleName ?>"><br>
	<p><label>First Name</label>
		<input type="text" id="last" name="last" required="true" value="<?php echo $lastName ?>"></p>
	<p><label>Date Of Birth</label>
		<input type="datetime" id="dob" name="dob" required="true" value="<?php echo $dateOfBirth ?>"></p>
	<p><label>Email</label>
		<input type="email" id="email" name="email" required="true" value="<?php echo $email ?>"></p>
	<button type="submit">Submit Changes</button>
</form>
</body>
</html>

