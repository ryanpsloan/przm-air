<?php

// verify the form was submitted OK
session_start();
if(@isset($_POST["first"]) === false || @isset($_POST["middle"]) === false
		|| @isset($_POST["last"]) === false || @isset($_POST["dob"]) === false
		|| @isset($_POST["email"]) === false || @isset($_POST["password"]) === false
		|| @isset($_POST["confPassword"]) === false)
{
	throw(new RuntimeException("Form variables incomplete or missing. Please fill out the form"));
}

// verify the CSRF tokens
if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
	throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
}

if($firstNm = filter_input(INPUT_POST,"first",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter your first name"));
}
$firstNm = strtolower($firstNm);
if($middleNm = filter_input(INPUT_POST,"middle",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter your middle name"));
}
$middleNm = strtolower($middleNm);
if($lastNm = filter_input(INPUT_POST,"last",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter your last name"));
}
$lastNm = strtolower($lastNm);
if($DOB = filter_input(INPUT_POST,"dob",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter your DOB yyyy-mm-dd"));
}
if((preg_match("/^(\d{4})-(\d{2})-(\d{2})$/",$DOB, $matches)) !== 1) {
	throw(new RangeException("Date of Birth is not a valid date"));
}

$year  = intval($matches[1]);
$month = intval($matches[2]);
$day   = intval($matches[3]);
if(checkdate($month, $day, $year) === false) {
	throw(new RangeException("Date entered is not a Gregorian date"));
}

$DOB = DateTime::createFromFormat("Y-m-d H:i:s", $DOB);


if($email = filter_input(INPUT_POST,"email",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter your email"));
}
if($password = filter_input(INPUT_POST,"password",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Enter a password"));
}
if($password = filter_input(INPUT_POST,"confPassword",FILTER_SANITIZE_STRING) === false){
	throw(new RuntimeException("Confirm your password"));
}
$newUser = new User($email, $password, $salt, $authToken);
$newProfile = new Profile($firstNm, $middleNm, $lastNm, $DOB, $custToken, $newUser);
?>