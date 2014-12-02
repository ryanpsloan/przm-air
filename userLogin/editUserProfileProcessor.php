<?php
require("/etc/apache2/capstone-mysql/przm.php");
require("../php/user.php");
require("../php/profile.php");
$mysqli = MysqliConfiguration::getMysqli();

session_start();

	$profile = $_SESSION['profileObj'];

	$profile->setFirstName($newFirstName = filter_input(INPUT_POST, "first", FILTER_SANITIZE_STRING));
	$profile->setLastName($newLastName = filter_input(INPUT_POST, "last", FILTER_SANITIZE_STRING));
	$newDOB = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_STRING);
	$newDOB = DateTime::createFromFormat("Y-m-d", $newDOB);
	$profile->setDateOfBirth($newDOB->format("Y-m-d H:i:s"));
	$profile->userObj->setEmail($newEmail = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL));
	$newMiddleName = filter_input(INPUT_POST, "middle", FILTER_SANITIZE_STRING);
	if($newMiddleName !== "" || $newMiddleName !== " " || $newMiddleName !== null) {
		$profile->setMiddleName($newMiddleName);
	}

	$profile->update($mysqli);
	$profile->userObj->update($mysqli);

	echo "<p>Your profile has been updated with your changes</p>
			<script>
						$(document).ready(function() {
							$(':input').attr('disabled', true);
						});
			</script>";
	echo "<p><a href='..\index.php'>Home</a></p>";


?>
