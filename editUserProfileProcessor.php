<?php
session_start();
include("../lib/csrf.php");
if(@isset($_POST["first"]) === false || @isset($_POST["middle"]) === false
		|| @isset($_POST["last"]) === false || @isset($_POST["dob"]) === false
				|| @isset($_POST["email"]) === false)
{
	throw(new RuntimeException("Form variables incomplete or missing. Please fill out the form"));
}

if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
	throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
}

$email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
$firstName = filter_input(INPUT_POST,'first',FILTER_SANITIZE_STRING);
$middleName = filter_input(INPUT_POST, 'middle', FILTER_SANITIZE_STRING);
$lastName = filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING);
$dateOfBirth = filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING);



try{
	$query = "INSERT email INTO user VALUES ?";
	$statement = $mysqli->prepare($query);
	$statement = $statement->bind_param("s", $email);
	$statement->execute();

	$query = "INSERT userFirstName, userMiddleName, userLastName, dateOfBirth INTO profile VALUES ?, ?, ?, ?";
	$statement = $mysqli->prepare($query);
	$statement = $statement->bind_param("ssss", $firstName, $middleName, $lastName, $dateOfBirth);
	$statement->execute();
}catch(mysqli_sql_exception $exception){
	$exception->getMessage();
}

echo "<p>New Values Have been saved</p>";
?>