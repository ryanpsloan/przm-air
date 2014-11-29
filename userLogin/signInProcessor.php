<?php
//include("/etc/apache2/capstone-mysql/przm.php");
include("../lib/csrf.php");

try {
	// verify the form was submitted OK
	session_start();
	if(@isset($_POST["email"]) === false || @isset($_POST["password"]) === false) {
		throw(new RuntimeException("Form variables incomplete or missing. Please fill out the form"));
	}

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
	$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
	$mysqli = MysqliConfiguration::getMysqli();
//grab user by email
	if(!($user = USER::getUserByEmail($mysqli, $email))) {
		throw(new RuntimeException("User not found. Please sign-up."));
	}

	$userPass = hash_pbkdf2("sha512", $password, $user->getSalt(), 2048, 128);
	if(!($userPass === $user->getPassword())) {
		throw(new RuntimeException("Passwords do not match"));
	}

	$_SESSION["userObj"] = $user;
	$_SESSION["authToken"] = $user->authorizationToken;

	echo "Signed in OK";
}catch(Exception $exception)
{
	$exception->getMessage();
}























?>