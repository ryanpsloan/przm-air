<?php
require("/etc/apache2/capstone-mysql/przm.php");
require("../php/user.php");
require("../php/profile.php");
include("../lib/csrf.php");

try {
	session_start();
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("Make sure cookies are enabled"));
	}
	$mysqli = MysqliConfiguration::getMysqli();
	$profile = $_SESSION['profileObj'];

	$profile->setFirstName($newFirstName = filter_input(INPUT_POST, "first", FILTER_SANITIZE_STRING));
	$profile->setLastName($newLastName = filter_input(INPUT_POST, "last", FILTER_SANITIZE_STRING));
	$newDOB = filter_input(INPUT_POST, "dob", FILTER_SANITIZE_STRING);
	$newDOB = DateTime::createFromFormat("m/d/Y", $newDOB);
	$profile->setDateOfBirth($newDOB->format("Y-m-d H:i:s"));
	$profile->userObj->setEmail($newEmail = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL));
	$newMiddleName = filter_input(INPUT_POST, "middle", FILTER_SANITIZE_STRING);
	if($newMiddleName !== "" || $newMiddleName !== " " || $newMiddleName !== null) {
		$profile->setMiddleName($newMiddleName);
	}

	$profile->update($mysqli);
	$profile->userObj->update($mysqli);

	echo "<div class='alert alert-success' role='alert'>
  			Your profile has been updated with your changes</div>
			<script>
						$(document).ready(function() {
							$(':input').attr('disabled', true);
						});
			</script>";
	echo "<p><a href='..\index.php'>Home</a></p>";
}catch (Exception $e){
	echo "<div class='alert alert-danger' role='alert'>"
  .$e->getMessage."</div>";
}
?>
